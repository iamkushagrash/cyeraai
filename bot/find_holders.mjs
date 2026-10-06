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

const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';
const MINING_ADDR = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';

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
