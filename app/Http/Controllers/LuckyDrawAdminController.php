<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\LuckyDrawDetail;
use App\LuckyDrawRound;
use App\LuckyDrawParticipant;
use App\LuckyDrawWinner;
use App\RoyalWalletLedger;
use App\UserDetails;
use Elliptic\EC;
use kornrunner\Keccak;

class LuckyDrawAdminController extends Controller
{
    /**
     * Primary Deployer & Authorized Admin Wallets
     */
    protected static function getAuthorizedWallets()
    {
        $wallets = [
            // Deployer wallet from LUCKYDRAW_PRIVATE_KEY
            strtolower('0xEA8faf283e912596818115ca17dE10d5e4f8BC80'),
            // Cyera Admin Owner Address from .env
            strtolower(env('ADMIN_OWNER_ADDRESS', '0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7')),
            // Whitelisted collector wallet
            strtolower(env('ROYAL_JACKPOT_COLLECTOR_WALLET', '0xEA8faf283e912596818115ca17dE10d5e4f8BC80')),
        ];

        // Additional comma-separated wallets from .env
        $custom = env('LUCKYDRAW_ADMIN_WALLETS', '');
        if (!empty($custom)) {
            foreach (explode(',', $custom) as $w) {
                $w = strtolower(trim($w));
                if (!empty($w) && !in_array($w, $wallets)) {
                    $wallets[] = $w;
                }
            }
        }

        return array_unique($wallets);
    }

    /**
     * Check if a given wallet address is authorized as Deployer/Admin
     */
    public static function isAuthorized($wallet)
    {
        if (empty($wallet)) {
            return false;
        }
        $wallet = strtolower(trim($wallet));
        return in_array($wallet, self::getAuthorizedWallets());
    }

    /**
     * Common config passed to all admin views
     */
    protected function getContractsConfig()
    {
        $authorizedWallets = self::getAuthorizedWallets();
        $primaryDeployer = '0xEA8faf283e912596818115ca17dE10d5e4f8BC80';

        return [
            'depositContract'   => env('ROYAL_JACKPOT_DEPOSIT_ADDRESS', '0xB97D65c4A653a280d946A50FFe6237EDbEA4C0C5'),
            'vaultContract'     => env('ROYAL_JACKPOT_VAULT_ADDRESS', '0xF68506dAA4BD94B65112816DB41Bf0F371186B16'),
            'collectorWallet'   => env('ROYAL_JACKPOT_COLLECTOR_WALLET', '0xEA8faf283e912596818115ca17dE10d5e4f8BC80'),
            'adminOwnerAddress' => env('ADMIN_OWNER_ADDRESS', '0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7'),
            'backendSigner'     => env('BACKEND_SIGNER_ADDRESS', '0x188525c9810749a8012Dd1eBfA4a28801bac0a6e'),
            'usdtToken'         => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'chainId'           => 56, // BSC Mainnet
            'bscRpc'            => 'https://bsc-dataseed.binance.org/',
            'primaryDeployer'   => $primaryDeployer,
            'authorizedWallets' => $authorizedWallets,
            'currentSession'    => Session::get('luckydraw_admin_wallet'),
        ];
    }

    /**
     * Query BSC node for live on-chain balances
     */
    public static function fetchOnChainBalances()
    {
        $deposit = env('ROYAL_JACKPOT_DEPOSIT_ADDRESS', '0xB97D65c4A653a280d946A50FFe6237EDbEA4C0C5');
        $vault   = env('ROYAL_JACKPOT_VAULT_ADDRESS', '0xF68506dAA4BD94B65112816DB41Bf0F371186B16');
        $collector = env('ROYAL_JACKPOT_COLLECTOR_WALLET', '0xEA8faf283e912596818115ca17dE10d5e4f8BC80');
        $usdt    = env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955');

        $rpc = 'https://bsc-dataseed.binance.org/';

        // Helper for eth_call
        $callRpc = function ($payload) use ($rpc) {
            $ch = curl_init($rpc);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res ? json_decode($res, true) : null;
        };

        // USDT balanceOf signature: 0x70a08231 + 32-byte address
        $padAddress = function ($addr) {
            return str_pad(substr(strtolower($addr), 2), 64, '0', STR_PAD_LEFT);
        };

        // Batch RPC calls
        $batch = [
            // 0: Deposit USDT balance
            ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'eth_call', 'params' => [['to' => $usdt, 'data' => '0x70a08231' . $padAddress($deposit)], 'latest']],
            // 1: Vault USDT balance
            ['jsonrpc' => '2.0', 'id' => 2, 'method' => 'eth_call', 'params' => [['to' => $usdt, 'data' => '0x70a08231' . $padAddress($vault)], 'latest']],
            // 2: Collector USDT balance
            ['jsonrpc' => '2.0', 'id' => 3, 'method' => 'eth_call', 'params' => [['to' => $usdt, 'data' => '0x70a08231' . $padAddress($collector)], 'latest']],
            // 3: Collector BNB balance
            ['jsonrpc' => '2.0', 'id' => 4, 'method' => 'eth_getBalance', 'params' => [$collector, 'latest']],
            // 4: Deposit autoForward() -> 0x8ff695ee
            ['jsonrpc' => '2.0', 'id' => 5, 'method' => 'eth_call', 'params' => [['to' => $deposit, 'data' => '0x8ff695ee'], 'latest']],
            // 5: Deposit paused() -> 0x5c975abb
            ['jsonrpc' => '2.0', 'id' => 6, 'method' => 'eth_call', 'params' => [['to' => $deposit, 'data' => '0x5c975abb'], 'latest']],
            // 6: Vault paused() -> 0x5c975abb
            ['jsonrpc' => '2.0', 'id' => 7, 'method' => 'eth_call', 'params' => [['to' => $vault, 'data' => '0x5c975abb'], 'latest']],
        ];

        $results = $callRpc($batch);

        $fromWei18 = function ($hex) {
            if (!$hex || $hex === '0x' || $hex === '0x0') return 0.0;
            $dec = hexdec(substr($hex, 2));
            // For large 18-decimal numbers
            if (strlen($hex) > 10) {
                // Approximate with bcmath or gmp if available
                $hexClean = ltrim(substr($hex, 2), '0');
                if (empty($hexClean)) return 0.0;
                $val = base_convert($hexClean, 16, 10);
                return round($val / 1e18, 4);
            }
            return round($dec / 1e18, 4);
        };

        $depositUsdt = 0.0;
        $vaultUsdt = 0.0;
        $collectorUsdt = 0.0;
        $collectorBnb = 0.0;
        $autoForward = false;
        $depositPaused = false;
        $vaultPaused = false;

        if (is_array($results)) {
            foreach ($results as $item) {
                $id = $item['id'] ?? 0;
                $res = $item['result'] ?? '0x0';
                if ($id === 1) $depositUsdt = $fromWei18($res);
                if ($id === 2) $vaultUsdt = $fromWei18($res);
                if ($id === 3) $collectorUsdt = $fromWei18($res);
                if ($id === 4) $collectorBnb = $fromWei18($res);
                if ($id === 5) $autoForward = (hexdec($res) == 1);
                if ($id === 6) $depositPaused = (hexdec($res) == 1);
                if ($id === 7) $vaultPaused = (hexdec($res) == 1);
            }
        }

        return [
            'depositUsdt'   => $depositUsdt,
            'vaultUsdt'     => $vaultUsdt,
            'collectorUsdt' => $collectorUsdt,
            'collectorBnb'  => $collectorBnb,
            'autoForward'   => $autoForward,
            'depositPaused' => $depositPaused,
            'vaultPaused'   => $vaultPaused,
        ];
    }

    /**
     * 1. Main Dashboard & Analytics Overview (/luckydraw/admin)
     */
    public function index()
    {
        $contracts = $this->getContractsConfig();
        $onChain = self::fetchOnChainBalances();

        // Database pool stats
        $pools = LuckyDrawDetail::orderBy('pool_tier', 'asc')->get();
        $poolStats = [];
        $totalCollectedDb = 0;
        $totalActiveTickets = 0;

        foreach ($pools as $p) {
            $activeRound = LuckyDrawRound::where('pool_tier', $p->pool_tier)
                ->where('status', 0)
                ->first();

            $completedCount = LuckyDrawRound::where('pool_tier', $p->pool_tier)
                ->where('status', 1)
                ->count();

            $totalParticipants = LuckyDrawParticipant::where('pool_tier', $p->pool_tier)->count();
            $totalVolume = LuckyDrawRound::where('pool_tier', $p->pool_tier)->sum('total_collected');

            $totalCollectedDb += $totalVolume;
            $totalActiveTickets += ($activeRound ? $activeRound->current_members : 0);

            $poolStats[] = [
                'tier'              => $p->pool_tier,
                'name'              => $p->pool_name,
                'price'             => $p->ticket_price,
                'current_round'     => $activeRound ? $activeRound->round_number : ($completedCount + 1),
                'current_members'   => $activeRound ? $activeRound->current_members : 0,
                'slots_left'        => $activeRound ? max(0, 500 - $activeRound->current_members) : 500,
                'completed_rounds'  => $completedCount,
                'total_tickets'     => $totalParticipants,
                'total_volume'      => $totalVolume,
            ];
        }

        // Aggregate system metrics
        $metrics = [
            'totalTicketsAllTime' => LuckyDrawParticipant::count(),
            'totalRoundsCount'    => LuckyDrawRound::count(),
            'completedRounds'     => LuckyDrawRound::where('status', 1)->count(),
            'totalWinners'        => LuckyDrawWinner::count(),
            'totalPrizeWon'       => (float) LuckyDrawWinner::sum('prize_amount'),
            'totalCollectedDb'    => $totalCollectedDb,
            'royalWalletBalance'  => (float) UserDetails::sum('royal_wallet'),
            'totalClaimedOnChain' => (float) RoyalWalletLedger::where('type', 'CLAIM_CRYPTO')->where('status', 'COMPLETED')->sum('amount'),
            'pendingClaims'       => RoyalWalletLedger::where('type', 'CLAIM_CRYPTO')->where('status', 'PENDING')->count(),
        ];

        // Recent participants
        $recentParticipants = LuckyDrawParticipant::with(['round'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        // Recent winners
        $recentWinners = LuckyDrawWinner::with(['round'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        return view('luckydraw-admin.dashboard', compact(
            'contracts',
            'onChain',
            'poolStats',
            'metrics',
            'recentParticipants',
            'recentWinners'
        ));
    }

    /**
     * 2. Dedicated Fund Management & Transfer Page (/luckydraw/admin/funds)
     * Direct transfer of collected funds to any wallet + Vault payout liquidity management
     */
    public function funds()
    {
        $contracts = $this->getContractsConfig();
        $onChain = self::fetchOnChainBalances();

        // Claims and Payout tracking
        $claimLedgers = RoyalWalletLedger::where('type', 'CLAIM_CRYPTO')
            ->orderBy('id', 'desc')
            ->take(25)
            ->get();

        $metrics = [
            'totalCollectedDb'    => (float) LuckyDrawRound::sum('total_collected'),
            'totalPrizesWon'      => (float) LuckyDrawWinner::sum('prize_amount'),
            'totalClaimedOnChain' => (float) RoyalWalletLedger::where('type', 'CLAIM_CRYPTO')->where('status', 'COMPLETED')->sum('amount'),
            'pendingClaimsCount'  => RoyalWalletLedger::where('type', 'CLAIM_CRYPTO')->where('status', 'PENDING')->count(),
            'royalWalletBalance'  => (float) UserDetails::sum('royal_wallet'),
        ];

        return view('luckydraw-admin.funds', compact(
            'contracts',
            'onChain',
            'claimLedgers',
            'metrics'
        ));
    }

    /**
     * 3. Pools & Rounds Explorer (/luckydraw/admin/rounds)
     */
    public function rounds(Request $request)
    {
        $contracts = $this->getContractsConfig();
        $tier = $request->get('tier', 1);

        $rounds = LuckyDrawRound::where('pool_tier', $tier)
            ->orderBy('round_number', 'desc')
            ->paginate(15);

        $selectedRoundId = $request->get('round_id');
        $participants = [];
        $selectedRound = null;

        if ($selectedRoundId) {
            $selectedRound = LuckyDrawRound::find($selectedRoundId);
            if ($selectedRound) {
                $participants = LuckyDrawParticipant::where('round_id', $selectedRoundId)
                    ->orderBy('ticket_number', 'asc')
                    ->paginate(50);
            }
        } else {
            // Default to current active round
            $selectedRound = LuckyDrawRound::where('pool_tier', $tier)->where('status', 0)->first();
            if ($selectedRound) {
                $participants = LuckyDrawParticipant::where('round_id', $selectedRound->id)
                    ->orderBy('ticket_number', 'asc')
                    ->paginate(50);
            }
        }

        $pools = LuckyDrawDetail::orderBy('pool_tier', 'asc')->get();

        return view('luckydraw-admin.rounds', compact(
            'contracts',
            'pools',
            'tier',
            'rounds',
            'selectedRound',
            'participants'
        ));
    }

    /**
     * 4. Winners & Claims Ledger (/luckydraw/admin/winners)
     */
    public function winners(Request $request)
    {
        $contracts = $this->getContractsConfig();
        $tier = $request->get('tier');
        $roundNumber = $request->get('round');

        $query = LuckyDrawWinner::with(['round', 'participant']);

        if ($tier) {
            $query->where('pool_tier', $tier);
        }
        if ($roundNumber) {
            $query->whereHas('round', function ($q) use ($roundNumber) {
                $q->where('round_number', $roundNumber);
            });
        }

        $winners = $query->orderBy('id', 'desc')->paginate(30);
        $pools = LuckyDrawDetail::orderBy('pool_tier', 'asc')->get();

        return view('luckydraw-admin.winners', compact(
            'contracts',
            'winners',
            'pools',
            'tier',
            'roundNumber'
        ));
    }

    /**
     * 5. Smart Contract Controls & System Settings (/luckydraw/admin/settings)
     */
    public function settings()
    {
        $contracts = $this->getContractsConfig();
        $onChain = self::fetchOnChainBalances();
        $pools = LuckyDrawDetail::orderBy('pool_tier', 'asc')->get();

        return view('luckydraw-admin.settings', compact(
            'contracts',
            'onChain',
            'pools'
        ));
    }

    /**
     * API: Challenge Nonce for Web3 Deployer Wallet Login
     */
    public function getAuthChallenge(Request $request)
    {
        $request->validate([
            'address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/'
        ]);

        $address = strtolower(trim($request->address));
        if (!self::isAuthorized($address)) {
            return response()->json([
                'status'  => 'error',
                'message' => "Access Denied: Wallet {$address} is not authorized. Only the Lucky Draw Deployer Wallet (0xEA8faf...BC80) can access this portal."
            ], 403);
        }

        $nonce = bin2hex(random_bytes(16));
        $timestamp = time();

        Session::put('luckydraw_auth_nonce', $nonce);
        Session::put('luckydraw_auth_address', $address);

        $message = "👑 CYERA ROYAL JACKPOT • DEPLOYER ADMIN ACCESS\n\nSign this cryptographic challenge to authenticate administrative access.\n\nDeployer Wallet: {$address}\nNonce: {$nonce}\nTimestamp: {$timestamp}";

        return response()->json([
            'status'    => 'success',
            'nonce'     => $nonce,
            'message'   => $message,
            'timestamp' => $timestamp,
        ]);
    }

    /**
     * API: Authenticate Web3 Signature and Establish Admin Session
     */
    public function authLogin(Request $request)
    {
        $request->validate([
            'address'   => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
            'signature' => 'required|string|regex:/^0x[a-fA-F0-9]{130}$/',
            'message'   => 'required|string'
        ]);

        $address = strtolower(trim($request->address));
        if (!self::isAuthorized($address)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized deployer wallet.'], 403);
        }

        $expectedAddress = Session::get('luckydraw_auth_address');
        if ($expectedAddress && strtolower($expectedAddress) !== $address) {
            return response()->json(['status' => 'error', 'message' => 'Address mismatch with session challenge.'], 400);
        }

        // Verify cryptographic signature
        $recovered = self::verifyPersonalSignature($request->message, $request->signature, $address);
        if (!$recovered) {
            return response()->json(['status' => 'error', 'message' => 'Cryptographic signature verification failed.'], 401);
        }

        // Set authenticated deployer admin session
        Session::put('luckydraw_admin_wallet', $address);
        Session::put('luckydraw_admin_time', time());

        return response()->json([
            'status'  => 'success',
            'message' => 'Deployer Admin authentication successful.',
            'wallet'  => $address
        ]);
    }

    /**
     * API: Logout Admin Session
     */
    public function logout()
    {
        Session::forget('luckydraw_admin_wallet');
        Session::forget('luckydraw_admin_time');
        Session::forget('luckydraw_auth_nonce');
        Session::forget('luckydraw_auth_address');

        return response()->json(['status' => 'success', 'message' => 'Logged out successfully.']);
    }

    /**
     * API: Live Stats Polling
     */
    public function getStats()
    {
        $onChain = self::fetchOnChainBalances();
        $totalCollectedDb = (float) LuckyDrawRound::sum('total_collected');
        $activeMembers = (int) LuckyDrawRound::where('status', 0)->sum('current_members');

        return response()->json([
            'status'    => 'success',
            'on_chain'  => $onChain,
            'database'  => [
                'total_collected' => $totalCollectedDb,
                'active_members'  => $activeMembers,
                'total_tickets'   => LuckyDrawParticipant::count(),
            ]
        ]);
    }

    /**
     * Cryptographic EIP-191 personal_sign signature verification
     */
    protected static function verifyPersonalSignature($message, $signature, $address)
    {
        try {
            $msgLen = strlen($message);
            $prefix = "\x19Ethereum Signed Message:\n{$msgLen}";
            $hashHex = Keccak::hash($prefix . $message, 256);

            $sig = substr($signature, 2); // remove 0x
            if (strlen($sig) !== 130) {
                return false;
            }

            $r = substr($sig, 0, 64);
            $s = substr($sig, 64, 64);
            $v = hexdec(substr($sig, 128, 2));

            if ($v >= 27) {
                $v -= 27;
            }

            $ec = new EC('secp256k1');
            $pubKey = $ec->recoverPubKey($hashHex, ['r' => $r, 's' => $s], $v);
            $pubHex = $pubKey->encode('hex');

            $pubKeccak = Keccak::hash(substr(hex2bin($pubHex), 1), 256);
            $recoveredAddress = '0x' . substr($pubKeccak, -40);

            return strtolower($recoveredAddress) === strtolower($address);
        } catch (\Exception $e) {
            \Log::error('LuckyDraw Admin Signature Recovery Error: ' . $e->getMessage());
            return false;
        }
    }
}
