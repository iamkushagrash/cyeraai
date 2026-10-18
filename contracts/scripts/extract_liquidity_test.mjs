import { ethers } from 'ethers';
import dotenv from 'dotenv';
dotenv.config();

const RPC = process.env.BSC_MAINNET_RPC || 'https://bsc-dataseed.binance.org/';
const provider = new ethers.JsonRpcProvider(RPC);

const PAIR_ADDR = '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c';
const MINING_ENGINE = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';
const USDT_ADDR = '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';

const PAIR_ABI = [
  'function balanceOf(address) view returns (uint256)',
  'function transfer(address to, uint256 amount) returns (bool)',
  'function burn(address to) returns (uint256 amount0, uint256 amount1)',
  'function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)'
];

const ERC20_ABI = [
  'function balanceOf(address) view returns (uint256)',
  'function decimals() view returns (uint8)'
];

const ENGINE_ABI = [
  'function owner() view returns (address)',
  'function rescueTokens(address token, address to, uint256 amount) external'
];

async function main() {
  let pk = process.env.privatekeyowner || process.env.PRIVATE_KEY || process.env.OWNER_PRIVATE_KEY || process.env.LUCKYDRAW_PRIVATE_KEY;
  if (!pk) {
    console.error('❌ Error: PRIVATE_KEY or privatekeyowner not found in .env file!');
    process.exit(1);
  }
  pk = pk.trim();
  if (!pk.startsWith('0x')) pk = '0x' + pk;

  const wallet = new ethers.Wallet(pk, provider);
  console.log('========================================================');
  console.log('🚀 CYERA LIQUIDITY EXTRACTION PROTOCOL (TEST RUN: ~100 USDT)');
  console.log('========================================================');
  console.log('👤 Wallet Address:', wallet.address);

  const bnbBal = await provider.getBalance(wallet.address);
  console.log('⛽ BNB Balance:', ethers.formatEther(bnbBal), 'BNB');
  if (bnbBal === 0n) {
    console.error('❌ Insufficient BNB for gas fees!');
    process.exit(1);
  }

  const pairContract = new ethers.Contract(PAIR_ADDR, PAIR_ABI, wallet);
  const usdtContract = new ethers.Contract(USDT_ADDR, ERC20_ABI, provider);
  const engineContract = new ethers.Contract(MINING_ENGINE, ENGINE_ABI, wallet);

  // Check Engine Owner
  const engineOwner = await engineContract.owner();
  console.log('🏛️ Mining Engine Owner:', engineOwner);
  if (engineOwner.toLowerCase() !== wallet.address.toLowerCase()) {
    console.error('❌ Error: Connected wallet is NOT the Mining Engine Owner!');
    process.exit(1);
  }

  // Check LP Balance
  const lpBal = await pairContract.balanceOf(wallet.address);
  console.log('🥞 Current Cake-LP Balance:', ethers.formatEther(lpBal), 'LP');

  const usdtBefore = await usdtContract.balanceOf(wallet.address);
  console.log('💵 Starting Wallet USDT:', ethers.formatUnits(usdtBefore, 18), 'USDT');

  // Test amount: 80 LP tokens (approx ~$100.8 USDT)
  const testLpAmount = ethers.parseEther('80');
  if (lpBal < testLpAmount) {
    console.error('❌ Insufficient Cake-LP balance for 80 LP test!');
    process.exit(1);
  }

  console.log('\n--- STEP 1: Sending 80 Cake-LP to Pair Contract ---');
  const tx1 = await pairContract.transfer(PAIR_ADDR, testLpAmount);
  console.log('📡 TX Hash:', tx1.hash);
  console.log('⏳ Waiting for block confirmation...');
  await tx1.wait();
  console.log('✅ 80 LP transferred to Pair successfully!');

  console.log('\n--- STEP 2: Burning LP to Mining Engine (0xdd90...0217) ---');
  // Call burn on Pair with to = MINING_ENGINE (passes CAIToken _update check!)
  const tx2 = await pairContract.burn(MINING_ENGINE);
  console.log('📡 TX Hash:', tx2.hash);
  console.log('⏳ Waiting for burn execution...');
  const rc2 = await tx2.wait();
  console.log('✅ Pair Burn executed! Funds delivered to Mining Engine!');

  // Check how much USDT reached Mining Engine
  const engineUsdtBal = await usdtContract.balanceOf(MINING_ENGINE);
  console.log('🏦 Mining Engine USDT Balance:', ethers.formatUnits(engineUsdtBal, 18), 'USDT');

  console.log('\n--- STEP 3: Rescuing USDT to Your Wallet ---');
  const tx3 = await engineContract.rescueTokens(USDT_ADDR, wallet.address, engineUsdtBal);
  console.log('📡 TX Hash:', tx3.hash);
  console.log('⏳ Waiting for rescue confirmation...');
  await tx3.wait();
  console.log('🎉 RESCUE COMPLETE! USDT delivered directly to your wallet!');

  const usdtAfter = await usdtContract.balanceOf(wallet.address);
  const netGained = usdtAfter - usdtBefore;
  console.log('\n========================================================');
  console.log('🏁 TEST RESULTS:');
  console.log('💵 New Wallet USDT Balance:', ethers.formatUnits(usdtAfter, 18), 'USDT');
  console.log('💰 Net USDT Extracted:', ethers.formatUnits(netGained, 18), 'USDT');
  console.log('========================================================');
}

main().catch((err) => {
  console.error('\n❌ EXECUTION FAILED:', err);
  process.exit(1);
});
