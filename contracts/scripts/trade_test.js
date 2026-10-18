import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🧪 CYERA AI (CAAI) - $5 BUY & $5 SELL TEST FOR DEXSCREENER");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name} (Chain ID: ${network.chainId})`);
    console.log(`👤 Wallet: ${deployer.address}`);

    const CAAI_ADDRESS = process.env.CAI_TOKEN_ADDRESS || "0x193149028a3C83009Ecda6D5E76f1abD62E51a43";
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const PANCAKE_ROUTER = process.env.PANCAKE_ROUTER_ADDRESS || "0x10ED43C718714eb63d5aA57B78B54704E256024E";
    const MINING_ENGINE_ADDRESS = process.env.CYERA_MINING_ENGINE_ADDRESS || "0x61e1f46EA4f6892edF72f172eAcBC82Fa61dfbb6";

    const erc20Abi = [
        "function balanceOf(address) view returns (uint256)",
        "function allowance(address, address) view returns (uint256)",
        "function approve(address, uint256) returns (bool)",
        "function transfer(address, uint256) returns (bool)"
    ];

    const routerAbi = [
        "function swapExactTokensForTokensSupportingFeeOnTransferTokens(uint amountIn, uint amountOutMin, address[] calldata path, address to, uint deadline) external",
        "function getAmountsOut(uint amountIn, address[] calldata path) view returns (uint[] memory amounts)"
    ];

    const pairAbi = [
        "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
        "function token0() view returns (address)"
    ];

    const caiToken = new ethers.Contract(CAAI_ADDRESS, erc20Abi, deployer);
    const usdtToken = new ethers.Contract(USDT_ADDRESS, erc20Abi, deployer);
    const router = new ethers.Contract(PANCAKE_ROUTER, routerAbi, deployer);

    // Initial balances
    const usdtBefore = await usdtToken.balanceOf(deployer.address);
    const caaiBefore = await caiToken.balanceOf(deployer.address);
    console.log(`💰 Deployer Balance: ${ethers.formatEther(usdtBefore)} USDT | ${ethers.formatEther(caaiBefore)} CAAI\n`);

    // ---------------------------------------------------------
    // 1. EXECUTE $5 BUY (USDT -> CAAI to Mining Contract)
    // ---------------------------------------------------------
    console.log("⏳ [1/2] Executing $5 BUY on PancakeSwap V2...");
    const buyAmountUSDT = ethers.parseEther("5"); // $5 USDT

    // Approve USDT to Router if needed
    const usdtAllowance = await usdtToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (usdtAllowance < buyAmountUSDT) {
        console.log("   • Approving USDT to PancakeRouter...");
        const txApprove = await usdtToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApprove.wait();
        console.log("   ✔ USDT Approved!");
    }

    const buyPath = [USDT_ADDRESS, CAAI_ADDRESS];
    const deadlineBuy = Math.floor(Date.now() / 1000) + 600;

    // Output recipient is Mining Engine (PancakePair allows transfer to Mining Contract)
    console.log(`   • Swapping 5 USDT for CAAI (Destination: Mining Engine ${MINING_ENGINE_ADDRESS})...`);
    const txBuy = await router.swapExactTokensForTokensSupportingFeeOnTransferTokens(
        buyAmountUSDT,
        0, // 0 slippage restriction
        buyPath,
        MINING_ENGINE_ADDRESS,
        deadlineBuy,
        { gasLimit: 500000 }
    );
    console.log(`   ⏳ Buy Tx submitted: ${txBuy.hash}`);
    const receiptBuy = await txBuy.wait();
    console.log(`   ✅ $5 BUY Executed! (Block: ${receiptBuy.blockNumber})\n`);

    // ---------------------------------------------------------
    // 2. EXECUTE ~$5 SELL (10 CAAI -> USDT to Deployer)
    // ---------------------------------------------------------
    console.log("⏳ [2/2] Executing ~$5 SELL on PancakeSwap V2...");
    const sellAmountCAAI = ethers.parseEther("10"); // 10 CAAI (~$5 USDT)

    // Approve CAAI to Router if needed
    const caaiAllowance = await caiToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (caaiAllowance < sellAmountCAAI) {
        console.log("   • Approving CAAI to PancakeRouter...");
        const txApproveCAAI = await caiToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApproveCAAI.wait();
        console.log("   ✔ CAAI Approved!");
    }

    const sellPath = [CAAI_ADDRESS, USDT_ADDRESS];
    const deadlineSell = Math.floor(Date.now() / 1000) + 600;

    console.log(`   • Swapping 10 CAAI for USDT (Destination: ${deployer.address})...`);
    const txSell = await router.swapExactTokensForTokensSupportingFeeOnTransferTokens(
        sellAmountCAAI,
        0, // 0 slippage restriction
        sellPath,
        deployer.address,
        deadlineSell,
        { gasLimit: 500000 }
    );
    console.log(`   ⏳ Sell Tx submitted: ${txSell.hash}`);
    const receiptSell = await txSell.wait();
    console.log(`   ✅ ~$5 SELL Executed! (Block: ${receiptSell.blockNumber})\n`);

    // Final Pair Reserves & DexScreener URL
    const pairAddress = process.env.PANCAKESWAP_PAIR_ADDRESS || "0x17C405d90e8d112c340698EE2b09EB31FF72f730";
    const pairContract = new ethers.Contract(pairAddress, pairAbi, deployer);
    const reserves = await pairContract.getReserves();
    const token0 = await pairContract.token0();
    const isCaaiToken0 = token0.toLowerCase() === CAAI_ADDRESS.toLowerCase();
    const reserveCaai = isCaaiToken0 ? reserves[0] : reserves[1];
    const reserveUsdt = isCaaiToken0 ? reserves[1] : reserves[0];
    const price = (Number(ethers.formatEther(reserveUsdt)) / Number(ethers.formatEther(reserveCaai))).toFixed(4);

    console.log("================================================================");
    console.log("   🎉 BOTH BUY & SELL TRANSACTIONS RECORDED ON-CHAIN!");
    console.log(`   📈 Live Price: $${price} / CAAI`);
    console.log(`   📊 Pair Reserves: ${ethers.formatEther(reserveCaai)} CAAI / ${ethers.formatEther(reserveUsdt)} USDT`);
    console.log(`   🔗 DexScreener Link: https://dexscreener.com/bsc/${pairAddress}`);
    console.log(`   🔗 GeckoTerminal Link: https://www.geckoterminal.com/bsc/pools/${pairAddress}`);
    console.log("================================================================");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Trade test failed:", error);
        process.exit(1);
    });
