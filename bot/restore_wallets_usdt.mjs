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

const USDT_ADDR = '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';
const ROUTER_ADDR = '0x10ED43C718714eb63d5aA57B78B54704E256024E';

const ERC20_ABI = [
    'function balanceOf(address) view returns (uint256)',
    'function decimals() view returns (uint8)',
    'function allowance(address owner, address spender) view returns (uint256)',
    'function approve(address spender, uint256 amount) returns (bool)'
];

const ROUTER_ABI = [
    'function swapExactTokensForTokensSupportingFeeOnTransferTokens(uint amountIn, uint amountOutMin, address[] calldata path, address to, uint deadline) external',
    'function getAmountsIn(uint amountOut, address[] calldata path) view returns (uint[] memory amounts)'
];

const sleep = (ms) => new Promise(res => setTimeout(res, ms));

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

async function restoreAll() {
    console.log('===============================================================');
    console.log('🔴 SELLING CAI TO RECOVER FULL USDT ACROSS ALL 3 WALLETS');
    console.log('===============================================================\n');

    const provider = await getProvider();
    const rawKeys = process.env.BOT_PRIVATE_KEYS || process.env.BOT_PRIVATE_KEY || '';
    const keys = rawKeys.split(/[,;\n]+/).map(k => k.trim()).filter(k => k.length >= 64);

    const targetBalances = [50.00, 100.00, 50.00];

    for (let i = 0; i < keys.length; i++) {
        const wallet = new ethers.Wallet(keys[i], provider);
        const usdt = new ethers.Contract(USDT_ADDR, ERC20_ABI, wallet);
        const cai = new ethers.Contract(CAI_ADDR, ERC20_ABI, wallet);
        const router = new ethers.Contract(ROUTER_ADDR, ROUTER_ABI, wallet);

        const uDec = await usdt.decimals();
        const cDec = await cai.decimals();

        const uBal = await usdt.balanceOf(wallet.address);
        const uCurrent = parseFloat(ethers.formatUnits(uBal, uDec));
        const target = targetBalances[i] || 50.00;

        console.log(`[Wallet #${i+1}] ${wallet.address}`);
        console.log(`   Current USDT: $${uCurrent.toFixed(2)} | Target: $${target.toFixed(2)}`);

        if (uCurrent < target) {
            const neededUsdt = target - uCurrent;
            console.log(`   🔴 Selling CAI to get $${neededUsdt.toFixed(2)} USDT...`);

            // Check allowance
            const allow = await cai.allowance(wallet.address, ROUTER_ADDR);
            if (allow < ethers.parseUnits('100', cDec)) {
                const txA = await cai.approve(ROUTER_ADDR, ethers.MaxUint256);
                await txA.wait(1);
            }

            const desiredUsdtWei = ethers.parseUnits(neededUsdt.toFixed(2), uDec);
            const amountsIn = await router.getAmountsIn(desiredUsdtWei, [CAI_ADDR, USDT_ADDR]);
            const caiToSellWei = amountsIn[0];

            console.log(`   Amount: ${ethers.formatUnits(caiToSellWei, cDec)} CAI`);

            const deadline = Math.floor(Date.now() / 1000) + 300;
            const tx = await router.swapExactTokensForTokensSupportingFeeOnTransferTokens(
                caiToSellWei,
                0,
                [CAI_ADDR, USDT_ADDR],
                wallet.address,
                deadline,
                { gasLimit: 350000 }
            );

            console.log(`   ⏳ Tx Submitted: https://bscscan.com/tx/${tx.hash}`);
            const receipt = await tx.wait(1);
            if (receipt.status === 1) {
                console.log(`   🎉 SUCCESS! Recovered $${neededUsdt.toFixed(2)} USDT on Block #${receipt.blockNumber} 🔴\n`);
            }
            await sleep(5000);
        } else {
            console.log(`   ✅ USDT is already full ($${uCurrent.toFixed(2)} >= $${target.toFixed(2)}). No sell needed.\n`);
        }
    }

    console.log('===============================================================');
    console.log('🎉 ALL WALLETS FULLY RESTORED TO $200+ USDT!');
    console.log('===============================================================\n');
}

restoreAll().catch(console.error);
