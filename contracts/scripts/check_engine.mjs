import { ethers } from 'ethers';

const RPC = 'https://bsc-dataseed.binance.org/';
const provider = new ethers.JsonRpcProvider(RPC);

const MINING_ENGINE = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';
const CAI_TOKEN = '0x4756618F389A46819008Aff01ad0f91A38154eDB';
const USDT_TOKEN = '0x55d398326f99059fF775485246999027B3197955';
const PANCAKE_ROUTER = '0x10ED43C718714eb63d5aA57B78B54704E256024E';

const ENGINE_ABI = [
  'function backendSigner() view returns (address)',
  'function treasuryWallet() view returns (address)',
  'function DOMAIN_SEPARATOR() view returns (bytes32)',
  'function isNonceExecuted(address, uint256) view returns (bool)'
];

const ERC20_ABI = [
  'function balanceOf(address) view returns (uint256)',
  'function allowance(address, address) view returns (uint256)'
];

async function main() {
  console.log('--- CHECKING ON-CHAIN STATUS ---');
  const engine = new ethers.Contract(MINING_ENGINE, ENGINE_ABI, provider);
  const cai = new ethers.Contract(CAI_TOKEN, ERC20_ABI, provider);
  const usdt = new ethers.Contract(USDT_TOKEN, ERC20_ABI, provider);

  const signer = await engine.backendSigner();
  const treasury = await engine.treasuryWallet();
  const domain = await engine.DOMAIN_SEPARATOR();

  console.log('Contract Backend Signer:', signer);
  console.log('Contract Treasury:', treasury);

  const privateKey = '7672820670408540bfcd0c7d34794935e4a3054455fa5c7f9cccdfdf4aca45c3';
  const backendWallet = new ethers.Wallet(privateKey);
  console.log('Local Signer Wallet Address:', backendWallet.address);
  console.log('Signer Match?:', signer.toLowerCase() === backendWallet.address.toLowerCase());

  const engineCaiBal = await cai.balanceOf(MINING_ENGINE);
  console.log('Mining Engine CAI Balance:', ethers.formatEther(engineCaiBal), 'CAI');

  const engineUsdtBal = await usdt.balanceOf(MINING_ENGINE);
  console.log('Mining Engine USDT Balance:', ethers.formatEther(engineUsdtBal), 'USDT');

  const caiAllowance = await cai.allowance(MINING_ENGINE, PANCAKE_ROUTER);
  console.log('CAI Allowance to Router:', caiAllowance.toString());
}

main().catch(console.error);
