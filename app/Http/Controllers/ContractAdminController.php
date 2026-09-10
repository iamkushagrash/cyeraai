<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'adminOwnerAddress' => env('ADMIN_OWNER_ADDRESS', '0x07Bd1494C669a69C89e4566436c3fC629Fd9045E'),
            'caiTreasuryWallet' => env('CAI_TREASURY_WALLET', '0x07Bd1494C669a69C89e4566436c3fC629Fd9045E'),
            'liquidityTreasuryWallet' => env('LIQUIDITY_TREASURY_WALLET', '0x6A4139D4544Fd175888E5ca502f35B4Da0265a64'),
            'backendSignerAddress' => env('BACKEND_SIGNER_ADDRESS', '0x6A3DE8Ab8Ee9bb3899066A9678B89F3735A2BBBD'),
            
            'usdtTokenAddress' => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'caiTokenAddress' => env('CAI_TOKEN_ADDRESS', '0x5cb5452DE7044E551137985cE1d7C2D42e7Baf5f'),
            'treasuryClaimVaultAddress' => env('TREASURY_CLAIM_VAULT_ADDRESS', '0x6893000Cc1f77A82fFe129C8089014752492A5F0'),
            'investmentSplitterAddress' => env('INVESTMENT_SPLITTER_ADDRESS', '0x2A1CEBf5Afe686763E915838457ccBC344901ebD'),
            'caiRewardClaimVaultAddress' => env('CAI_REWARD_CLAIM_VAULT_ADDRESS', '0x76Ea835c1D1ae5670D5287c41894C599252dEA6e'),
            'usdtWithdrawalVaultAddress' => env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x97deeA367462eCB14B81B32014315BCd58be3a1D'),
            
            'bscMainnetRpc' => env('BSC_MAINNET_RPC', 'https://bsc-dataseed.binance.org/'),
            'bscTestnetRpc' => env('BSC_TESTNET_RPC', 'https://data-seed-prebsc-1-s1.binance.org:8545/'),
            'chainId' => 56, // BSC Mainnet default
        ];

        return view('contract-admin.index', compact('contracts'));
    }
}
