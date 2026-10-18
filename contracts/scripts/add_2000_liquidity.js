import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🏊 ADDING 2,000 USDT LIQUIDITY TO CAAI/USDT PANCAKESWAP POOL");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name} (Chain ID: ${network.chainId})`);
    console.log(`👤 Deployer Address: ${deployer.address}`);

    const CAAI_ADDRESS = process.env.CAI_TOKEN_ADDRESS || "0x193149028a3C83009Ecda6D5E76f1abD62E51a43";
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const PANCAKE_ROUTER = process.env.PANCAKE_ROUTER_ADDRESS || "0x10ED43C718714eb63d5aA57B78B54704E256024E";
    const PAIR_ADDRESS = process.env.PANCAKESWAP_PAIR_ADDRESS || "0x17C405d90e8d112c340698EE2b09EB31FF72f730";

    const erc20Abi = [
        "function balanceOf(address) view returns (uint256)",
        "function allowance(address, address) view returns (uint256)",
        "function approve(address, uint256) returns (bool)"
    ];

    const routerAbi = [
        "function addLiquidity(address tokenA, address tokenB, uint amountADesired, uint amountBDesired, uint amountAMin, uint amountBMin, address to, uint deadline) external returns (uint amountA, uint amountB, uint liquidity)"
    ];

    const pairAbi = [
        "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
        "function token0() view returns (address)"
    ];

    const caiToken = new ethers.Contract(CAAI_ADDRESS, erc20Abi, deployer);
    const usdtToken = new ethers.Contract(USDT_ADDRESS, erc20Abi, deployer);
    const router = new ethers.Contract(PANCAKE_ROUTER, routerAbi, deployer);
    const pairContract = new ethers.Contract(PAIR_ADDRESS, pairAbi, deployer);

    // Initial Balances
    const bnbBal = await ethers.provider.getBalance(deployer.address);
    const usdtBal = await usdtToken.balanceOf(deployer.address);
    const caaiBal = await caiToken.balanceOf(deployer.address);

    console.log(`💰 Deployer BNB:  ${ethers.formatEther(bnbBal)} BNB`);
    console.log(`💰 Deployer USDT: ${ethers.formatEther(usdtBal)} USDT`);
    console.log(`💰 Deployer CAAI: ${ethers.formatEther(caaiBal)} CAAI\n`);

    const LIQ_USDT = ethers.parseEther("2000"); // 2,000 USDT
    if (usdtBal < LIQ_USDT) {
        throw new Error(`Insufficient USDT! Have ${ethers.formatEther(usdtBal)} USDT, need 2000 USDT`);
    }

    // Read current reserves to calculate exact optimal CAAI ratio
    console.log("🔍 Fetching current pool reserves...");
    const reserves = await pairContract.getReserves();
    const token0 = await pairContract.token0();
    const isCaaiToken0 = token0.toLowerCase() === CAAI_ADDRESS.toLowerCase();
    const reserveCaai = isCaaiToken0 ? reserves[0] : reserves[1];
    const reserveUsdt = isCaaiToken0 ? reserves[1] : reserves[0];

    const currentPrice = Number(ethers.formatEther(reserveUsdt)) / Number(ethers.formatEther(reserveCaai));
    console.log(`   • Current Reserves: ${ethers.formatEther(reserveCaai)} CAAI / ${ethers.formatEther(reserveUsdt)} USDT`);
    console.log(`   • Live Price: $${currentPrice.toFixed(6)} / CAAI`);

    // Optimal CAAI for 2000 USDT = (2000 * reserveCaai) / reserveUsdt
    const optimalCaai = (LIQ_USDT * reserveCaai) / reserveUsdt;
    // Provide 0.5% buffer for amountADesired so router uses exact required and refunds remainder
    const desiredCaai = (optimalCaai * 1005n) / 1000n;
    // 2% slippage tolerance
    const minCaai = (optimalCaai * 98n) / 100n;
    const minUsdt = (LIQ_USDT * 98n) / 100n;

    console.log(`\n📋 Liquidity Parameters:`);
    console.log(`   • Desired USDT: ${ethers.formatEther(LIQ_USDT)} USDT`);
    console.log(`   • Optimal CAAI: ${ethers.formatEther(optimalCaai)} CAAI (~${ethers.formatEther(desiredCaai)} desired)`);
    console.log(`   • Min CAAI (2% slippage): ${ethers.formatEther(minCaai)} CAAI`);
    console.log(`   • Min USDT (2% slippage): ${ethers.formatEther(minUsdt)} USDT\n`);

    if (caaiBal < desiredCaai) {
        throw new Error(`Insufficient CAAI! Have ${ethers.formatEther(caaiBal)} CAAI, need ${ethers.formatEther(desiredCaai)} CAAI`);
    }

    // Approvals
    console.log("🔐 Checking token approvals to PancakeRouter...");
    const usdtAllowance = await usdtToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (usdtAllowance < LIQ_USDT) {
        console.log("   • Approving USDT to PancakeRouter...");
        const txApproveUSDT = await usdtToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApproveUSDT.wait();
        console.log("   ✔ USDT Approved!");
    } else {
        console.log("   ✔ USDT already approved.");
    }

    const caaiAllowance = await caiToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (caaiAllowance < desiredCaai) {
        console.log("   • Approving CAAI to PancakeRouter...");
        const txApproveCAAI = await caiToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApproveCAAI.wait();
        console.log("   ✔ CAAI Approved!");
    } else {
        console.log("   ✔ CAAI already approved.");
    }

    // Add Liquidity
    const deadline = Math.floor(Date.now() / 1000) + 600; // 10 minutes
    console.log("\n🚀 Calling addLiquidity on PancakeRouter...");
    const txLiq = await router.addLiquidity(
        CAAI_ADDRESS,
        USDT_ADDRESS,
        desiredCaai,
        LIQ_USDT,
        minCaai,
        minUsdt,
        deployer.address,
        deadline,
        { gasLimit: 1000000 }
    );

    console.log(`   ⏳ Transaction submitted: ${txLiq.hash}`);
    const receiptLiq = await txLiq.wait();
    console.log(`   ✅ 2,000 USDT Liquidity Added! (Block: ${receiptLiq.blockNumber})\n`);

    // Verify New Reserves
    const newReserves = await pairContract.getReserves();
    const newCaai = isCaaiToken0 ? newReserves[0] : newReserves[1];
    const newUsdt = isCaaiToken0 ? newReserves[1] : newReserves[0];
    const newPrice = Number(ethers.formatEther(newUsdt)) / Number(ethers.formatEther(newCaai));

    console.log("================================================================");
    console.log("   🎉 LIQUIDITY INJECTION COMPLETE!");
    console.log(`   📈 New Pool Reserves: ${ethers.formatEther(newCaai)} CAAI / ${ethers.formatEther(newUsdt)} USDT`);
    console.log(`   💰 Total Pool Liquidity: ~$${(Number(ethers.formatEther(newUsdt)) * 2).toFixed(2)}`);
    console.log(`   📊 Live Price: $${newPrice.toFixed(6)} / CAAI`);
    console.log(`   🔗 Tx BscScan: https://bscscan.com/tx/${txLiq.hash}`);
    console.log(`   🔗 DexScreener: https://dexscreener.com/bsc/${PAIR_ADDRESS}`);
    console.log("================================================================\n");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Add liquidity failed:", error);
        process.exit(1);
    });
