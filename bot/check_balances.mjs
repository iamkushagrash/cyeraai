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
const MINING_ADDR = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';

const ERC20_ABI = [
    'function balanceOf(address) view returns (uint256)',
    'function decimals() view returns (uint8)'
];

async function check() {
    const pkey = process.env.BOT_PRIVATE_KEYS || process.env.BOT_PRIVATE_KEY || process.env.PRIVATE_KEY || '';
    const wallet = new ethers.Wallet(pkey.split(/[,;\n]+/)[0].trim(), provider);
    const usdt = new ethers.Contract(USDT_ADDR, ERC20_ABI, provider);
    const cai = new ethers.Contract(CAI_ADDR, ERC20_ABI, provider);

    const uDec = await usdt.decimals();
    const cDec = await cai.decimals();

    const wBnb = await provider.getBalance(wallet.address);
    const wUsdt = await usdt.balanceOf(wallet.address);
    const wCai = await cai.balanceOf(wallet.address);

    const mUsdt = await usdt.balanceOf(MINING_ADDR);
    const mCai = await cai.balanceOf(MINING_ADDR);

    console.log('--- WALLET:', wallet.address, '---');
    console.log('BNB:', ethers.formatEther(wBnb));
    console.log('USDT:', ethers.formatUnits(wUsdt, uDec));
    console.log('CAI:', ethers.formatUnits(wCai, cDec));

    console.log('\n--- MINING ENGINE CONTRACT:', MINING_ADDR, '---');
    console.log('USDT:', ethers.formatUnits(mUsdt, uDec));
    console.log('CAI:', ethers.formatUnits(mCai, cDec));
}

check().catch(console.error);
