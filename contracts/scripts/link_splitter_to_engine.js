import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    console.log("👤 Executing Wallet:", deployer.address);

    const MINING_ENGINE_ADDRESS = process.env.CYERA_MINING_ENGINE_ADDRESS || "0xdd905468F6F91f8c37eFB9e27E1f282734E60217";
    const SPLITTER_ADDRESS = "0xcC3902345ad939df1C072E5D7fFD12d3d84c8Fc5";

    console.log(`⏳ Setting Treasury Wallet on Mining Engine (${MINING_ENGINE_ADDRESS}) to Splitter (${SPLITTER_ADDRESS})...`);

    const CyeraMiningEngine = await ethers.getContractAt("contracts/CyeraMiningEngine.sol:CyeraMiningEngine", MINING_ENGINE_ADDRESS);
    
    const currentTreasury = await CyeraMiningEngine.treasuryWallet();
    console.log(`Current Treasury on Mining Engine: ${currentTreasury}`);

    if (currentTreasury.toLowerCase() === SPLITTER_ADDRESS.toLowerCase()) {
        console.log("✅ Already set to Splitter!");
        return;
    }

    const tx = await CyeraMiningEngine.setTreasuryWallet(SPLITTER_ADDRESS);
    console.log(`📡 Transaction Hash: ${tx.hash}`);
    await tx.wait();

    const updatedTreasury = await CyeraMiningEngine.treasuryWallet();
    console.log(`🎉 SUCCESS! Updated Treasury on Mining Engine is now: ${updatedTreasury}`);
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Failed to update treasury:", error);
        process.exit(1);
    });
