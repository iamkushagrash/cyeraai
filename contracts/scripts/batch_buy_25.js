import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🟢 EXECUTING 25x $5 BUY TRANSACTIONS ON PANCAKESWAP V2 ($125 TOTAL)");
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
        "function approve(address, uint256) returns (bool)"
    ];

    const routerAbi = [
        "function swapExactTokensForTokensSupportingFeeOnTransferTokens(uint amountIn, uint amountOutMin, address[] calldata path, address to, uint deadline) external"
    ];

    const pairAbi = [
        "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
        "function token0() view returns (address)"
    ];

    const usdtToken = new ethers.Contract(USDT_ADDRESS, erc20Abi, deployer);
    const router = new ethers.Contract(PANCAKE_ROUTER, routerAbi, deployer);

    // Verify USDT allowance
    const totalBuyUSDT = ethers.parseEther("125");
    const currentAllowance = await usdtToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (currentAllowance < totalBuyUSDT) {
        console.log("   • Approving USDT to PancakeRouter...");
        const txApprove = await usdtToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApprove.wait();
        console.log("   ✔ USDT Approved!\n");
    }

    const buyAmount = ethers.parseEther("5"); // $5 each
    const path = [USDT_ADDRESS, CAAI_ADDRESS];
    const totalCount = 25;
    const results = [];

    for (let i = 1; i <= totalCount; i++) {
        const deadline = Math.floor(Date.now() / 1000) + 600;
        
        const tx = await router.swapExactTokensForTokensSupportingFeeOnTransferTokens(
            buyAmount,
            0,
            path,
            MINING_ENGINE_ADDRESS,
            deadline,
            { gasLimit: 500000 }
        );
        console.log(`⏳ [${i}/${totalCount}] Tx submitted: ${tx.hash}`);
        const receipt = await tx.wait();
        console.log(`   ✅ [${i}/${totalCount}] Confirmed in Block ${receipt.blockNumber}!`);
        
        results.push({
            txNumber: i,
            hash: tx.hash,
            block: receipt.blockNumber
        });

        // Small pause between transactions
        if (i < totalCount) {
            await new Promise(r => setTimeout(r, 1500));
        }
    }

    const pairAddress = process.env.PANCAKESWAP_PAIR_ADDRESS || "0x17C405d90e8d112c340698EE2b09EB31FF72f730";
    const pairContract = new ethers.Contract(pairAddress, pairAbi, deployer);
    const reserves = await pairContract.getReserves();
    const token0 = await pairContract.token0();
    const isCaai = token0.toLowerCase() === CAAI_ADDRESS.toLowerCase();
    const caaiRes = isCaai ? reserves[0] : reserves[1];
    const usdtRes = isCaai ? reserves[1] : reserves[0];
    const newPrice = (Number(ethers.formatEther(usdtRes)) / Number(ethers.formatEther(caaiRes))).toFixed(4);

    console.log("\n================================================================");
    console.log("   🎉 ALL 25x $5 BUY TRANSACTIONS COMPLETED ($125 TOTAL)!");
    console.log(`   📈 New Live Price: $${newPrice} / CAAI`);
    console.log(`   📊 Pair Reserves: ${ethers.formatEther(caaiRes)} CAAI / ${ethers.formatEther(usdtRes)} USDT`);
    console.log(`   🔗 DexScreener Link: https://dexscreener.com/bsc/${pairAddress}`);
    console.log("================================================================\n");

    console.log("Summary of all 25 transactions:");
    results.forEach(r => {
        console.log(`Buy #${r.txNumber} (Block ${r.block}): https://bscscan.com/tx/${r.hash}`);
    });
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Batch buy failed:", error);
        process.exit(1);
    });
