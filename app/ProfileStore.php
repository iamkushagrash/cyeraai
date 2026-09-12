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

        $pairAddress = env('PANCAKESWAP_PAIR_ADDRESS', '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c');
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
