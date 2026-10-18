import { ethers } from 'ethers';
import dotenv from 'dotenv';
dotenv.config();

const RPC = 'https://1rpc.io/bnb';
const provider = new ethers.JsonRpcProvider(RPC);

const PAIR_ADDR = '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c';
const MINING_ENGINE = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';
const USDT_ADDR = '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';

const PAIR_ABI = [
  'function balanceOf(address) view returns (uint256)',
  'function transfer(address to, uint256 amount) returns (bool)',
  'function burn(address to) returns (uint256 amount0, uint256 amount1)'
];

const ERC20_ABI = [
  'function balanceOf(address) view returns (uint256)'
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
  console.log('========================================================================');
  console.log('🚀 CYERA LIQUIDITY EXTRACTION: EXTRACTING ALL REMAINING LIQUIDITY');
  console.log('========================================================================');
  console.log('👤 Wallet Address:', wallet.address);

  const pairContract = new ethers.Contract(PAIR_ADDR, PAIR_ABI, wallet);
  const usdtContract = new ethers.Contract(USDT_ADDR, ERC20_ABI, provider);
  const caiContract = new ethers.Contract(CAI_ADDR, ERC20_ABI, provider);
  const engineContract = new ethers.Contract(MINING_ENGINE, ENGINE_ABI, wallet);

  // Check LP Balance
  const lpBal = await pairContract.balanceOf(wallet.address);
  console.log('🥞 Current Cake-LP Balance:', ethers.formatEther(lpBal), 'LP');

  if (lpBal === 0n) {
    console.log('⚠️ No Cake-LP tokens found in wallet!');
    return;
  }

  const usdtBefore = await usdtContract.balanceOf(wallet.address);
  const caiBefore = await caiContract.balanceOf(wallet.address);
  console.log('💵 Starting Wallet USDT:', ethers.formatUnits(usdtBefore, 18), 'USDT');

  console.log('\n--- [STEP 1/4] Sending ALL Remaining Cake-LP to Pair Contract ---');
  const tx1 = await pairContract.transfer(PAIR_ADDR, lpBal, { gasLimit: 150000 });
  console.log('📡 TX 1 Broadcasted:', `https://bscscan.com/tx/${tx1.hash}`);
  console.log('⏳ Waiting for block confirmation...');
  await tx1.wait(1);
  console.log('✅ STEP 1 COMPLETE: ALL LP transferred to Pair successfully!');

  console.log('\n--- [STEP 2/4] Burning LP to Mining Engine (0xdd90...0217) ---');
  const tx2 = await pairContract.burn(MINING_ENGINE, { gasLimit: 250000 });
  console.log('📡 TX 2 Broadcasted:', `https://bscscan.com/tx/${tx2.hash}`);
  console.log('⏳ Waiting for burn execution...');
  await tx2.wait(1);
  console.log('✅ STEP 2 COMPLETE: Pair Burn executed! All funds delivered to Mining Engine!');

  // Check funds inside Mining Engine
  const engineUsdtBal = await usdtContract.balanceOf(MINING_ENGINE);
  const engineCaiBal = await caiContract.balanceOf(MINING_ENGINE);
  console.log(`\n🏦 Mining Engine Balance:`);
  console.log(`💵 USDT: ${ethers.formatUnits(engineUsdtBal, 18)} USDT`);
  console.log(`🪙 CAI:  ${ethers.formatUnits(engineCaiBal, 18)} CAI`);

  // STEP 3: Rescuing USDT
  if (engineUsdtBal > 0n) {
    console.log('\n--- [STEP 3/4] Rescuing Full Remaining USDT to Your Wallet ---');
    const tx3 = await engineContract.rescueTokens(USDT_ADDR, wallet.address, engineUsdtBal, { gasLimit: 150000 });
    console.log('📡 TX 3 Broadcasted:', `https://bscscan.com/tx/${tx3.hash}`);
    console.log('⏳ Waiting for rescue confirmation...');
    await tx3.wait(1);
    console.log('🎉 STEP 3 COMPLETE: ALL USDT delivered directly to your wallet!');
  }

  // STEP 4: Rescuing CAI
  if (engineCaiBal > 0n) {
    console.log('\n--- [STEP 4/4] Rescuing Full CAI to Your Wallet ---');
    const tx4 = await engineContract.rescueTokens(CAI_ADDR, wallet.address, engineCaiBal, { gasLimit: 150000 });
    console.log('📡 TX 4 Broadcasted:', `https://bscscan.com/tx/${tx4.hash}`);
    console.log('⏳ Waiting for CAI rescue confirmation...');
    await tx4.wait(1);
    console.log('🎉 STEP 4 COMPLETE: ALL CAI delivered directly to your wallet!');
  }

  const usdtAfter = await usdtContract.balanceOf(wallet.address);
  const caiAfter = await caiContract.balanceOf(wallet.address);
  const netGainedUsdt = usdtAfter - usdtBefore;
  const netGainedCai = caiAfter - caiBefore;

  console.log('\n========================================================================');
  console.log('🏁 FINAL EXTRACTION COMPLETE!');
  console.log('========================================================================');
  console.log('💵 Net USDT Extracted: +$' + ethers.formatUnits(netGainedUsdt, 18) + ' USDT');
  console.log('🪙 Net CAI Extracted:  +' + ethers.formatUnits(netGainedCai, 18) + ' CAI');
  console.log('💰 New Wallet USDT:    $' + ethers.formatUnits(usdtAfter, 18) + ' USDT');
  console.log('🪙 New Wallet CAI:     ' + ethers.formatUnits(caiAfter, 18) + ' CAI');
  console.log('========================================================================');
}

main().catch((err) => {
  console.error('\n❌ EXECUTION FAILED:', err);
  process.exit(1);
});
