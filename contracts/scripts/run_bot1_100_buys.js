import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🤖 EXECUTING 100x $1 BUY TRANSACTIONS FROM BOT1 ($100 TOTAL)");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name}`);

    const bot1Pk = process.env.bot1pvt.startsWith("0x") ? process.env.bot1pvt : "0x" + process.env.bot1pvt;
    const bot1Wallet = new ethers.Wallet(bot1Pk, ethers.provider);
    console.log(`🤖 Bot1 Address: ${bot1Wallet.address}`);

    const CAAI_ADDRESS = process.env.CAI_TOKEN_ADDRESS || "0x193149028a3C83009Ecda6D5E76f1abD62E51a43";
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const PANCAKE_ROUTER = process.env.PANCAKE_ROUTER_ADDRESS || "0x10ED43C718714eb63d5aA57B78B54704E256024E";
    const MINING_ENGINE_ADDRESS = process.env.CYERA_MINING_ENGINE_ADDRESS || "0x61e1f46EA4f6892edF72f172eAcBC82Fa61dfbb6";

    const erc20Abi = [
        "function balanceOf(address) view returns (uint256)",
        "function allowance(address, address) view returns (uint256)",
        "function approve(address, uint256) returns (bool)"
    ];

    const routerAbi = [
        "function swapExactTokensForTokensSupportingFeeOnTransferTokens(uint amountIn, uint amountOutMin, address[] calldata path, address to, uint deadline) external"
    ];

    const pairAbi = [
        "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
        "function token0() view returns (address)"
    ];

    const usdtToken = new ethers.Contract(USDT_ADDRESS, erc20Abi, bot1Wallet);
    const router = new ethers.Contract(PANCAKE_ROUTER, routerAbi, bot1Wallet);

    const bnbBal = await ethers.provider.getBalance(bot1Wallet.address);
    const usdtBal = await usdtToken.balanceOf(bot1Wallet.address);
    console.log(`💰 Bot1 BNB Balance:  ${ethers.formatEther(bnbBal)} BNB`);
    console.log(`💰 Bot1 USDT Balance: ${ethers.formatEther(usdtBal)} USDT\n`);

    const TOTAL_BUYS = 100;
    const buyAmount = ethers.parseEther("1"); // $1 each

    // Check & approve USDT
    const neededUSDT = ethers.parseEther("100");
    const currentAllowance = await usdtToken.allowance(bot1Wallet.address, PANCAKE_ROUTER);
    if (currentAllowance < neededUSDT) {
        console.log("   • Approving USDT to PancakeRouter from Bot1...");
        const txApprove = await usdtToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApprove.wait();
        console.log("   ✔ USDT Approved by Bot1!\n");
    }

    const path = [USDT_ADDRESS, CAAI_ADDRESS];
    console.log(`🚀 Starting execution of ${TOTAL_BUYS} consecutive $1 BUY transactions...\n`);

    let successCount = 0;
    let currentNonce = await ethers.provider.getTransactionCount(bot1Wallet.address, "latest");
    console.log(`📌 Starting Nonce: ${currentNonce}\n`);

    for (let i = 1; i <= TOTAL_BUYS; i++) {
        let retries = 0;
        let done = false;

        while (!done && retries < 3) {
            try {
                const deadline = Math.floor(Date.now() / 1000) + 600;
                const feeData = await ethers.provider.getFeeData();
                const gasPrice = feeData.gasPrice || ethers.parseUnits("1", "gwei");

                const tx = await router.swapExactTokensForTokensSupportingFeeOnTransferTokens(
                    buyAmount,
                    0,
                    path,
                    MINING_ENGINE_ADDRESS,
                    deadline,
                    { 
                        gasLimit: 350000,
                        gasPrice: gasPrice,
                        nonce: currentNonce
                    }
                );

                const receipt = await tx.wait();
                currentNonce++;
                successCount++;
                done = true;
                console.log(`✅ [${i}/${TOTAL_BUYS}] Tx Confirmed! Block: ${receipt.blockNumber} | Hash: ${tx.hash}`);

                // Small pause between transactions
                if (i < TOTAL_BUYS) {
                    await new Promise(r => setTimeout(r, 1000));
                }
            } catch (err) {
                retries++;
                console.error(`⚠️ [${i}/${TOTAL_BUYS}] Attempt ${retries} failed: ${err.message?.slice(0, 100)}...`);
                // Resync nonce
                currentNonce = await ethers.provider.getTransactionCount(bot1Wallet.address, "latest");
                await new Promise(r => setTimeout(r, 2500));
            }
        }

        if (!done) {
            console.error(`❌ [${i}/${TOTAL_BUYS}] Failed after 3 retries. Skipping.`);
        }
    }

    // Final summary
    const pairAddress = process.env.PANCAKESWAP_PAIR_ADDRESS || "0x17C405d90e8d112c340698EE2b09EB31FF72f730";
    const pairContract = new ethers.Contract(pairAddress, pairAbi, bot1Wallet);
    const reserves = await pairContract.getReserves();
    const token0 = await pairContract.token0();
    const isCaai = token0.toLowerCase() === CAAI_ADDRESS.toLowerCase();
    const caaiRes = isCaai ? reserves[0] : reserves[1];
    const usdtRes = isCaai ? reserves[1] : reserves[0];
    const newPrice = (Number(ethers.formatEther(usdtRes)) / Number(ethers.formatEther(caaiRes))).toFixed(4);

    const finalBnb = await ethers.provider.getBalance(bot1Wallet.address);
    const finalUsdt = await usdtToken.balanceOf(bot1Wallet.address);

    console.log("\n================================================================");
    console.log(`   🎉 COMPLETED ${successCount}/${TOTAL_BUYS} BUY TRANSACTIONS!`);
    console.log(`   📈 New Live Price: $${newPrice} / CAAI`);
    console.log(`   📊 Pair Reserves: ${ethers.formatEther(caaiRes)} CAAI / ${ethers.formatEther(usdtRes)} USDT`);
    console.log(`   💰 Remaining Bot1 USDT: ${ethers.formatEther(finalUsdt)} USDT`);
    console.log(`   💰 Remaining Bot1 BNB:  ${ethers.formatEther(finalBnb)} BNB`);
    console.log(`   🔗 DexScreener Link: https://dexscreener.com/bsc/${pairAddress}`);
    console.log("================================================================\n");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Bot execution failed:", error);
        process.exit(1);
    });
