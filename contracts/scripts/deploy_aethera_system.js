import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   🚀 CYERA AI - PROTOCOL V2 DEPLOYMENT (777,777 CAI + REVENUE SPLITTER)");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name} (Chain ID: ${network.chainId})`);
    console.log(`👤 Deployer: ${deployer.address}`);

    const balance = await ethers.provider.getBalance(deployer.address);
    console.log(`💰 Deployer Balance: ${ethers.formatEther(balance)} BNB\n`);

    // Addresses on BSC Mainnet
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";
    const PANCAKE_ROUTER = process.env.PANCAKE_ROUTER_ADDRESS || "0x10ED43C718714eb63d5aA57B78B54704E256024E";
    const BACKEND_SIGNER = process.env.BACKEND_SIGNER_ADDRESS || "0xEf537Abb8feD481D3C26d8ce0923bCcFD1f99a57";

    // Splitter Wallets (Fallback to deployer address if not yet set in .env)
    const TRADING_WALLET = process.env.TRADING_WALLET_ADDRESS || deployer.address;
    const RESERVE_WALLET = process.env.RESERVE_WALLET_ADDRESS || deployer.address;
    const partnerWallets = [
        process.env.PARTNER_1_WALLET || deployer.address,
        process.env.PARTNER_2_WALLET || deployer.address,
        process.env.PARTNER_3_WALLET || deployer.address,
        process.env.PARTNER_4_WALLET || deployer.address,
        process.env.PARTNER_5_WALLET || deployer.address,
    ];

    console.log(`📋 Protocol V2 Configuration Parameters:`);
    console.log(`   • USDT Token:        ${USDT_ADDRESS}`);
    console.log(`   • Pancake Router:    ${PANCAKE_ROUTER}`);
    console.log(`   • Backend Signer:    ${BACKEND_SIGNER}`);
    console.log(`   • Trading Wallet:    ${TRADING_WALLET} (20%)`);
    console.log(`   • Reserve Wallet:    ${RESERVE_WALLET} (2%)`);
    console.log(`   • Partner 1:         ${partnerWallets[0]} (2%)`);
    console.log(`   • Partner 2:         ${partnerWallets[1]} (2%)`);
    console.log(`   • Partner 3:         ${partnerWallets[2]} (2%)`);
    console.log(`   • Partner 4:         ${partnerWallets[3]} (2%)`);
    console.log(`   • Partner 5:         ${partnerWallets[4]} (2%)\n`);

    // 1. Deploy CAIToken (Fixed 777,777 Supply & Auto-Creates PancakePair)
    console.log("⏳ [1/4] Deploying CAIToken (Fixed 777,777 Supply & Auto Pancake Pair Creation)...");
    const CAIToken = await ethers.getContractFactory("contracts/CAIToken.sol:CAIToken");
    const caiToken = await CAIToken.deploy(USDT_ADDRESS, PANCAKE_ROUTER);
    await caiToken.waitForDeployment();
    const caiTokenAddress = await caiToken.getAddress();
    const pancakePairAddress = await caiToken.pancakePair();

    console.log(`   ✅ CAIToken Address:           ${caiTokenAddress}`);
    console.log(`   ✅ PancakeSwap V2 Pair:        ${pancakePairAddress}\n`);

    // 2. Deploy RevenueSplitter (32% total: 20% Trading + 10% [5 Partners @ 2%] + 2% Reserve)
    console.log("⏳ [2/4] Deploying RevenueSplitter (20% Trading + 5x2% Partners + 2% Reserve)...");
    const RevenueSplitter = await ethers.getContractFactory("contracts/RevenueSplitter.sol:RevenueSplitter");
    const revenueSplitter = await RevenueSplitter.deploy(
        USDT_ADDRESS,
        TRADING_WALLET,
        partnerWallets,
        RESERVE_WALLET,
        deployer.address
    );
    await revenueSplitter.waitForDeployment();
    const revenueSplitterAddress = await revenueSplitter.getAddress();

    console.log(`   ✅ RevenueSplitter Address:    ${revenueSplitterAddress}\n`);

    // 3. Deploy USDTWithdrawalVault (Working Incomes: Level & Rank Payout Vault - 38% auto-funded)
    console.log("⏳ [3/4] Deploying USDTWithdrawalVault (Working Incomes Vault with EIP-712 Signing)...");
    const USDTWithdrawalVault = await ethers.getContractFactory("contracts/USDTWithdrawalVault.sol:USDTWithdrawalVault");
    const usdtVault = await USDTWithdrawalVault.deploy(
        USDT_ADDRESS,
        BACKEND_SIGNER,
        deployer.address
    );
    await usdtVault.waitForDeployment();
    const usdtVaultAddress = await usdtVault.getAddress();

    console.log(`   ✅ USDTWithdrawalVault Address:${usdtVaultAddress}\n`);

    // 4. Deploy CyeraMiningEngine (30% Auto-Buy + 38% Vault + 32% RevenueSplitter)
    console.log("⏳ [4/4] Deploying CyeraMiningEngine (30% DEX Buy + 38% Vault + 32% Splitter)...");
    const CyeraMiningEngine = await ethers.getContractFactory("contracts/CyeraMiningEngine.sol:CyeraMiningEngine");
    const miningEngine = await CyeraMiningEngine.deploy(
        USDT_ADDRESS,
        caiTokenAddress,
        PANCAKE_ROUTER,
        usdtVaultAddress,
        revenueSplitterAddress,
        BACKEND_SIGNER,
        deployer.address
    );
    await miningEngine.waitForDeployment();
    const miningEngineAddress = await miningEngine.getAddress();

    console.log(`   ✅ CyeraMiningEngine Address:  ${miningEngineAddress}\n`);

    console.log("================================================================");
    console.log("   🎉 PROTOCOL V2 DEPLOYMENT COMPLETE! UPDATE YOUR .env FILE:");
    console.log("================================================================");
    console.log(`CAI_TOKEN_ADDRESS=${caiTokenAddress}`);
    console.log(`PANCAKESWAP_PAIR_ADDRESS=${pancakePairAddress}`);
    console.log(`CYERA_MINING_ENGINE_ADDRESS=${miningEngineAddress}`);
    console.log(`INVESTMENT_SPLITTER_ADDRESS=${miningEngineAddress}`);
    console.log(`USDT_WITHDRAWAL_VAULT_ADDRESS=${usdtVaultAddress}`);
    console.log(`REVENUE_SPLITTER_ADDRESS=${revenueSplitterAddress}`);
    console.log(`DEX_SLIPPAGE_TOLERANCE=0`);
    console.log("================================================================\n");

    console.log("📌 NEXT 4 CRITICAL STEPS TO GO LIVE:");
    console.log(`1. Add Initial Liquidity on PancakeSwap V2: 1,000 USDT + 2,000 CAI (Initial Price: $0.50 / CAI).`);
    console.log(`2. Fund Mining Engine Buffer: Transfer 30,000 to 50,000 CAI from deployer to ${miningEngineAddress}.`);
    console.log(`3. Renounce Token Ownership: Call setMiningContractAndRenounceOwnership(${miningEngineAddress}) on CAIToken.`);
    console.log(`   ➔ Result: 100% Green Score on DexScreener & GoPlus Security!`);
    console.log(`4. Update your .env with the deployed addresses above.\n`);
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Deployment failed:", error);
        process.exit(1);
    });
