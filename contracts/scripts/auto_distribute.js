import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [caller] = await ethers.getSigners();
    const SPLITTER_ADDRESS = process.env.TREASURY_SPLITTER_ADDRESS || "0xcC3902345ad939df1C072E5D7fFD12d3d84c8Fc5";
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";

    const usdt = await ethers.getContractAt("IERC20", USDT_ADDRESS);
    const balance = await usdt.balanceOf(SPLITTER_ADDRESS);

    if (balance === 0n) {
        console.log("No balance to distribute in TreasurySplitter.");
        return;
    }

    console.log(`Distributing ${ethers.formatEther(balance)} USDT from TreasurySplitter...`);
    const TreasurySplitter = await ethers.getContractAt("TreasurySplitter", SPLITTER_ADDRESS);
    const tx = await TreasurySplitter.distribute();
    console.log(`Distribution Tx Hash: ${tx.hash}`);
    await tx.wait();
    console.log("Distributed successfully!");
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("Auto-distribute error:", error.message);
        process.exit(0); // Exit smoothly without breaking parent process
    });
