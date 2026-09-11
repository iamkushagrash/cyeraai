<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfileStore;
use Illuminate\Support\Facades\Log;

class PriceUpdateController extends Controller
{
    /**
     * Official PancakeSwap V2 Pair Address for CAI/USDT on BSC Mainnet.
     */
    const PAIR_ADDRESS = '0x62e0b6c229a096f9b5ff4e03c58907187e0ab749';

    /**
     * CAI Token Contract Address.
     */
    const CAI_ADDRESS = '0x5cb5452DE7044E551137985cE1d7C2D42e7bAf5f';

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

        // 1. Primary Source: DexScreener Real-Time Indexer API
        try {
            $url = "https://api.dexscreener.com/latest/dex/pairs/bsc/" . self::PAIR_ADDRESS;
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
        $rpcUrl = env('BSC_RPC_URL', 'https://bsc-dataseed1.binance.org/');
        $payload = json_encode([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_call',
            'params' => [
                [
                    'to' => self::PAIR_ADDRESS,
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

            $r0 = hexdec($r0Hex);
            $r1 = hexdec($r1Hex);

            // Pair order: Token0 = CAI (or USDT), Token1 = USDT (or CAI)
            if ($r0 > 0 && $r1 > 0) {
                // In our pair on BSC, Token0 is CAI and Token1 is BSC-USD
                $price = ($r1 / $r0);
                if ($price > 0.0001 && $price < 100000) {
                    return round($price, 6);
                }
            }
        }

        return 0;
    }
}
