import { ethers } from 'ethers';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config({ path: path.join(__dirname, '.env') });
dotenv.config({ path: path.join(__dirname, '..', '.env') });

const RPC_LIST = [
    process.env.BSC_RPC_URL || 'https://bsc-dataseed.binance.org/',
    'https://bsc-rpc.publicnode.com',
    'https://1rpc.io/bnb',
    'https://binance.llamarpc.com'
];

const USDT_ADDR = process.env.USDT_TOKEN_ADDRESS || '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = process.env.CAI_TOKEN_ADDRESS || '0x4756618F389A46819008Aff01ad0f91A38154eDB';
const ROUTER_ADDR = process.env.PANCAKE_ROUTER_ADDRESS || '0x10ED43C718714eb63d5aA57B78B54704E256024E';

const ERC20_ABI = [
    'function balanceOf(address) view returns (uint256)',
    'function decimals() view returns (uint8)',
    'function allowance(address owner, address spender) view returns (uint256)',
    'function approve(address spender, uint256 amount) returns (bool)'
];

const ROUTER_ABI = [
    'function swapExactTokensForTokensSupportingFeeOnTransferTokens(uint amountIn, uint amountOutMin, address[] calldata path, address to, uint deadline) external',
    'function getAmountsIn(uint amountOut, address[] calldata path) view returns (uint[] memory amounts)',
    'function getAmountsOut(uint amountIn, address[] calldata path) view returns (uint[] memory amounts)'
];

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

async function getProvider() {
    for (const rpc of RPC_LIST) {
        try {
            const p = new ethers.JsonRpcProvider(rpc);
            await p.getBlockNumber();
            return p;
        } catch (e) {}
    }
    return new ethers.JsonRpcProvider(RPC_LIST[0]);
}

async function run4Sells() {
    console.log('===============================================================');
    console.log('🔴 EXECUTING 4 CONSECUTIVE SELL ORDERS ($5.00 USDT EACH)');
    console.log('===============================================================\n');

    const provider = await getProvider();
    const pkey = (process.env.BOT_PRIVATE_KEYS || process.env.BOT_PRIVATE_KEY || process.env.PRIVATE_KEY || '').split(/[,;\n]+/)[0].trim();
    const wallet = new ethers.Wallet(pkey, provider);

    const usdt = new ethers.Contract(USDT_ADDR, ERC20_ABI, wallet);
    const cai = new ethers.Contract(CAI_ADDR, ERC20_ABI, wallet);
    const router = new ethers.Contract(ROUTER_ADDR, ROUTER_ABI, wallet);

    const uDec = await usdt.decimals();
    const cDec = await cai.decimals();

    const caiBal = await cai.balanceOf(wallet.address);
    const caiFormatted = parseFloat(ethers.formatUnits(caiBal, cDec));

    console.log(`🎯 Wallet: ${wallet.address}`);
    console.log(`🪙 CAI Balance: ${caiFormatted.toFixed(4)} CAI\n`);

    if (caiFormatted <= 0.001) {
        console.error('❌ ERROR: Wallet has 0.0000 CAI tokens!');
        console.error(`👉 Please send 3 to 5 CAI tokens from your deployer wallet to: ${wallet.address}`);
        console.error('👉 Once sent, run: node bot/sell_4_times.mjs\n');
        process.exit(1);
    }

    // Auto-approve CAI
    const allowance = await cai.allowance(wallet.address, ROUTER_ADDR);
    if (allowance < ethers.parseUnits('100', cDec)) {
        console.log('🔓 Approving CAI to PancakeSwap Router...');
        const txApp = await cai.approve(ROUTER_ADDR, ethers.MaxUint256);
        await txApp.wait(1);
        console.log('✅ CAI Approved!\n');
    }

    const TARGET_USDT_PER_SELL = 5.00;

    for (let i = 1; i <= 4; i++) {
        console.log(`\n--- [SELL ${i}/4] 🔴 Selling ~$${TARGET_USDT_PER_SELL} USDT worth of CAI ---`);
        
        let caiToSellWei = 0n;
        try {
            const sellPath = [CAI_ADDR, USDT_ADDR];
            const desiredUsdtWei = ethers.parseUnits(TARGET_USDT_PER_SELL.toString(), uDec);
            const amountsIn = await router.getAmountsIn(desiredUsdtWei, sellPath);
            caiToSellWei = amountsIn[0];
        } catch (e) {
            caiToSellWei = ethers.parseUnits('0.75', cDec);
        }

        const caiToSellFormatted = parseFloat(ethers.formatUnits(caiToSellWei, cDec));
        console.log(`   Amount to swap: ${caiToSellFormatted.toFixed(4)} CAI`);

        const currentBal = await cai.balanceOf(wallet.address);
        if (currentBal < caiToSellWei) {
            console.warn(`   ⚠️ Insufficient CAI for full trade (Available: ${ethers.formatUnits(currentBal, cDec)} CAI). Selling remaining balance...`);
            caiToSellWei = currentBal;
            if (caiToSellWei <= 0n) break;
        }

        const sellPath = [CAI_ADDR, USDT_ADDR];
        const deadline = Math.floor(Date.now() / 1000) + 300;

        console.log(`   📡 Broadcasting Sell #${i} to PancakeSwap...`);
        const tx = await router.swapExactTokensForTokensSupportingFeeOnTransferTokens(
            caiToSellWei,
            0,
            sellPath,
            wallet.address, // deliver USDT to wallet
            deadline,
            { gasLimit: 350000 }
        );

        console.log(`   ⏳ Tx Submitted: https://bscscan.com/tx/${tx.hash}`);
        const receipt = await tx.wait(1);

        if (receipt.status === 1) {
            console.log(`   🎉 SUCCESS! Sell #${i} confirmed on Block #${receipt.blockNumber} 🔴`);
        } else {
            console.log(`   ❌ Sell #${i} reverted.`);
        }

        if (i < 4) {
            console.log(`   ⏳ Waiting 10 seconds before next sell...`);
            await sleep(10000);
        }
    }

    const finalUsdt = await usdt.balanceOf(wallet.address);
    console.log(`\n===============================================================`);
    console.log(`✅ ALL 4 SELLS COMPLETED! Final Wallet USDT Balance: $${ethers.formatUnits(finalUsdt, uDec)} USDT`);
    console.log(`===============================================================\n`);
}

run4Sells().catch(console.error);
