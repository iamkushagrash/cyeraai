<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ContractAdminController extends Controller
{
    /**
     * Display the Unified White-Theme MLM & Smart Contract Governance Command Center.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
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
