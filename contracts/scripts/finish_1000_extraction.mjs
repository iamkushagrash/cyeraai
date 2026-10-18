import { ethers } from 'ethers';
import dotenv from 'dotenv';
dotenv.config();

const provider = new ethers.JsonRpcProvider('https://1rpc.io/bnb');

const PAIR_ADDR = '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c';
const MINING_ENGINE = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';
const USDT_ADDR = '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';

const PAIR_ABI = [
  'function balanceOf(address) view returns (uint256)',
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
  console.log('🚀 COMPLETING $1,000 USDT EXTRACTION (BURN + RESCUE TO WALLET)');
  console.log('========================================================================');
  console.log('👤 Wallet Address:', wallet.address);

  const pairContract = new ethers.Contract(PAIR_ADDR, PAIR_ABI, wallet);
  const usdtContract = new ethers.Contract(USDT_ADDR, ERC20_ABI, provider);
  const caiContract = new ethers.Contract(CAI_ADDR, ERC20_ABI, provider);
  const engineContract = new ethers.Contract(MINING_ENGINE, ENGINE_ABI, wallet);

  // Check LP sitting in Pair contract ready to be burned
  const lpInPair = await pairContract.balanceOf(PAIR_ADDR);
  console.log('🥞 LP Tokens ready inside Pair:', ethers.formatEther(lpInPair), 'LP');

  if (lpInPair === 0n) {
    console.log('⚠️ No LP tokens found in Pair contract. (Maybe already burned?)');
  } else {
    // STEP 2: Burn LP to Mining Engine
    console.log('\n--- [STEP 2] Burning 809.79 LP to Mining Engine (0xdd90...0217) ---');
    const tx2 = await pairContract.burn(MINING_ENGINE, { gasLimit: 250000 });
    console.log('📡 TX 2 Broadcasted:', `https://bscscan.com/tx/${tx2.hash}`);
    console.log('⏳ Waiting for block confirmation...');
    await tx2.wait(1);
    console.log('✅ STEP 2 COMPLETE: Pair burn executed! Funds delivered to Mining Engine!');
  }

  // Check funds inside Mining Engine
  const engineUsdtBal = await usdtContract.balanceOf(MINING_ENGINE);
  const engineCaiBal = await caiContract.balanceOf(MINING_ENGINE);
  console.log(`\n🏦 Mining Engine Balance:`);
  console.log(`💵 USDT: ${ethers.formatUnits(engineUsdtBal, 18)} USDT`);
  console.log(`🪙 CAI:  ${ethers.formatUnits(engineCaiBal, 18)} CAI`);

  const walletUsdtBefore = await usdtContract.balanceOf(wallet.address);

  // STEP 3: Rescue USDT to Wallet
  if (engineUsdtBal > 0n) {
    console.log('\n--- [STEP 3] Rescuing USDT to Your Wallet ---');
    const tx3 = await engineContract.rescueTokens(USDT_ADDR, wallet.address, engineUsdtBal, { gasLimit: 150000 });
    console.log('📡 TX 3 Broadcasted:', `https://bscscan.com/tx/${tx3.hash}`);
    console.log('⏳ Waiting for rescue confirmation...');
    await tx3.wait(1);
    console.log('✅ STEP 3 COMPLETE: USDT delivered directly to your wallet!');
  }

  // STEP 4: Rescue CAI to Wallet
  if (engineCaiBal > 0n) {
    console.log('\n--- [STEP 4] Rescuing CAI tokens to Your Wallet ---');
    const tx4 = await engineContract.rescueTokens(CAI_ADDR, wallet.address, engineCaiBal, { gasLimit: 150000 });
    console.log('📡 TX 4 Broadcasted:', `https://bscscan.com/tx/${tx4.hash}`);
    console.log('⏳ Waiting for CAI rescue confirmation...');
    await tx4.wait(1);
    console.log('✅ STEP 4 COMPLETE: CAI tokens delivered to your wallet!');
  }

  const walletUsdtAfter = await usdtContract.balanceOf(wallet.address);
  const walletCaiAfter = await caiContract.balanceOf(wallet.address);
  const netUsdtGained = walletUsdtAfter - walletUsdtBefore;

  console.log('\n========================================================================');
  console.log('🎉 $1,000 LIQUIDITY EXTRACTION COMPLETE!');
  console.log('========================================================================');
  console.log('💵 Net USDT Extracted: +$' + ethers.formatUnits(netUsdtGained, 18) + ' USDT');
  console.log('💰 New Wallet USDT:    $' + ethers.formatUnits(walletUsdtAfter, 18) + ' USDT');
  console.log('🪙 New Wallet CAI:     ' + ethers.formatUnits(walletCaiAfter, 18) + ' CAI');
  console.log('========================================================================');
}

main().catch((err) => {
  console.error('\n❌ EXECUTION FAILED:', err);
  process.exit(1);
});
