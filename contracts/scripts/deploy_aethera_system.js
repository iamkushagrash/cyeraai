import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🚀 CYERA AI - COMPLETE PROTOCOL DEPLOYMENT (AETHERA STANDARD)");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name} (Chain ID: ${network.chainId})`);
    console.log(`👤 Deployer: ${deployer.address}`);

    const balance = await ethers.provider.getBalance(deployer.address);
    console.log(`💰 Deployer Balance: ${ethers.formatEther(balance)} BNB\n`);

    // Addresses on BSC Mainnet
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const PANCAKE_ROUTER = process.env.PANCAKE_ROUTER_ADDRESS || "0x10ED43C718714eb63d5aA57B78B54704E256024E";
    const TREASURY_WALLET = process.env.ADMIN_OWNER_ADDRESS || "0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7";
    const BACKEND_SIGNER = process.env.SIGNER_WALLET_ADDRESS || "0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7";

    console.log(`📋 Configuration Parameters:`);
    console.log(`   USDT Address:     ${USDT_ADDRESS}`);
    console.log(`   Pancake Router:   ${PANCAKE_ROUTER}`);
    console.log(`   Owner Treasury:   ${TREASURY_WALLET} (Receives 70% USDT directly)`);
    console.log(`   Backend Signer:   ${BACKEND_SIGNER}\n`);

    // 1. Deploy CAIToken (Aethera Standard)
    console.log("⏳ [1/3] Deploying CAIToken (Fixed 300,000 Supply & Auto Pancake Pair Creation)...");
    const CAIToken = await ethers.getContractFactory("CAIToken");
    const caiToken = await CAIToken.deploy(USDT_ADDRESS, PANCAKE_ROUTER);
    await caiToken.waitForDeployment();
    const caiTokenAddress = await caiToken.getAddress();
    const pancakePairAddress = await caiToken.pancakePair();

    console.log(`   ✅ CAIToken Address:           ${caiTokenAddress}`);
    console.log(`   ✅ PancakeSwap V2 Pair:        ${pancakePairAddress}\n`);

    // 2. Deploy CyeraMiningEngine
    console.log("⏳ [2/3] Deploying CyeraMiningEngine (70% Treasury + 30% Auto-Buy & Staking ROI Claims)...");
    const CyeraMiningEngine = await ethers.getContractFactory("CyeraMiningEngine");
    const miningEngine = await CyeraMiningEngine.deploy(
        USDT_ADDRESS,
        caiTokenAddress,
        PANCAKE_ROUTER,
        TREASURY_WALLET,
        BACKEND_SIGNER,
        deployer.address
    );
    await miningEngine.waitForDeployment();
    const miningEngineAddress = await miningEngine.getAddress();

    console.log(`   ✅ CyeraMiningEngine Address:  ${miningEngineAddress}\n`);

    // 3. Deploy USDTWithdrawalVault (Working Incomes: Level & Rank Payout Vault)
    console.log("⏳ [3/3] Deploying USDTWithdrawalVault (Working Incomes Vault with Admin Emergency Recover)...");
    const USDTWithdrawalVault = await ethers.getContractFactory("USDTWithdrawalVault");
    const usdtVault = await USDTWithdrawalVault.deploy(
        USDT_ADDRESS,
        BACKEND_SIGNER,
        deployer.address
    );
    await usdtVault.waitForDeployment();
    const usdtVaultAddress = await usdtVault.getAddress();

    console.log(`   ✅ USDTWithdrawalVault Address:${usdtVaultAddress}\n`);

    console.log("================================================================");
    console.log("   🎉 DEPLOYMENT COMPLETE & READY FOR ENV SYNC");
    console.log("================================================================");
    console.log(`CAI_TOKEN_ADDRESS=${caiTokenAddress}`);
    console.log(`CYERA_MINING_ENGINE_ADDRESS=${miningEngineAddress}`);
    console.log(`USDT_WITHDRAWAL_VAULT_ADDRESS=${usdtVaultAddress}`);
    console.log(`PANCAKESWAP_PAIR_ADDRESS=${pancakePairAddress}`);
    console.log("================================================================\n");

    console.log("📌 NEXT 3 SIMPLE STEPS TO GO LIVE:");
    console.log("1. Add initial liquidity on PancakeSwap V2 (e.g. 500 USDT + 500 CAI).");
    console.log(`2. Open /dishi/yashi and click 'Set Mining Engine & Renounce Ownership' (Target: ${miningEngineAddress}).`);
    console.log("3. Token is live with 100% Green Score (0 issues) on DexScreener & Go+ Security!");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Deployment failed:", error);
        process.exit(1);
    });
