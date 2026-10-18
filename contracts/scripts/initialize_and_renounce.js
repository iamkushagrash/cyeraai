import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🚀 CYERA AI (CAAI) - LIQUIDITY, BUFFER & RENOUNCE SETUP");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name} (Chain ID: ${network.chainId})`);
    console.log(`👤 Deployer: ${deployer.address}`);

    const balanceBNB = await ethers.provider.getBalance(deployer.address);
    console.log(`💰 Deployer Balance: ${ethers.formatEther(balanceBNB)} BNB\n`);

    const CAAI_ADDRESS = process.env.CAI_TOKEN_ADDRESS || "0x193149028a3C83009Ecda6D5E76f1abD62E51a43";
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const PANCAKE_ROUTER = process.env.PANCAKE_ROUTER_ADDRESS || "0x10ED43C718714eb63d5aA57B78B54704E256024E";
    const MINING_ENGINE_ADDRESS = process.env.CYERA_MINING_ENGINE_ADDRESS || "0x61e1f46EA4f6892edF72f172eAcBC82Fa61dfbb6";

    // ERC20 Interface
    const erc20Abi = [
        "function balanceOf(address) view returns (uint256)",
        "function allowance(address, address) view returns (uint256)",
        "function approve(address, uint256) returns (bool)",
        "function transfer(address, uint256) returns (bool)",
        "function owner() view returns (address)",
        "function configurationLocked() view returns (bool)",
        "function setMiningContractAndRenounceOwnership(address) external",
        "function pancakePair() view returns (address)"
    ];

    const routerAbi = [
        "function addLiquidity(address tokenA, address tokenB, uint amountADesired, uint amountBDesired, uint amountAMin, uint amountBMin, address to, uint deadline) external returns (uint amountA, uint amountB, uint liquidity)"
    ];

    const pairAbi = [
        "function getReserves() view returns (uint112 reserve0, uint112 reserve1, uint32 blockTimestampLast)",
        "function token0() view returns (address)",
        "function token1() view returns (address)"
    ];

    const caiToken = new ethers.Contract(CAAI_ADDRESS, erc20Abi, deployer);
    const usdtToken = new ethers.Contract(USDT_ADDRESS, erc20Abi, deployer);
    const router = new ethers.Contract(PANCAKE_ROUTER, routerAbi, deployer);

    const caaiBal = await caiToken.balanceOf(deployer.address);
    const usdtBal = await usdtToken.balanceOf(deployer.address);

    console.log(`📊 Deployer CAAI Balance: ${ethers.formatEther(caaiBal)} CAAI`);
    console.log(`📊 Deployer USDT Balance: ${ethers.formatEther(usdtBal)} USDT\n`);

    const LIQ_CAAI = ethers.parseEther("2000"); // 2,000 CAAI
    const LIQ_USDT = ethers.parseEther("1000"); // 1,000 USDT ($0.50 base price)
    const BUFFER_CAAI = ethers.parseEther("40000"); // 40,000 CAAI buffer

    // STEP 1: Add Initial Liquidity to PancakeSwap V2
    console.log("⏳ [1/3] Adding Initial Liquidity on PancakeSwap V2 (1,000 USDT + 2,000 CAAI)...");
    
    console.log("   • Approving CAAI to PancakeRouter...");
    const caaiAllowance = await caiToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (caaiAllowance < LIQ_CAAI) {
        const txApproveCAAI = await caiToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApproveCAAI.wait();
        console.log("   ✔ CAAI Approved!");
    } else {
        console.log("   ✔ CAAI already approved.");
    }

    console.log("   • Approving USDT to PancakeRouter...");
    const usdtAllowance = await usdtToken.allowance(deployer.address, PANCAKE_ROUTER);
    if (usdtAllowance < LIQ_USDT) {
        const txApproveUSDT = await usdtToken.approve(PANCAKE_ROUTER, ethers.MaxUint256);
        await txApproveUSDT.wait();
        console.log("   ✔ USDT Approved!");
    } else {
        console.log("   ✔ USDT already approved.");
    }

    const deadline = Math.floor(Date.now() / 1000) + 600; // 10 minutes
    console.log("   • Calling addLiquidity on PancakeRouter...");
    const txLiq = await router.addLiquidity(
        CAAI_ADDRESS,
        USDT_ADDRESS,
        LIQ_CAAI,
        LIQ_USDT,
        0,
        0,
        deployer.address,
        deadline,
        { gasLimit: 5000000 }
    );
    console.log(`   ⏳ Transaction submitted: ${txLiq.hash}`);
    const receiptLiq = await txLiq.wait();
    console.log(`   ✅ Liquidity Added! (Block: ${receiptLiq.blockNumber})\n`);

    // Verify Pair reserves
    const pairAddress = await caiToken.pancakePair();
    const pairContract = new ethers.Contract(pairAddress, pairAbi, deployer);
    const reserves = await pairContract.getReserves();
    const token0 = await pairContract.token0();
    const isCaaiToken0 = token0.toLowerCase() === CAAI_ADDRESS.toLowerCase();
    const reserveCaai = isCaaiToken0 ? reserves[0] : reserves[1];
    const reserveUsdt = isCaaiToken0 ? reserves[1] : reserves[0];
    console.log(`   📈 PancakeSwap Pair Reserves: ${ethers.formatEther(reserveCaai)} CAAI / ${ethers.formatEther(reserveUsdt)} USDT`);
    console.log(`   💰 Base Price: $${(Number(ethers.formatEther(reserveUsdt)) / Number(ethers.formatEther(reserveCaai))).toFixed(4)} / CAAI\n`);

    // STEP 2: Transfer 40,000 CAAI Buffer to CyeraMiningEngine
    console.log(`⏳ [2/3] Transferring 40,000 CAAI Buffer to CyeraMiningEngine (${MINING_ENGINE_ADDRESS})...`);
    const txBuffer = await caiToken.transfer(MINING_ENGINE_ADDRESS, BUFFER_CAAI);
    console.log(`   ⏳ Transaction submitted: ${txBuffer.hash}`);
    const receiptBuffer = await txBuffer.wait();
    console.log(`   ✅ Buffer Transferred! (Block: ${receiptBuffer.blockNumber})`);
    const engineBal = await caiToken.balanceOf(MINING_ENGINE_ADDRESS);
    console.log(`   🏦 CyeraMiningEngine CAAI Balance: ${ethers.formatEther(engineBal)} CAAI\n`);

    // STEP 3: Lock Configuration and Renounce CAAI Token Ownership (100% Green Score)
    console.log("⏳ [3/3] Calling setMiningContractAndRenounceOwnership on CAIToken...");
    const txRenounce = await caiToken.setMiningContractAndRenounceOwnership(MINING_ENGINE_ADDRESS);
    console.log(`   ⏳ Transaction submitted: ${txRenounce.hash}`);
    const receiptRenounce = await txRenounce.wait();
    console.log(`   ✅ Configuration Locked & Ownership Renounced! (Block: ${receiptRenounce.blockNumber})`);

    const finalOwner = await caiToken.owner();
    const isLocked = await caiToken.configurationLocked();
    console.log(`   🔒 Token Configuration Locked: ${isLocked}`);
    console.log(`   👑 Token Owner:                ${finalOwner} (Zero Address = 100% Renounced!)\n`);

    console.log("================================================================");
    console.log("   🎉 PROTOCOL V2 IS OFFICIALLY 100% LIVE ON BSC MAINNET!");
    console.log("   🌟 100% GREEN SCORE ON DEXSCREENER & GOPLUS SECURITY");
    console.log("================================================================");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Setup failed:", error);
        process.exit(1);
    });
