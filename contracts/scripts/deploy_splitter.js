import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🚀 DEPLOYING TREASURY SPLITTER (65% OWNER / 5% SECONDARY)");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name} (Chain ID: ${network.chainId})`);
    console.log(`👤 Deployer: ${deployer.address}`);

    const balance = await ethers.provider.getBalance(deployer.address);
    console.log(`💰 Deployer Balance: ${ethers.formatEther(balance)} BNB\n`);

    // Configuration
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const OWNER_MAIN_WALLET = process.env.ADMIN_OWNER_ADDRESS || "0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7";
    const SECONDARY_WALLET = process.env.SECONDARY_TREASURY_WALLET || "0x0a4e1ecF7df23fCD369A836763E5A791E84F03E7"; // Replace with your 5% wallet

    console.log(`📋 Parameters:`);
    console.log(`   USDT Address:       ${USDT_ADDRESS}`);
    console.log(`   Owner Main (65%):   ${OWNER_MAIN_WALLET}`);
    console.log(`   Secondary (5%):     ${SECONDARY_WALLET}\n`);

    // 1. Deploy TreasurySplitter
    console.log("⏳ Deploying TreasurySplitter contract...");
    const TreasurySplitter = await ethers.getContractFactory("TreasurySplitter");
    const splitter = await TreasurySplitter.deploy(
        USDT_ADDRESS,
        OWNER_MAIN_WALLET,
        SECONDARY_WALLET,
        deployer.address
    );
    await splitter.waitForDeployment();
    const splitterAddress = await splitter.getAddress();

    console.log(`   ✅ TreasurySplitter Deployed at: ${splitterAddress}\n`);

    console.log("================================================================");
    console.log("   📌 NEXT STEP TO LINK WITH MINING ENGINE:");
    console.log("================================================================");
    console.log(`1. Call setTreasuryWallet on CyeraMiningEngine with new address:`);
    console.log(`   Target: ${splitterAddress}`);
    console.log(`2. All 70% deposits will now automatically split:`);
    console.log(`   - 65/70th (65% of stake) -> ${OWNER_MAIN_WALLET}`);
    console.log(`   - 5/70th  (5% of stake)  -> ${SECONDARY_WALLET}`);
    console.log("================================================================\n");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Deployment failed:", error);
        process.exit(1);
    });
