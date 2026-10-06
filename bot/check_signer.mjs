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

const MINING_ENGINE = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';

const ENGINE_ABI = [
  'function backendSigner() view returns (address)',
  'function DOMAIN_SEPARATOR() view returns (bytes32)',
  'function isNonceExecuted(address, uint256) view returns (bool)'
];

async function checkSigner() {
  const engine = new ethers.Contract(MINING_ENGINE, ENGINE_ABI, provider);
  const signer = await engine.backendSigner();
  console.log('Contract Backend Signer on-chain:', signer);

  const envKey = process.env.BACKEND_SIGNER_KEY || process.env.BACKEND_SIGNER_PRIVATE_KEY || '7672820670408540bfcd0c7d34794935e4a3054455fa5c7f9cccdfdf4aca45c3';
  const backendWallet = new ethers.Wallet(envKey);
  console.log('Local Signer Wallet Address:', backendWallet.address);
  console.log('Match?:', signer.toLowerCase() === backendWallet.address.toLowerCase());
}

checkSigner().catch(console.error);
