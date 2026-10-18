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

const CAI_ADDR = '0x193149028a3C83009Ecda6D5E76f1abD62E51a43';
const MINING_ADDR = '0x61e1f46EA4f6892edF72f172eAcBC82Fa61dfbb6';

const ERC20_ABI = [
    'function balanceOf(address) view returns (uint256)',
    'event Transfer(address indexed from, address indexed to, uint256 value)'
];

async function findHolders() {
    const cai = new ethers.Contract(CAI_ADDR, ERC20_ABI, provider);
    const filter = cai.filters.Transfer(ethers.ZeroAddress);
    const events = await cai.queryFilter(filter, 0, 'latest');
    
    console.log('Mint Events:');
    for (const ev of events) {
        console.log('Minted to:', ev.args.to, 'Amount:', ethers.formatEther(ev.args.value));
        const bal = await cai.balanceOf(ev.args.to);
        console.log('Current Balance of deployer:', ethers.formatEther(bal));
    }
}

findHolders().catch(console.error);
