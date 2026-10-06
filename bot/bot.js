import { ethers } from 'ethers';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Load .env from current bot folder or root folder
dotenv.config({ path: path.join(__dirname, '.env') });
dotenv.config({ path: path.join(__dirname, '..', '.env') });

// Configuration variables
const RPC_LIST = [
    process.env.BSC_RPC_URL || 'https://bsc-dataseed.binance.org/',
    'https://bsc-rpc.publicnode.com',
    'https://1rpc.io/bnb',
    'https://binance.llamarpc.com',
    'https://bscrpc.com'
];

const USDT_ADDR = process.env.USDT_TOKEN_ADDRESS || '0x55d398326f99059fF775485246999027B3197955';
const CAI_ADDR = process.env.CAI_TOKEN_ADDRESS || '0x4756618F389A46819008Aff01ad0f91A38154eDB';
const ROUTER_ADDR = process.env.PANCAKE_ROUTER_ADDRESS || '0x10ED43C718714eb63d5aA57B78B54704E256024E';
const MINING_ADDR = process.env.MINING_CONTRACT_ADDRESS || '0xdd905468F6F91f8c37eFB9e27E1f282734E60217';

// Random Trade Amount Range (In USDT)
const MIN_TRADE = parseFloat(process.env.MIN_TRADE_USDT || process.env.MIN_BUY_USDT || '1.00');
const MAX_TRADE = parseFloat(process.env.MAX_TRADE_USDT || process.env.MAX_BUY_USDT || '3.50');

// Random Delay Interval (In Seconds)
const MIN_DELAY = parseInt(process.env.MIN_DELAY_SECONDS || '20', 10);
const MAX_DELAY = parseInt(process.env.MAX_DELAY_SECONDS || '120', 10);

// Target Price Band to keep price balanced and organic
const TARGET_MIN_PRICE = parseFloat(process.env.TARGET_MIN_PRICE || '6.50');
const TARGET_MAX_PRICE = parseFloat(process.env.TARGET_MAX_PRICE || '6.85');

// Minimal ABIs
const ERC20_ABI = [
    'function name() view returns (string)',
    'function symbol() view returns (string)',
    'function decimals() view returns (uint8)',
    'function balanceOf(address) view returns (uint256)',
    'function allowance(address owner, address spender) view returns (uint256)',
    'function approve(address spender, uint256 amount) returns (bool)'
];

const ROUTER_ABI = [
    'function swapExactTokensForTokensSupportingFeeOnTransferTokens(uint amountIn, uint amountOutMin, address[] calldata path, address to, uint deadline) external',
    'function getAmountsOut(uint amountIn, address[] calldata path) view returns (uint[] memory amounts)',
    'function getAmountsIn(uint amountOut, address[] calldata path) view returns (uint[] memory amounts)'
];

// Helper: sleep
const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

// Helper: Random float with 2 decimals
function getRandomAmount(min, max) {
    const val = Math.random() * (max - min) + min;
    return parseFloat(val.toFixed(2));
}

// Helper: Random integer
function getRandomDelay(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Helper: Get working BSC provider
async function getProvider() {
    for (const rpc of RPC_LIST) {
        try {
            const p = new ethers.JsonRpcProvider(rpc);
            await p.getBlockNumber();
            return p;
        } catch (e) {
            // try next RPC
        }
    }
    return new ethers.JsonRpcProvider(RPC_LIST[0]);
}

// Helper: Load all private keys (supports 1, 4, 10, 20+ wallets)
function getPrivateKeys() {
    const rawKeys = process.env.BOT_PRIVATE_KEYS || process.env.BOT_PRIVATE_KEY || process.env.PRIVATE_KEY || '';
    if (!rawKeys || rawKeys.includes('your_private_key_here')) {
        console.error('\n❌ ERROR: BOT_PRIVATE_KEYS is missing in .env file!');
        console.error('👉 Please open bot/.env and paste 1 or more wallet private keys (separated by commas).\n');
        process.exit(1);
    }
    const keys = rawKeys.split(/[,;\n]+/).map((k) => k.trim()).filter((k) => k.length >= 64);
    if (keys.length === 0) {
        console.error('\n❌ No valid private keys found. Please check bot/.env.');
        process.exit(1);
    }
    return keys;
}

// Helper: Check live CAI price on PancakeSwap
async function getLivePrice(routerContract, usdtDecimals, caiDecimals) {
    try {
        const oneCai = ethers.parseUnits('1', caiDecimals);
        const amounts = await routerContract.getAmountsOut(oneCai, [CAI_ADDR, USDT_ADDR]);
        return parseFloat(ethers.formatUnits(amounts[1], usdtDecimals));
    } catch (e) {
        return 6.70; // fallback default
    }
}

// Main 24/7 Organic Volume Maker Loop
async function runBot() {
    console.log('========================================================================');
    console.log('🚀 CYERA AI (CAI) - 24/7 ORGANIC MULTI-WALLET MARKET MAKER BOT');
    console.log('========================================================================');
    console.log(`📌 CAI Token:        ${CAI_ADDR}`);
    console.log(`📌 USDT Token:       ${USDT_ADDR}`);
    console.log(`📌 Pancake Router:   ${ROUTER_ADDR}`);
    console.log(`📌 Mining Recipient: ${MINING_ADDR}`);
    console.log(`💰 Trade Range:      $${MIN_TRADE.toFixed(2)} - $${MAX_TRADE.toFixed(2)} USDT (Organic Random Cents)`);
    console.log(`⏱️ Delay Interval:   ${MIN_DELAY}s - ${MAX_DELAY}s`);
    console.log(`🎯 Price Channel:    $${TARGET_MIN_PRICE} - $${TARGET_MAX_PRICE} USD`);
    console.log('------------------------------------------------------------------------\n');

    const privateKeys = getPrivateKeys();
    console.log(`✅ Initialized ${privateKeys.length} active trading wallet(s).\n`);

    let provider = await getProvider();
    let tradeCount = 0;
    let consecutiveBuys = 0;
    let consecutiveSells = 0;

    // Track approved status to save RPC queries
    const approvedUsdtMap = new Set();
    const approvedCaiMap = new Set();

    while (true) {
        tradeCount++;
        // Pick random wallet from pool so there is NO sequential pattern
        const randomKeyIndex = Math.floor(Math.random() * privateKeys.length);
        const currentKey = privateKeys[randomKeyIndex];

        try {
            const wallet = new ethers.Wallet(currentKey, provider);
            const walletAddr = wallet.address;
            const shortAddr = `${walletAddr.substring(0, 6)}...${walletAddr.substring(walletAddr.length - 4)}`;

            const usdtContract = new ethers.Contract(USDT_ADDR, ERC20_ABI, wallet);
            const caiContract = new ethers.Contract(CAI_ADDR, ERC20_ABI, wallet);
            const routerContract = new ethers.Contract(ROUTER_ADDR, ROUTER_ABI, wallet);

            const usdtDecimals = await usdtContract.decimals();
            const caiDecimals = await caiContract.decimals();

            // 1. Check Live Price
            const livePrice = await getLivePrice(routerContract, usdtDecimals, caiDecimals);
            console.log(`\n[Trade #${tradeCount}] 📊 Live CAI Price: $${livePrice.toFixed(4)} USDT | 🎯 Active Wallet #${randomKeyIndex + 1}: ${shortAddr}`);

            // 2. Fetch Wallet Balances
            const bnbBal = await provider.getBalance(walletAddr);
            const usdtBal = await usdtContract.balanceOf(walletAddr);
            const caiBal = await caiContract.balanceOf(walletAddr);

            const bnbFormatted = parseFloat(ethers.formatEther(bnbBal));
            const usdtFormatted = parseFloat(ethers.formatUnits(usdtBal, usdtDecimals));
            const caiFormatted = parseFloat(ethers.formatUnits(caiBal, caiDecimals));

            console.log(`   Balances -> BNB: ${bnbFormatted.toFixed(5)} | USDT: $${usdtFormatted.toFixed(2)} | CAI: ${caiFormatted.toFixed(4)}`);

            if (bnbFormatted < 0.0008) {
                console.warn(`   ⚠️ Warning: Wallet ${shortAddr} has very low BNB for gas (${bnbFormatted.toFixed(5)} BNB).`);
            }

            // 3. Determine Action: BUY 🟢 or SELL 🔴 (Anti-Bot Stochastic Algorithm)
            let action = 'BUY'; // default

            if (livePrice > TARGET_MAX_PRICE) {
                // Price is high -> Sell to cool down and maintain price stability
                action = 'SELL';
            } else if (livePrice < TARGET_MIN_PRICE) {
                // Price is low -> Buy to pump/maintain floor
                action = 'BUY';
            } else {
                // Within target channel -> Random 50/50 organic distribution
                if (consecutiveBuys >= 3) {
                    action = 'SELL';
                } else if (consecutiveSells >= 3) {
                    action = 'BUY';
                } else {
                    action = Math.random() < 0.55 ? 'BUY' : 'SELL';
                }
            }

            // Random trade amount in micro-range (e.g. $1.35, $2.70, $3.12)
            const tradeAmountUsdt = getRandomAmount(MIN_TRADE, MAX_TRADE);

            // =================================================================
            // ACTION A: 🟢 ORGANIC BUY
            // =================================================================
            if (action === 'BUY') {
                if (usdtFormatted >= tradeAmountUsdt) {
                    console.log(`   🟢 Action: BUY $${tradeAmountUsdt.toFixed(2)} USDT of CAI`);
                    const buyAmountWei = ethers.parseUnits(tradeAmountUsdt.toString(), usdtDecimals);

                    // Check & Auto-Approve USDT
                    if (!approvedUsdtMap.has(walletAddr)) {
                        const allowance = await usdtContract.allowance(walletAddr, ROUTER_ADDR);
                        if (allowance < buyAmountWei) {
                            console.log(`   🔓 Approving USDT to PancakeSwap Router...`);
                            const txApp = await usdtContract.approve(ROUTER_ADDR, ethers.MaxUint256);
                            await txApp.wait(1);
                        }
                        approvedUsdtMap.add(walletAddr);
                    }

                    // Swap USDT -> CAI
                    const buyPath = [USDT_ADDR, CAI_ADDR];
                    const deadline = Math.floor(Date.now() / 1000) + 300;

                    const buyTx = await routerContract.swapExactTokensForTokensSupportingFeeOnTransferTokens(
                        buyAmountWei,
                        0,
                        buyPath,
                        MINING_ADDR,
                        deadline,
                        { gasLimit: 350000 }
                    );

                    console.log(`   ⏳ Tx Broadcasted: https://bscscan.com/tx/${buyTx.hash}`);
                    const receipt = await buyTx.wait(1);

                    if (receipt.status === 1) {
                        console.log(`   🎉 BUY SUCCESS! Green Candle on Block #${receipt.blockNumber} 🟢`);
                        consecutiveBuys++;
                        consecutiveSells = 0;
                    }
                } else {
                    console.warn(`   ⚠️ Wallet ${shortAddr} has insufficient USDT ($${usdtFormatted.toFixed(2)} < $${tradeAmountUsdt}). Switching to Sell.`);
                    action = 'SELL'; // fallback to sell if possible
                }
            }

            // =================================================================
            // ACTION B: 🔴 ORGANIC SELL
            // =================================================================
            if (action === 'SELL') {
                // Calculate CAI needed for ~$tradeAmountUsdt
                let caiToSellWei = 0n;
                try {
                    const sellPath = [CAI_ADDR, USDT_ADDR];
                    const desiredUsdtWei = ethers.parseUnits(tradeAmountUsdt.toString(), usdtDecimals);
                    const amountsIn = await routerContract.getAmountsIn(desiredUsdtWei, sellPath);
                    caiToSellWei = amountsIn[0];
                } catch (e) {
                    const approxCai = (tradeAmountUsdt / (livePrice || 6.70)).toFixed(4);
                    caiToSellWei = ethers.parseUnits(approxCai.toString(), caiDecimals);
                }

                const caiNeededFormatted = parseFloat(ethers.formatUnits(caiToSellWei, caiDecimals));

                if (caiFormatted >= caiNeededFormatted && caiToSellWei > 0n) {
                    console.log(`   🔴 Action: SELL ${caiNeededFormatted.toFixed(4)} CAI (~$${tradeAmountUsdt.toFixed(2)} USDT)`);

                    // Check & Auto-Approve CAI
                    if (!approvedCaiMap.has(walletAddr)) {
                        const allowance = await caiContract.allowance(walletAddr, ROUTER_ADDR);
                        if (allowance < caiToSellWei) {
                            console.log(`   🔓 Approving CAI to PancakeSwap Router...`);
                            const txApp = await caiContract.approve(ROUTER_ADDR, ethers.MaxUint256);
                            await txApp.wait(1);
                        }
                        approvedCaiMap.add(walletAddr);
                    }

                    // Swap CAI -> USDT (Delivers USDT back to wallet!)
                    const sellPath = [CAI_ADDR, USDT_ADDR];
                    const deadline = Math.floor(Date.now() / 1000) + 300;

                    const sellTx = await routerContract.swapExactTokensForTokensSupportingFeeOnTransferTokens(
                        caiToSellWei,
                        0,
                        sellPath,
                        walletAddr,
                        deadline,
                        { gasLimit: 350000 }
                    );

                    console.log(`   ⏳ Tx Broadcasted: https://bscscan.com/tx/${sellTx.hash}`);
                    const receipt = await sellTx.wait(1);

                    if (receipt.status === 1) {
                        console.log(`   🎉 SELL SUCCESS! Red Candle Confirmed & USDT received in Wallet 🔴`);
                        consecutiveSells++;
                        consecutiveBuys = 0;
                    }
                } else {
                    console.warn(`   ⚠️ Wallet ${shortAddr} has ${caiFormatted.toFixed(4)} CAI (Needs ~${caiNeededFormatted.toFixed(4)} CAI to sell).`);
                    console.warn(`   👉 Tip: Send some CAI tokens to ${shortAddr} so it can sell!`);
                }
            }

        } catch (err) {
            console.error(`   ❌ Trade error (resuming):`, err.message || err);
            provider = await getProvider();
        }

        // Random delay before next trade (e.g. 20s - 120s)
        const nextDelay = getRandomDelay(MIN_DELAY, MAX_DELAY);
        console.log(`⏳ Next random trade in ${nextDelay}s...`);
        await sleep(nextDelay * 1000);
    }
}

// Run bot forever
runBot().catch((e) => {
    console.error('Fatal error:', e);
});
