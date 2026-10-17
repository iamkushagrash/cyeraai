<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ContractAdminController extends Controller
{
    /**
     * Display the Decentralized Smart Contract Owner Governance Portal.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contracts = [
            'adminOwnerAddress'         => env('ADMIN_OWNER_ADDRESS', '0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7'),
            'caiTreasuryWallet'         => env('CAI_TREASURY_WALLET', '0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7'),
            'liquidityTreasuryWallet'   => env('LIQUIDITY_TREASURY_WALLET', '0x6A4139D4544Fd175888E5ca502f35B4Da0265a64'),
            'backendSignerAddress'      => env('BACKEND_SIGNER_ADDRESS', '0x188525c9810749a8012Dd1eBfA4a28801bac0a6e'),
            
            'usdtTokenAddress'          => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'caiTokenAddress'           => env('CAI_TOKEN_ADDRESS', '0x4756618F389A46819008Aff01ad0f91A38154eDB'),
            'pancakePairAddress'        => env('PANCAKESWAP_PAIR_ADDRESS', '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c'),
            'pancakeRouterAddress'      => env('PANCAKE_ROUTER_ADDRESS', '0x10ED43C718714eb63d5aA57B78B54704E256024E'),
            'miningEngineAddress'       => env('CYERA_MINING_ENGINE_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217'),
            'investmentSplitterAddress' => env('CYERA_MINING_ENGINE_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217'),
            'treasurySplitterAddress'   => env('TREASURY_SPLITTER_ADDRESS', '0xcC3902345ad939df1C072E5D7fFD12d3d84c8Fc5'),
            'secondaryTreasuryWallet'   => env('SECONDARY_TREASURY_WALLET', '0x629FF4ccc833d7F11AEcE0C02945B67dfa5cfFf2'),
            'usdtWithdrawalVaultAddress' => env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x0D1Cf84DcB6Ad9dF2d2f7a5998C441569e87684b'),
            'treasuryClaimVaultAddress' => env('TREASURY_CLAIM_VAULT_ADDRESS', '0x6893000Cc1f77A82fFe129C8089014752492A5F0'),
            'caiRewardClaimVaultAddress' => env('CAI_REWARD_CLAIM_VAULT_ADDRESS', '0x76Ea835c1D1ae5670D5287c41894C599252dEA6e'),
            
            'bscMainnetRpc'             => env('BSC_MAINNET_RPC', 'https://bsc-dataseed.binance.org/'),
            'bscTestnetRpc'             => env('BSC_TESTNET_RPC', 'https://data-seed-prebsc-1-s1.binance.org:8545/'),
            'chainId'                   => 56, // BSC Mainnet default
        ];

        // Fetch All Staking Deposits with User Details, Name, UUID & Wallet Address
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
                // Direct fallback if userid points directly to users table
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
                'multiplier'    => (float) ($deposit->istatus > 0 ? $deposit->istatus : 2.0),
                'status'        => (int) $deposit->status,
                'status_label'  => (int) $deposit->status === 1 ? 'Active' : 'Completed',
                'staketype'     => (int) $deposit->staketype,
                'is_dao'        => $userDetail ? (int) $userDetail->is_dao : 0,
                'created_at'    => $deposit->created_at,
            ];
        });

        // Summary Statistics
        $stakingSummary = [
            'totalCount'      => $stakingList->count(),
            'activeCount'     => $stakingList->where('status', 1)->count(),
            'completedCount'  => $stakingList->where('status', '!=', 1)->count(),
            'totalUsdt'       => $stakingList->sum('usdt'),
            'activeUsdt'      => $stakingList->where('status', 1)->sum('usdt'),
            'uniqueUsers'     => $stakingList->pluck('uuid')->unique()->filter()->count(),
            'topStake'        => $stakingList->max('usdt') ?: 0,
            'avgStake'        => $stakingList->count() > 0 ? ($stakingList->sum('usdt') / $stakingList->count()) : 0,
        ];

        return view('contract-admin.index', compact('contracts', 'stakingList', 'stakingSummary'));
    }
}
