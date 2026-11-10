<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfileStore;
use Illuminate\Support\Facades\Log;

class PriceUpdateController extends Controller
{
    /**
     * Official PancakeSwap V2 Pair Address for CAAI/USDT on BSC Mainnet (Protocol V3).
     */
    const PAIR_ADDRESS = '0xBD51320E33Ae6aCE0b41639c5c850634D218Bc2e';

    /**
     * CAAI Token Contract Address (Protocol V3).
     */
    const CAI_ADDRESS = '0xbb4bf90D809974606a832cAD2628Ae35Fe7BB8eC';

    /**
     * BSC-USD (USDT) Contract Address on BSC Mainnet.
     */
    const USDT_ADDRESS = '0x55d398326f99059fF775485246999027B3197955';

    /**
     * Fetch live CAI/USDT price from DEX (DexScreener API with On-chain RPC Fallback)
     * and update ProfileStore in database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCaiPrice()
    {
        $newPrice = null;
        $source = 'none';
        $pairAddr = env('PANCAKESWAP_PAIR_ADDRESS') ?: self::PAIR_ADDRESS;

        // 1. Primary Source: DexScreener Real-Time Indexer API
        try {
            $url = "https://api.dexscreener.com/latest/dex/pairs/bsc/" . $pairAddr;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'CyeraAI-PriceEngine/1.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && !empty($response)) {
                $data = json_decode($response, true);
                if (isset($data['pairs'][0]['priceUsd'])) {
                    $val = floatval($data['pairs'][0]['priceUsd']);
                    if ($val > 0) {
                        $newPrice = $val;
                        $source = 'DexScreener API';
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("DexScreener Price Fetch Error: " . $e->getMessage());
        }

        // 2. Fallback Source: Direct On-Chain BSC RPC via PancakeSwap Pair getReserves()
        if ($newPrice === null || $newPrice <= 0) {
            try {
                $onChainPrice = $this->fetchOnChainReservesPrice();
                if ($onChainPrice > 0) {
                    $newPrice = $onChainPrice;
                    $source = 'On-Chain PancakeSwap Pair RPC';
                }
            } catch (\Exception $e) {
                Log::error("On-Chain RPC Price Fetch Error: " . $e->getMessage());
            }
        }

        // 3. Database Update
        if ($newPrice !== null && $newPrice > 0) {
            $profile = ProfileStore::where('id', 1)->first();
            $oldPrice = $profile ? $profile->price : null;

            if ($profile) {
                $profile->price = $newPrice;
                $profile->updated_at = now();
                $profile->save();
            } else {
                ProfileStore::create([
                    'id' => 1,
                    'price' => $newPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info("CAI Price Updated [{$source}]: {$oldPrice} -> {$newPrice} USD");

            return response()->json([
                'status' => true,
                'message' => 'CAI live price updated successfully',
                'price' => $newPrice,
                'source' => $source,
                'old_price' => $oldPrice,
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to fetch live CAI price from all sources',
            'timestamp' => now()->toDateTimeString(),
        ], 500);
    }

    /**
     * Query BSC RPC directly for PancakeSwap pair reserves (getReserves: 0x0902f1ac).
     */
    private function fetchOnChainReservesPrice()
    {
        $rpcUrl = env('BSC_MAINNET_RPC') ?: env('BSC_RPC_URL', 'https://bsc-dataseed1.binance.org/');
        $pairAddr = env('PANCAKESWAP_PAIR_ADDRESS') ?: self::PAIR_ADDRESS;
        $caiAddr = env('CAI_TOKEN_ADDRESS') ?: self::CAI_ADDRESS;
        $usdtAddr = env('USDT_TOKEN_ADDRESS') ?: self::USDT_ADDRESS;

        $payload = json_encode([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_call',
            'params' => [
                [
                    'to' => $pairAddr,
                    'data' => '0x0902f1ac' // getReserves()
                ],
                'latest'
            ]
        ]);

        $ch = curl_init($rpcUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($res, true);
        if (isset($json['result']) && strlen($json['result']) >= 130) {
            $hex = substr($json['result'], 2);
            $r0Hex = substr($hex, 0, 64);
            $r1Hex = substr($hex, 64, 64);

            $r0 = (float) hexdec($r0Hex);
            $r1 = (float) hexdec($r1Hex);

            // PancakeSwap V2 deterministically orders token0 < token1 by lowercase hex address
            if ($r0 > 0 && $r1 > 0) {
                $isCaaiToken0 = strtolower($caiAddr) < strtolower($usdtAddr);
                $price = $isCaaiToken0 ? ($r1 / $r0) : ($r0 / $r1);
                if ($price > 0.0001 && $price < 100000) {
                    return round($price, 6);
                }
            }
        }

        return 0;
    }
}
