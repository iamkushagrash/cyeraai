import hre from "hardhat";
import dotenv from "dotenv";
dotenv.config();

const { ethers } = hre;

/**
 * @title Deploy Royal Jackpot Smart Contracts
 * Deploys RoyalJackpotDeposit and RoyalJackpotVault
 */
async function main() {
    console.log("==================================================================");
    console.log("👑 Deploying Royal Jackpot (Lucky Draw) Contracts Suite...");
    console.log("==================================================================");

    const [deployer] = await ethers.getSigners();
    console.log("Deployer Address:", deployer.address);
    const balance = await ethers.provider.getBalance(deployer.address);
    console.log("Deployer Balance:", ethers.formatEther(balance), "BNB");

    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const WHITELISTED_COLLECTOR = process.env.ROYAL_JACKPOT_COLLECTOR_WALLET || deployer.address;
    const ADMIN_OWNER = deployer.address;
    const BACKEND_SIGNER = process.env.BACKEND_SIGNER_ADDRESS || "0x188525c9810749a8012Dd1eBfA4a28801bac0a6e";
    const AUTO_FORWARD = false; // default false to hold funds until owner sweeps or sets true

    console.log("\nConfiguration:");
    console.log("• USDT Token Address:      ", USDT_ADDRESS);
    console.log("• Whitelisted Collector:   ", WHITELISTED_COLLECTOR);
    console.log("• Admin Owner:             ", ADMIN_OWNER);
    console.log("• Backend Signer:          ", BACKEND_SIGNER);
    console.log("• Auto-Forward on Deposit: ", AUTO_FORWARD);

    // 1. Deploy RoyalJackpotDeposit
    console.log("\n1️⃣ Deploying RoyalJackpotDeposit...");
    const DepositFactory = await ethers.getContractFactory("RoyalJackpotDeposit");
    const depositContract = await DepositFactory.deploy(
        USDT_ADDRESS,
        WHITELISTED_COLLECTOR,
        ADMIN_OWNER,
        AUTO_FORWARD
    );
    await depositContract.waitForDeployment();
    const depositAddress = await depositContract.getAddress();
    console.log("✅ RoyalJackpotDeposit deployed at:", depositAddress);

    // 2. Deploy RoyalJackpotVault
    console.log("\n2️⃣ Deploying RoyalJackpotVault...");
    const VaultFactory = await ethers.getContractFactory("RoyalJackpotVault");
    const vaultContract = await VaultFactory.deploy(
        USDT_ADDRESS,
        BACKEND_SIGNER,
        ADMIN_OWNER
    );
    await vaultContract.waitForDeployment();
    const vaultAddress = await vaultContract.getAddress();
    console.log("✅ RoyalJackpotVault deployed at:", vaultAddress);

    console.log("\n==================================================================");
    console.log("🎉 DEPLOYMENT SUMMARY FOR .env FILE:");
    console.log("==================================================================");
    console.log(`ROYAL_JACKPOT_DEPOSIT_ADDRESS=${depositAddress}`);
    console.log(`ROYAL_JACKPOT_VAULT_ADDRESS=${vaultAddress}`);
    console.log(`ROYAL_JACKPOT_COLLECTOR_WALLET=${WHITELISTED_COLLECTOR}`);
    console.log("==================================================================");
}

main().catch((error) => {
    console.error(error);
    process.exitCode = 1;
});
