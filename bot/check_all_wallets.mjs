import { ethers } from 'ethers';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config({ path: path.join(__dirname, '.env') });
dotenv.config({ path: path.join(__dirname, '..', '.env') });

const RPC = process.env.BSC_RPC_URL || 'https://bsc-dataseed.binance.org/';
const provider = new ethers.JsonRpcProvider(RPC);

const USDT_ADDR = '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';

const ERC20_ABI = [
    'function balanceOf(address) view returns (uint256)',
    'function decimals() view returns (uint8)'
];

async function checkAll() {
    const rawKeys = process.env.BOT_PRIVATE_KEYS || process.env.BOT_PRIVATE_KEY || '';
    const keys = rawKeys.split(/[,;\n]+/).map(k => k.trim()).filter(k => k.length >= 64);
    
    const usdt = new ethers.Contract(USDT_ADDR, ERC20_ABI, provider);
    const cai = new ethers.Contract(CAI_ADDR, ERC20_ABI, provider);

    const uDec = await usdt.decimals();
    const cDec = await cai.decimals();

    console.log(`=== CHECKING ALL ${keys.length} WALLETS ===\n`);

    let totalUsdt = 0;
    let totalCai = 0;

    for (let i = 0; i < keys.length; i++) {
        const wallet = new ethers.Wallet(keys[i], provider);
        const bnb = await provider.getBalance(wallet.address);
        const uBal = await usdt.balanceOf(wallet.address);
        const cBal = await cai.balanceOf(wallet.address);

        const uF = parseFloat(ethers.formatUnits(uBal, uDec));
        const cF = parseFloat(ethers.formatUnits(cBal, cDec));

        totalUsdt += uF;
        totalCai += cF;

        console.log(`[Wallet #${i+1}] ${wallet.address}`);
        console.log(`   BNB: ${parseFloat(ethers.formatEther(bnb)).toFixed(5)} | USDT: $${uF.toFixed(2)} | CAI: ${cF.toFixed(4)}\n`);
    }

    console.log(`-----------------------------------------------`);
    console.log(`Total USDT across wallets: $${totalUsdt.toFixed(2)} USDT`);
    console.log(`Total CAI across wallets:  ${totalCai.toFixed(4)} CAI\n`);
}

checkAll().catch(console.error);
