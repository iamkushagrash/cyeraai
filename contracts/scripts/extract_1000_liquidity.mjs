import { ethers } from 'ethers';
import dotenv from 'dotenv';
dotenv.config();

const RPC_LIST = [
  'https://binance.llamarpc.com',
  'https://bsc-rpc.publicnode.com',
  'https://1rpc.io/bnb',
  process.env.BSC_MAINNET_RPC || 'https://bsc-dataseed.binance.org/'
];

async function getWorkingProvider() {
  for (const rpc of RPC_LIST) {
    try {
      const p = new ethers.JsonRpcProvider(rpc);
      await p.getBlockNumber();
      return p;
    } catch (e) {
      // try next
    }
  }
  return new ethers.JsonRpcProvider(RPC_LIST[0]);
}

const PAIR_ADDR = '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c';
const MINING_ENGINE = '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';
const USDT_ADDR = '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = '0x4756618F389A46819008Aff01ad0f91A38154eDB';

const PAIR_ABI = [
  'function totalSupply() view returns (uint256)',
  'function balanceOf(address) view returns (uint256)',
  'function transfer(address to, uint256 amount) returns (bool)',
  'function burn(address to) returns (uint256 amount0, uint256 amount1)'
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
  const provider = await getWorkingProvider();

  let pk = process.env.privatekeyowner || process.env.PRIVATE_KEY || process.env.OWNER_PRIVATE_KEY || process.env.LUCKYDRAW_PRIVATE_KEY;
  if (!pk) {
    console.error('❌ Error: PRIVATE_KEY or privatekeyowner not found in .env file!');
    process.exit(1);
  }
  pk = pk.trim();
  if (!pk.startsWith('0x')) pk = '0x' + pk;

  const wallet = new ethers.Wallet(pk, provider);
  console.log('========================================================================');
  console.log('🚀 CYERA LIQUIDITY EXTRACTION PROTOCOL: TARGET ~$1,000 USDT');
  console.log('========================================================================');
  console.log('👤 Wallet Address:', wallet.address);

  const bnbBal = await provider.getBalance(wallet.address);
  console.log('⛽ BNB Gas Balance:', ethers.formatEther(bnbBal), 'BNB');
  if (bnbBal < ethers.parseEther('0.001')) {
    console.error('❌ Insufficient BNB for gas fees (Minimum 0.001 BNB required)!');
    process.exit(1);
  }

  const pairContract = new ethers.Contract(PAIR_ADDR, PAIR_ABI, wallet);
  const usdtContract = new ethers.Contract(USDT_ADDR, ERC20_ABI, provider);
  const caiContract = new ethers.Contract(CAI_ADDR, ERC20_ABI, provider);
  const engineContract = new ethers.Contract(MINING_ENGINE, ENGINE_ABI, wallet);

  // 1. Verify Engine Owner
  const engineOwner = await engineContract.owner();
  console.log('🏛️ Mining Engine Owner:', engineOwner);
  if (engineOwner.toLowerCase() !== wallet.address.toLowerCase()) {
    console.error('❌ Connected wallet is NOT the Mining Engine Owner! Aborting.');
    process.exit(1);
  }

  // 2. Fetch Live Reserves & Balances
  const walletLpBal = await pairContract.balanceOf(wallet.address);
  const totSupply = await pairContract.totalSupply();
  const pairUsdtBal = await usdtContract.balanceOf(PAIR_ADDR);
  const pairCaiBal = await caiContract.balanceOf(PAIR_ADDR);
  const walletUsdtBefore = await usdtContract.balanceOf(wallet.address);
  const walletCaiBefore = await caiContract.balanceOf(wallet.address);

  console.log('\n📊 LIVE POOL STATUS:');
  console.log('🥞 Total LP Supply:  ', ethers.formatEther(totSupply), 'LP');
  console.log('🥞 Your LP Balance:  ', ethers.formatEther(walletLpBal), 'LP');
  console.log('💵 Pair USDT Reserve:', ethers.formatUnits(pairUsdtBal, 18), 'USDT');
  console.log('🪙 Pair CAI Reserve: ', ethers.formatUnits(pairCaiBal, 18), 'CAI');
  console.log('💰 Wallet Start USDT:', ethers.formatUnits(walletUsdtBefore, 18), 'USDT');

  // 3. Calculate LP needed for $1000 USDT
  const targetUsdtWei = ethers.parseUnits('1000', 18);
  const lpToBurnWei = (targetUsdtWei * totSupply) / pairUsdtBal;

  console.log('\n🎯 EXTRACTION PARAMETERS:');
  console.log('🎯 Target USDT:       $1,000.00 USDT');
  console.log('🔥 LP to be burned:  ', ethers.formatEther(lpToBurnWei), 'LP');

  if (walletLpBal < lpToBurnWei) {
    console.error('❌ Error: Insufficient LP balance to burn!');
    process.exit(1);
  }

  // STEP 1: Send calculated LP to Pair
  console.log('\n--- [STEP 1/4] Sending LP Tokens to Pair Contract ---');
  const tx1 = await pairContract.transfer(PAIR_ADDR, lpToBurnWei);
  console.log('📡 TX 1 Broadcasted:', `https://bscscan.com/tx/${tx1.hash}`);
  console.log('⏳ Waiting for confirmation...');
  await tx1.wait(1);
  console.log('✅ STEP 1 COMPLETE: LP transferred to Pair!');

  // STEP 2: Burn LP to Mining Engine
  console.log('\n--- [STEP 2/4] Burning LP to Mining Engine (Whitelisted Recipient) ---');
  const tx2 = await pairContract.burn(MINING_ENGINE);
  console.log('📡 TX 2 Broadcasted:', `https://bscscan.com/tx/${tx2.hash}`);
  console.log('⏳ Waiting for burn execution...');
  await tx2.wait(1);
  console.log('✅ STEP 2 COMPLETE: Pair burn executed! Funds delivered to Mining Engine!');

  // Check Engine Balances
  const engineUsdtBal = await usdtContract.balanceOf(MINING_ENGINE);
  const engineCaiBal = await caiContract.balanceOf(MINING_ENGINE);
  console.log(`🏦 Mining Engine now holds: ${ethers.formatUnits(engineUsdtBal, 18)} USDT & ${ethers.formatUnits(engineCaiBal, 18)} CAI`);

  // STEP 3: Rescue USDT to Wallet
  if (engineUsdtBal > 0n) {
    console.log('\n--- [STEP 3/4] Rescuing USDT from Mining Engine to Your Wallet ---');
    const tx3 = await engineContract.rescueTokens(USDT_ADDR, wallet.address, engineUsdtBal);
    console.log('📡 TX 3 Broadcasted:', `https://bscscan.com/tx/${tx3.hash}`);
    console.log('⏳ Waiting for rescue confirmation...');
    await tx3.wait(1);
    console.log('✅ STEP 3 COMPLETE: USDT delivered directly to your wallet!');
  }

  // STEP 4: Rescue CAI to Wallet
  if (engineCaiBal > 0n) {
    console.log('\n--- [STEP 4/4] Rescuing CAI tokens to Your Wallet ---');
    const tx4 = await engineContract.rescueTokens(CAI_ADDR, wallet.address, engineCaiBal);
    console.log('📡 TX 4 Broadcasted:', `https://bscscan.com/tx/${tx4.hash}`);
    console.log('⏳ Waiting for CAI rescue confirmation...');
    await tx4.wait(1);
    console.log('✅ STEP 4 COMPLETE: CAI tokens delivered to your wallet!');
  }

  // 4. Final Summary
  const walletUsdtAfter = await usdtContract.balanceOf(wallet.address);
  const walletCaiAfter = await caiContract.balanceOf(wallet.address);
  const netUsdtGained = walletUsdtAfter - walletUsdtBefore;
  const netCaiGained = walletCaiAfter - walletCaiBefore;
  const remainingLp = await pairContract.balanceOf(wallet.address);

  console.log('\n========================================================================');
  console.log('🎉 SUCCESS! LIQUIDITY EXTRACTION REPORT:');
  console.log('========================================================================');
  console.log('💵 Net USDT Received:  +$' + ethers.formatUnits(netUsdtGained, 18) + ' USDT');
  console.log('🪙 Net CAI Received:   +' + ethers.formatUnits(netCaiGained, 18) + ' CAI');
  console.log('💰 New Wallet USDT:    $' + ethers.formatUnits(walletUsdtAfter, 18) + ' USDT');
  console.log('🥞 Remaining Cake-LP:  ' + ethers.formatEther(remainingLp) + ' LP');
  console.log('========================================================================');
}

main().catch((err) => {
  console.error('\n❌ EXECUTION FAILED:', err);
  process.exit(1);
});
