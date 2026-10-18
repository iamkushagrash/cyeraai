<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Session;
use Elliptic\EC;
use kornrunner\Keccak;

class ContractAdminController extends Controller
{
    /**
     * Primary V2 Owner Wallet
     */
    const OWNER_WALLET = '0xB68e29B51BB9B0856211cb2856215e3F68243622';

    /**
     * Get list of authorized admin wallets (Strictly Owner Wallet)
     */
    public static function getAuthorizedWallets()
    {
        return [
            strtolower(self::OWNER_WALLET),
            strtolower(env('ADMIN_OWNER_ADDRESS', self::OWNER_WALLET)),
        ];
    }

    /**
     * Check if user is authenticated in session with the Owner Wallet
     */
    public static function isAuthenticated()
    {
        $owner = strtolower(self::OWNER_WALLET);
        $sessionWallet = strtolower((string) Session::get('contract_admin_wallet', ''));
        return Session::get('contract_admin_authenticated', false) === true && !empty($sessionWallet) && ($sessionWallet === $owner || in_array($sessionWallet, self::getAuthorizedWallets()));
    }

    /**
     * Render the Executive Login Gate
     */
    public function loginPage(Request $request)
    {
        if (self::isAuthenticated()) {
            return redirect('/dishi/yashi');
        }

        $ownerWallet = self::OWNER_WALLET;
        return view('contract-admin.login', compact('ownerWallet'));
    }

    /**
     * Issue cryptographic challenge nonce for MetaMask authentication
     */
    public function getChallenge(Request $request)
    {
        $owner = self::OWNER_WALLET;
        $nonce = bin2hex(random_bytes(16));
        $time = gmdate('Y-m-d H:i:s') . ' UTC';
        
        $message = "CYERA PROTOCOL V2 — SECURE ADMIN AUTHENTICATION\n\n" .
                   "Designated Owner: {$owner}\n" .
                   "Timestamp: {$time}\n" .
                   "Nonce: {$nonce}\n\n" .
                   "Sign this message with your MetaMask wallet to authenticate as the Cyera Protocol Owner.";

        Session::put('contract_admin_nonce', $nonce);
        Session::put('contract_admin_challenge_msg', $message);
        Session::put('contract_admin_challenge_created_at', time());

        return response()->json([
            'status'  => 'success',
            'owner'   => $owner,
            'nonce'   => $nonce,
            'message' => $message,
        ]);
    }

    /**
     * Cryptographically verify an Ethereum personal_sign (EIP-191) signature using secp256k1 and Keccak-256
     */
    public static function verifyPersonalSignature($message, $signature, $expectedAddress)
    {
        if (empty($message) || empty($signature) || empty($expectedAddress)) {
            return false;
        }

        // 1. Ethereum Signed Message Prefix
        $prefix = "\x19Ethereum Signed Message:\n" . strlen($message);
        $hashHex = Keccak::hash($prefix . $message, 256);

        // 2. Format signature
        $sig = $signature;
        if (strpos($sig, '0x') === 0) {
            $sig = substr($sig, 2);
        }
        if (strlen($sig) !== 130) {
            return false;
        }

        $r = substr($sig, 0, 64);
        $s = substr($sig, 64, 64);
        $v = hexdec(substr($sig, 128, 2));
        if ($v >= 27) {
            $v -= 27;
        }

        try {
            $ec = new EC('secp256k1');
            $pubKey = $ec->recoverPubKey($hashHex, ['r' => $r, 's' => $s], $v);
            $pubHex = $pubKey->encode('hex');
            $pubBytes = substr(hex2bin($pubHex), 1);
            $recoveredAddress = '0x' . substr(Keccak::hash($pubBytes, 256), -40);

            return strtolower(trim($recoveredAddress)) === strtolower(trim($expectedAddress));
        } catch (\Exception $e) {
            \Log::warning('MetaMask Signature Verification Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Login: MetaMask Cryptographic Authentication (Strictly Owner 0xB68e...3622, case-insensitive)
     */
    public function processLogin(Request $request)
    {
        // Support both input names: address or wallet_address
        $wallet = strtolower(trim((string) ($request->input('address') ?: $request->input('wallet_address'))));
        $signature = trim((string) $request->input('signature', ''));
        $message = (string) $request->input('message', '');

        $primaryOwner = strtolower(self::OWNER_WALLET);
        $authorized = self::getAuthorizedWallets();

        // 1. Strict Owner Wallet Verification (Case-Insensitive)
        if (empty($wallet) || ($wallet !== $primaryOwner && !in_array($wallet, $authorized))) {
            $formattedProvided = !empty($wallet) ? substr($wallet, 0, 6) . '...' . substr($wallet, -4) : 'Empty';
            $formattedOwner = substr(self::OWNER_WALLET, 0, 6) . '...' . substr(self::OWNER_WALLET, -4);
            
            return response()->json([
                'status'  => 'error',
                'message' => "Access Denied: Wallet {$formattedProvided} is not authorized. ONLY the Cyera Protocol Owner Wallet ({$formattedOwner}) can unlock this Command Center."
            ], 403);
        }

        // 2. MetaMask Cryptographic Signature Verification
        if (!empty($signature)) {
            $isValid = self::verifyPersonalSignature($message, $signature, $wallet);
            
            if (!$isValid) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'MetaMask Cryptographic Verification Failed: Signature does not match the Protocol Owner Wallet.'
                ], 401);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'MetaMask Signature Required: Please click "Authenticate with MetaMask" to sign the authentication challenge.'
            ], 400);
        }

        // 3. Set Authenticated Session
        Session::put('contract_admin_authenticated', true);
        Session::put('contract_admin_wallet', self::OWNER_WALLET);
        Session::put('contract_admin_login_at', Carbon::now()->toDateTimeString());

        // Clear used challenge
        Session::forget(['contract_admin_nonce', 'contract_admin_challenge_msg']);

        return response()->json([
            'status'   => 'success',
            'redirect' => url('/dishi/yashi'),
            'message'  => 'MetaMask verification successful! Access granted. Unlocking Command Center...'
        ]);
    }

    /**
     * Logout & terminate Contract Admin session
     */
    public function logout(Request $request)
    {
        Session::forget('contract_admin_authenticated');
        Session::forget('contract_admin_wallet');
        Session::forget('contract_admin_login_at');

        return redirect('/dishi/yashi/login')->with('success', 'Logged out successfully.');
    }

    /**
     * Display the Unified White-Theme MLM & Smart Contract Governance Command Center.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!self::isAuthenticated()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'unauthorized', 'message' => 'Authentication required.'], 401);
            }
            return redirect('/dishi/yashi/login');
        }

        // 1. Live V2 Deployed Contracts & Architecture Configuration
        $contracts = [
            'adminOwnerAddress'         => env('ADMIN_OWNER_ADDRESS', '0xB68e29B51BB9B0856211cb2856215e3F68243622'),
            'caiTreasuryWallet'         => env('CAI_TREASURY_WALLET', '0xB68e29B51BB9B0856211cb2856215e3F68243622'),
            'backendSignerAddress'      => env('BACKEND_SIGNER_ADDRESS', '0xEf537Abb8feD481D3C26d8ce0923bCcFD1f99a57'),
            
            'usdtTokenAddress'          => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'caiTokenAddress'           => env('CAI_TOKEN_ADDRESS', '0x193149028a3C83009Ecda6D5E76f1abD62E51a43'),
            'pancakePairAddress'        => env('PANCAKESWAP_PAIR_ADDRESS', '0x17C405d90e8d112c340698EE2b09EB31FF72f730'),
            'pancakeRouterAddress'      => env('PANCAKE_ROUTER_ADDRESS', '0x10ED43C718714eb63d5aA57B78B54704E256024E'),
            'miningEngineAddress'       => env('CYERA_MINING_ENGINE_ADDRESS', '0x61e1f46EA4f6892edF72f172eAcBC82Fa61dfbb6'),
            'revenueSplitterAddress'    => env('REVENUE_SPLITTER_ADDRESS', '0x027932cE4b00b4d89BC2209Df556cfdD7e72E822'),
            'usdtWithdrawalVaultAddress' => env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x15599D8f0Da534ae908885f4905abC6718f1BaD7'),
            
            'royalJackpotDepositAddress'=> env('ROYAL_JACKPOT_DEPOSIT_ADDRESS', '0x029792e00FB340c384aA226960321dC3c1D9b7E9'),
            'royalJackpotVaultAddress'  => env('ROYAL_JACKPOT_VAULT_ADDRESS', '0xd28279388c164E1773519c6F59755Baf62AfD6f5'),
            'royalJackpotCollector'     => env('ROYAL_JACKPOT_COLLECTOR_WALLET', '0xBb83f1dbc26f6a414350E3EaBB5F69E28Bfef3d9'),
            
            'tradingWallet'             => env('TRADING_WALLET_ADDRESS', '0xAC2aa717c6f4c18D2e4b1DD11aa17BFCA8cc8D53'),
            'reserveWallet'             => env('RESERVE_WALLET_ADDRESS', '0x63BAF2Aa307DC8a456690a705c40d63194C0dd70'),
            
            'bscMainnetRpc'             => env('BSC_MAINNET_RPC', 'https://bsc-dataseed.binance.org/'),
            'chainId'                   => 56,
        ];

        // 2. Fetch Users with Joined MLM Details
        $usersQuery = DB::table('users as u')
            ->leftJoin('user_details as ud', 'u.id', '=', 'ud.userid')
            ->leftJoin('users as sp', 'ud.sponsorid', '=', 'sp.id')
            ->leftJoin('asset_details as ad', function($join) {
                $join->on('u.id', '=', 'ad.userid')
                     ->orOn('ud.id', '=', 'ad.userid');
            })
            ->select(
                'u.id',
                'u.uuid',
                'u.usersname as name',
                'u.email',
                'u.permission',
                'u.licence',
                'u.doj',
                'ud.id as user_detail_id',
                'ud.sponsorid',
                'sp.uuid as sponsor_uuid',
                'sp.usersname as sponsor_name',
                'ud.current_self_investment as self_investment',
                'ud.total_investment as team_investment',
                'ud.total_direct',
                'ud.active_direct',
                'ud.userstatus',
                'ad.usdtbep20addr',
                'ad.bep20addr'
            )
            ->orderBy('u.id', 'desc');

        $users = $usersQuery->get()->map(function ($u) {
            $wallet = $u->usdtbep20addr ?: ($u->bep20addr ?: '');
            return (object) [
                'id'              => $u->id,
                'uuid'            => $u->uuid ?: 'ID-' . $u->id,
                'name'            => $u->name ?: 'Member #' . $u->id,
                'email'           => $u->email ?: 'N/A',
                'permission'      => (int) $u->permission,
                'is_active'       => (int) $u->userstatus === 1,
                'status_label'    => (int) $u->userstatus === 1 ? 'Active' : 'Inactive',
                'sponsor_uuid'    => $u->sponsor_uuid ?: 'Root / Direct',
                'sponsor_name'    => $u->sponsor_name ?: 'System',
                'self_investment' => (float) $u->self_investment,
                'team_investment' => (float) $u->team_investment,
                'total_direct'    => (int) $u->total_direct,
                'active_direct'   => (int) $u->active_direct,
                'wallet'          => $wallet,
                'doj'             => $u->doj ? Carbon::parse($u->doj)->format('d M Y') : 'N/A',
            ];
        });

        // 3. User & Directs Summary Statistics
        $today = Carbon::today();
        $userSummary = [
            'totalUsers'     => $users->count(),
            'activeUsers'    => $users->where('is_active', true)->count(),
            'inactiveUsers'  => $users->where('is_active', false)->count(),
            'blockedUsers'   => $users->where('permission', 0)->count(),
            'totalDirects'   => $users->sum('total_direct'),
            'activeDirects'  => $users->sum('active_direct'),
            'totalSelfVol'   => $users->sum('self_investment'),
            'totalTeamVol'   => $users->sum('team_investment'),
        ];

        // 4. Staking Deposits List with Associated Member Data
        $rawDeposits = \App\StackingDeposite::orderBy('id', 'desc')->get();

        $stakingList = $rawDeposits->map(function ($deposit) {
            $userDetail = \App\UserDetails::where('id', $deposit->userid)->first();
            $user = null;
            $assetDetail = null;

            if ($userDetail) {
                $user = \App\User::where('id', $userDetail->userid)->first();
                $assetDetail = \App\AssetDetail::where('userid', $userDetail->id)
                    ->orWhere('userid', $userDetail->userid)
                    ->first();
            } else {
                $user = \App\User::where('id', $deposit->userid)->first();
                if ($user) {
                    $assetDetail = \App\AssetDetail::where('userid', $user->id)->first();
                }
            }

            $walletAddress = '';
            if ($assetDetail) {
                $walletAddress = $assetDetail->usdtbep20addr ?: ($assetDetail->bep20addr ?: '');
            }

            $displayName = 'Anonymous';
            if ($user) {
                $displayName = $user->usersname ?: ($user->email ?: ($user->uuid ?: 'Member #' . $deposit->userid));
            } elseif ($userDetail) {
                $displayName = 'User #' . $userDetail->id;
            }

            return (object) [
                'id'            => $deposit->id,
                'userid'        => $deposit->userid,
                'uuid'          => $user ? $user->uuid : ('ID-' . $deposit->userid),
                'name'          => $displayName,
                'email'         => $user ? $user->email : 'N/A',
                'wallet'        => $walletAddress,
                'usdt'          => (float) $deposit->usdt,
                'cai'           => (float) $deposit->cai,
                'multiplier'    => (float) ($deposit->istatus > 0 ? $deposit->istatus : 2.0),
                'status'        => (int) $deposit->status,
                'status_label'  => (int) $deposit->status === 1 ? 'Active' : 'Completed',
                'staketype'     => (int) $deposit->staketype,
                'created_at'    => $deposit->created_at ? Carbon::parse($deposit->created_at)->format('d M Y, H:i') : 'N/A',
            ];
        });

        // 5. Staking Summary
        $todayDeposits = $rawDeposits->filter(function ($d) use ($today) {
            return $d->created_at && Carbon::parse($d->created_at)->isToday();
        });

        $stakingSummary = [
            'totalCount'      => $stakingList->count(),
            'activeCount'     => $stakingList->where('status', 1)->count(),
            'completedCount'  => $stakingList->where('status', '!=', 1)->count(),
            'totalUsdt'       => $stakingList->sum('usdt'),
            'activeUsdt'      => $stakingList->where('status', 1)->sum('usdt'),
            'todayUsdt'       => $todayDeposits->sum('usdt'),
            'uniqueUsers'     => $stakingList->pluck('uuid')->unique()->filter()->count(),
            'topStake'        => $stakingList->max('usdt') ?: 0,
            'avgStake'        => $stakingList->count() > 0 ? round($stakingList->sum('usdt') / $stakingList->count(), 2) : 0,
        ];

        // 6. Build Initial Root Tree (Top sponsor ID 1 or top users)
        $rootUserRecord = DB::table('users as u')
            ->join('user_details as ud', 'u.id', '=', 'ud.userid')
            ->leftJoin('asset_details as ad', 'u.id', '=', 'ad.userid')
            ->where('u.id', 1)
            ->select(
                'u.id',
                'u.uuid',
                'u.usersname as name',
                'ud.current_self_investment as self_inv',
                'ud.total_investment as team_inv',
                'ud.total_direct',
                'ud.active_direct',
                'ud.userstatus',
                'ad.usdtbep20addr as wallet'
            )
            ->first();

        $initialTree = null;
        if ($rootUserRecord) {
            $initialTree = (array) $rootUserRecord;
            $initialTree['children'] = $this->buildTreeHierarchy($rootUserRecord->id, 1, 3);
        }

        return view('contract-admin.index', compact(
            'contracts',
            'users',
            'userSummary',
            'stakingList',
            'stakingSummary',
            'initialTree'
        ));
    }

    /**
     * AJAX Endpoint: Get Dynamic Multi-Level Genealogy Tree for any User UUID or ID
     */
    public function genealogy(Request $request)
    {
        
        if (!self::isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please login first.'], 401);
        }

        $search = trim($request->get('search', ''));
        $targetUser = null;

        if ($search) {
            $targetUser = DB::table('users as u')
                ->join('user_details as ud', 'u.id', '=', 'ud.userid')
                ->leftJoin('asset_details as ad', 'u.id', '=', 'ad.userid')
                ->where('u.uuid', $search)
                ->orWhere('u.id', $search)
                ->orWhere('u.email', $search)
                ->orWhere('ad.usdtbep20addr', $search)
                ->select(
                    'u.id',
                    'u.uuid',
                    'u.usersname as name',
                    'u.email',
                    'ud.current_self_investment as self_inv',
                    'ud.total_investment as team_inv',
                    'ud.total_direct',
                    'ud.active_direct',
                    'ud.userstatus',
                    'ad.usdtbep20addr as wallet'
                )
                ->first();
        } else {
            // Default to Root User ID 1
            $targetUser = DB::table('users as u')
                ->join('user_details as ud', 'u.id', '=', 'ud.userid')
                ->leftJoin('asset_details as ad', 'u.id', '=', 'ad.userid')
                ->where('u.id', 1)
                ->select(
                    'u.id',
                    'u.uuid',
                    'u.usersname as name',
                    'u.email',
                    'ud.current_self_investment as self_inv',
                    'ud.total_investment as team_inv',
                    'ud.total_direct',
                    'ud.active_direct',
                    'ud.userstatus',
                    'ad.usdtbep20addr as wallet'
                )
                ->first();
        }

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in system.'
            ], 404);
        }

        $tree = (array) $targetUser;
        $tree['children'] = $this->buildTreeHierarchy($targetUser->id, 1, 4);

        return response()->json([
            'success' => true,
            'data'    => $tree
        ]);
    }

    /**
     * Recursive Downline Tree Builder
     */
    private function buildTreeHierarchy($sponsorId, $depth = 1, $maxDepth = 4)
    {
        if ($depth > $maxDepth) {
            return [];
        }

        $children = DB::table('users as u')
            ->join('user_details as ud', 'u.id', '=', 'ud.userid')
            ->leftJoin('asset_details as ad', 'u.id', '=', 'ad.userid')
            ->where('ud.sponsorid', $sponsorId)
            ->select(
                'u.id',
                'u.uuid',
                'u.usersname as name',
                'u.email',
                'u.permission',
                'ud.current_self_investment as self_inv',
                'ud.total_investment as team_inv',
                'ud.total_direct',
                'ud.active_direct',
                'ud.userstatus',
                'ad.usdtbep20addr as wallet'
            )
            ->get();

        $res = [];
        foreach ($children as $c) {
            $node = (array) $c;
            $node['level'] = $depth;
            $node['children'] = $this->buildTreeHierarchy($c->id, $depth + 1, $maxDepth);
            $res[] = $node;
        }

        return $res;
    }

    /**
     * AJAX Endpoint: Toggle User Permission (Block / Unblock)
     */
    public function toggleUserStatus(Request $request)
    {
        if (!self::isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please login first.'], 401);
        }

        $userId = $request->input('userId');
        $user = \App\User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $newPermission = $user->permission == 1 ? 0 : 1;
        $user->permission = $newPermission;
        $user->save();

        return response()->json([
            'success' => true,
            'newPermission' => $newPermission,
            'statusLabel' => $newPermission == 1 ? 'Allowed' : 'Blocked',
            'message' => 'User status updated successfully.'
        ]);
    }

    /**
     * AJAX Endpoint: Get Single User Details & Staking History
     */
    public function getUserDetails($id)
    {
        if (!self::isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please login first.'], 401);
        }

        $user = DB::table('users as u')
            ->leftJoin('user_details as ud', 'u.id', '=', 'ud.userid')
            ->leftJoin('users as sp', 'ud.sponsorid', '=', 'sp.id')
            ->leftJoin('asset_details as ad', 'u.id', '=', 'ad.userid')
            ->where('u.id', $id)
            ->orWhere('u.uuid', $id)
            ->select(
                'u.id',
                'u.uuid',
                'u.usersname as name',
                'u.email',
                'u.permission',
                'u.doj',
                'sp.uuid as sponsor_uuid',
                'sp.usersname as sponsor_name',
                'ud.current_self_investment as self_inv',
                'ud.total_investment as team_inv',
                'ud.total_direct',
                'ud.active_direct',
                'ud.userstatus',
                'ad.usdtbep20addr as wallet'
            )
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $stakings = DB::table('stacking_deposites')
            ->where('userid', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $directs = DB::table('users as u')
            ->join('user_details as ud', 'u.id', '=', 'ud.userid')
            ->where('ud.sponsorid', $user->id)
            ->select('u.id', 'u.uuid', 'u.usersname as name', 'ud.current_self_investment as self_inv', 'ud.userstatus', 'u.doj')
            ->get();

        return response()->json([
            'success' => true,
            'user'    => $user,
            'stakings'=> $stakings,
            'directs' => $directs
        ]);
    }
}
