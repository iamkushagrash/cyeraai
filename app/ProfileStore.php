<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProfileStore extends Model
{
    protected $fillable = ['id', 'price', 'created_at', 'updated_at'];

    /**
     * Cache for live price queries within the same request lifecycle.
     */
    protected static $cachedLivePrice = null;

    /**
     * Get real-time live DEX price for CAI/USDT (used for display, live charts, and 1-click DEX Mining & Liquidation).
     * Does NOT alter the database base price (1.00).
     *
     * @return float
     */
    public static function getLivePrice()
    {
        if (self::$cachedLivePrice !== null && self::$cachedLivePrice > 0) {
            return self::$cachedLivePrice;
        }

        $pairAddress = env('PANCAKESWAP_PAIR_ADDRESS') ?: '0xBD51320E33Ae6aCE0b41639c5c850634D218Bc2e';
        $caiAddress = env('CAI_TOKEN_ADDRESS') ?: '0xbb4bf90D809974606a832cAD2628Ae35Fe7BB8eC';
        $usdtAddress = env('USDT_TOKEN_ADDRESS') ?: '0x55d398326f99059fF775485246999027B3197955';
        $livePrice = null;

        // 1. Fetch live DEX price from DexScreener API with fast cURL timeout
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.dexscreener.com/latest/dex/pairs/bsc/" . $pairAddress);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'CyeraAI-PriceEngine/1.0');
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['pairs'][0]['priceUsd']) && is_numeric($data['pairs'][0]['priceUsd']) && (float) $data['pairs'][0]['priceUsd'] > 0) {
                    $livePrice = round((float) $data['pairs'][0]['priceUsd'], 6);
                }
            }
        } catch (\Exception $e) {
            Log::warning("DexScreener live price fetch error: " . $e->getMessage());
        }

        // 1B. Direct On-Chain PancakeSwap Pair getReserves() Fallback (Instant live price even before DexScreener indexes)
        if (!$livePrice || $livePrice <= 0) {
            try {
                $rpcUrl = env('BSC_MAINNET_RPC') ?: 'https://bsc-dataseed.binance.org/';
                $payload = json_encode([
                    'jsonrpc' => '2.0',
                    'id' => 1,
                    'method' => 'eth_call',
                    'params' => [
                        ['to' => $pairAddress, 'data' => '0x0902f1ac'], // getReserves()
                        'latest'
                    ]
                ]);
                $ch = curl_init($rpcUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $res = curl_exec($ch);
                curl_close($ch);

                if ($res) {
                    $json = json_decode($res, true);
                    if (isset($json['result']) && strlen($json['result']) >= 130) {
                        $hex = substr($json['result'], 2);
                        $r0 = (float) hexdec(substr($hex, 0, 64));
                        $r1 = (float) hexdec(substr($hex, 64, 64));
                        if ($r0 > 0 && $r1 > 0) {
                            // UniswapV2 / PancakeSwapV2 deterministically orders token0 < token1 by lowercase hex address
                            $isCaaiToken0 = strtolower($caiAddress) < strtolower($usdtAddress);
                            $onChainPrice = $isCaaiToken0 ? ($r1 / $r0) : ($r0 / $r1);
                            if ($onChainPrice > 0.0001 && $onChainPrice < 100000) {
                                $livePrice = round($onChainPrice, 6);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("On-chain getReserves live price fallback error: " . $e->getMessage());
            }
        }

        if ($livePrice && $livePrice > 0) {
            self::$cachedLivePrice = $livePrice;
            return $livePrice;
        }

        // 2. Fallback to DB price (Base 1.00)
        $profile = self::where('id', 1)->first();
        $fallbackPrice = ($profile && (float) $profile->price > 0) ? (float) $profile->price : 1.00;
        self::$cachedLivePrice = $fallbackPrice;
        return $fallbackPrice;
    }
}
