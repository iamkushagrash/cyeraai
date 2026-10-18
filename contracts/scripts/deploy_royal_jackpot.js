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
    const WHITELISTED_COLLECTOR = process.env.ROYAL_JACKPOT_COLLECTOR_WALLET || "0xBb83f1dbc26f6a414350E3EaBB5F69E28Bfef3d9";
    const ADMIN_OWNER = "0xBb83f1dbc26f6a414350E3EaBB5F69E28Bfef3d9";
    const BACKEND_SIGNER = process.env.BACKEND_SIGNER_ADDRESS || "0xEf537Abb8feD481D3C26d8ce0923bCcFD1f99a57";
    const AUTO_FORWARD = true; // Auto-forwards all ticket deposits immediately to collector wallet!

    console.log("\nConfiguration:");
    console.log("• USDT Token Address:      ", USDT_ADDRESS);
    console.log("• Whitelisted Collector:   ", WHITELISTED_COLLECTOR);
    console.log("• Admin Owner:             ", ADMIN_OWNER);
    console.log("• Backend Signer:          ", BACKEND_SIGNER);
    console.log("• Auto-Forward on Deposit: ", AUTO_FORWARD);

    // 1. Deploy RoyalJackpotDeposit
    console.log("\n1️⃣ Deploying RoyalJackpotDeposit...");
    const DepositFactory = await ethers.getContractFactory("contracts/RoyalJackpotDeposit.sol:RoyalJackpotDeposit");
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
    const VaultFactory = await ethers.getContractFactory("contracts/RoyalJackpotVault.sol:RoyalJackpotVault");
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
