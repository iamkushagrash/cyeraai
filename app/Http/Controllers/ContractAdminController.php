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
            'usdtWithdrawalVaultAddress' => env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x0D1Cf84DcB6Ad9dF2d2f7a5998C441569e87684b'),
            'treasuryClaimVaultAddress' => env('TREASURY_CLAIM_VAULT_ADDRESS', '0x6893000Cc1f77A82fFe129C8089014752492A5F0'),
            'caiRewardClaimVaultAddress' => env('CAI_REWARD_CLAIM_VAULT_ADDRESS', '0x76Ea835c1D1ae5670D5287c41894C599252dEA6e'),
            
            'bscMainnetRpc'             => env('BSC_MAINNET_RPC', 'https://bsc-dataseed.binance.org/'),
            'bscTestnetRpc'             => env('BSC_TESTNET_RPC', 'https://data-seed-prebsc-1-s1.binance.org:8545/'),
            'chainId'                   => 56, // BSC Mainnet default
        ];

        return view('contract-admin.index', compact('contracts'));
    }
}
