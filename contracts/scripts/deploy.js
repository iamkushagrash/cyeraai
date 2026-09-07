import hre from "hardhat";
import dotenv from "dotenv";
dotenv.config();

const { ethers } = hre;

/**
 * @title CYERA (CAI) Mainnet / Testnet Production Deployment Script
 * @dev Deploys the entire 5-contract suite and connects them to the Admin Owner & Backend Signer.
 */
async function main() {
    console.log("==================================================================");
    console.log("🚀 Starting CYERA (CAI) Smart Contracts Suite Deployment...");
    console.log("==================================================================");

    const [deployer] = await ethers.getSigners();
    console.log("Deployer Address:", deployer.address);
    const balance = await ethers.provider.getBalance(deployer.address);
    console.log("Deployer Balance:", ethers.formatEther(balance), "BNB");

    // -------------------------------------------------------------
    // CONFIGURATION PARAMETERS (Configured with User's Specified Wallets)
    // -------------------------------------------------------------
    // 1. Admin / Owner Wallet Address (Has full administrative control via 2-Step Ownable)
    const ADMIN_OWNER_ADDRESS = process.env.ADMIN_OWNER_ADDRESS || "0x4D4a1f8625a2882797876B0de099127c2F21e15B";

    // 2. Main CAI Treasury Wallet (Receives initial 300,000 CAI supply)
    const CAI_TREASURY_WALLET = process.env.CAI_TREASURY_WALLET || "0x00Bfd48cdC82D93b94e885efE7148166265Ee8c4";

    // 3. Liquidity Treasury Wallet (Receives 30% USDT from investments)
    const LIQUIDITY_TREASURY_WALLET = process.env.LIQUIDITY_TREASURY_WALLET || "0x62A0a9555f3984560131A1e9D283F40d60EdF544";

    // 4. Dedicated Hot Backend Signer Key (Used exclusively for EIP-712 Claim Signatures)
    const BACKEND_SIGNER_ADDRESS = process.env.BACKEND_SIGNER_ADDRESS || "0xe7F03Bf39D86d30F5BD0c319Fe949b01c5eA031B";

    // 5. USDT Token Address
    let USDT_TOKEN_ADDRESS = process.env.USDT_TOKEN_ADDRESS;

    // If no USDT is provided, deploy a MockUSDT for Testnet use
    if (!USDT_TOKEN_ADDRESS) {
        console.log("\n⚠️ No USDT_TOKEN_ADDRESS specified. Deploying MockUSDT for Testnet...");
        const MockUSDT = await ethers.getContractFactory("MockUSDT");
        const mockUSDT = await MockUSDT.deploy("Tether USD", "USDT", 18);
        await mockUSDT.waitForDeployment();
        USDT_TOKEN_ADDRESS = await mockUSDT.getAddress();
        console.log("✅ MockUSDT deployed at:", USDT_TOKEN_ADDRESS);

        // Mint initial 1,000,000 USDT to Admin & Deployer for testing
        const mintAmount = ethers.parseUnits("1000000", 18);
        await mockUSDT.mint(deployer.address, mintAmount);
        await mockUSDT.mint(ADMIN_OWNER_ADDRESS, mintAmount);
        console.log("💸 Minted 1,000,000 Mock USDT to Deployer & Admin Owner for testing.");

        // If on local node and admin has low balance, fund native gas BNB
        const adminBal = await ethers.provider.getBalance(ADMIN_OWNER_ADDRESS);
        if (adminBal < ethers.parseEther("1")) {
            await deployer.sendTransaction({
                to: ADMIN_OWNER_ADDRESS,
                value: ethers.parseEther("50.0")
            });
            await deployer.sendTransaction({
                to: BACKEND_SIGNER_ADDRESS,
                value: ethers.parseEther("10.0")
            });
            console.log("⛽ Funded Admin Owner with 50 BNB & Backend Signer with 10 BNB for testing.");
        }
    }

    console.log("\n📋 Deployment Configuration:");
    console.log(" - Admin Owner Wallet:", ADMIN_OWNER_ADDRESS);
    console.log(" - CAI Treasury Wallet:", CAI_TREASURY_WALLET);
    console.log(" - Liquidity Treasury Wallet (30%):", LIQUIDITY_TREASURY_WALLET);
    console.log(" - Backend Signer (EIP-712):", BACKEND_SIGNER_ADDRESS);
    console.log(" - USDT Token Contract:", USDT_TOKEN_ADDRESS);

    // -------------------------------------------------------------
    // 1. Deploy CAIToken (Fixed 300,000 CAI)
    // -------------------------------------------------------------
    console.log("\n1️⃣ Deploying CAIToken.sol...");
    const CAIToken = await ethers.getContractFactory("CAIToken");
    const caiToken = await CAIToken.deploy(CAI_TREASURY_WALLET, ADMIN_OWNER_ADDRESS);
    await caiToken.waitForDeployment();
    const caiTokenAddress = await caiToken.getAddress();
    console.log("✅ CAIToken deployed at:", caiTokenAddress);

    // -------------------------------------------------------------
    // 2. Deploy TreasuryClaimVault (Holds 70% USDT)
    // -------------------------------------------------------------
    console.log("\n2️⃣ Deploying TreasuryClaimVault.sol...");
    const TreasuryClaimVault = await ethers.getContractFactory("TreasuryClaimVault");
    const treasuryVault = await TreasuryClaimVault.deploy(USDT_TOKEN_ADDRESS, ADMIN_OWNER_ADDRESS);
    await treasuryVault.waitForDeployment();
    const treasuryVaultAddress = await treasuryVault.getAddress();
    console.log("✅ TreasuryClaimVault deployed at:", treasuryVaultAddress);

    // -------------------------------------------------------------
    // 3. Deploy CyeraInvestmentSplitter (70% Vault / 30% Liquidity)
    // -------------------------------------------------------------
    console.log("\n3️⃣ Deploying CyeraInvestmentSplitter.sol...");
    const CyeraInvestmentSplitter = await ethers.getContractFactory("CyeraInvestmentSplitter");
    const splitter = await CyeraInvestmentSplitter.deploy(
        USDT_TOKEN_ADDRESS,
        treasuryVaultAddress,
        LIQUIDITY_TREASURY_WALLET,
        ADMIN_OWNER_ADDRESS
    );
    await splitter.waitForDeployment();
    const splitterAddress = await splitter.getAddress();
    console.log("✅ CyeraInvestmentSplitter deployed at:", splitterAddress);

    // -------------------------------------------------------------
    // 4. Deploy CAIRewardClaimVault (EIP-712 CAI ROI Claims)
    // -------------------------------------------------------------
    console.log("\n4️⃣ Deploying CAIRewardClaimVault.sol...");
    const CAIRewardClaimVault = await ethers.getContractFactory("CAIRewardClaimVault");
    const rewardVault = await CAIRewardClaimVault.deploy(
        caiTokenAddress,
        BACKEND_SIGNER_ADDRESS,
        ADMIN_OWNER_ADDRESS
    );
    await rewardVault.waitForDeployment();
    const rewardVaultAddress = await rewardVault.getAddress();
    console.log("✅ CAIRewardClaimVault deployed at:", rewardVaultAddress);

    // -------------------------------------------------------------
    // 5. Deploy USDTWithdrawalVault (EIP-712 Working Income USDT Payouts)
    // -------------------------------------------------------------
    console.log("\n5️⃣ Deploying USDTWithdrawalVault.sol...");
    const USDTWithdrawalVault = await ethers.getContractFactory("USDTWithdrawalVault");
    const withdrawalVault = await USDTWithdrawalVault.deploy(
        USDT_TOKEN_ADDRESS,
        BACKEND_SIGNER_ADDRESS,
        ADMIN_OWNER_ADDRESS
    );
    await withdrawalVault.waitForDeployment();
    const withdrawalVaultAddress = await withdrawalVault.getAddress();
    console.log("✅ USDTWithdrawalVault deployed at:", withdrawalVaultAddress);

    console.log("\n==================================================================");
    console.log("🎉 ALL CONTRACTS DEPLOYED SUCCESSFULLY!");
    console.log("==================================================================");
    console.log("Save these addresses in your .env & backend configuration:");
    console.log("------------------------------------------------------------------");
    console.log(`USDT_TOKEN_ADDRESS="${USDT_TOKEN_ADDRESS}"`);
    console.log(`CAI_TOKEN_ADDRESS="${caiTokenAddress}"`);
    console.log(`TREASURY_CLAIM_VAULT_ADDRESS="${treasuryVaultAddress}"`);
    console.log(`INVESTMENT_SPLITTER_ADDRESS="${splitterAddress}"`);
    console.log(`CAI_REWARD_CLAIM_VAULT_ADDRESS="${rewardVaultAddress}"`);
    console.log(`USDT_WITHDRAWAL_VAULT_ADDRESS="${withdrawalVaultAddress}"`);
    console.log("==================================================================");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error(error);
        process.exit(1);
    });
