import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [caller] = await ethers.getSigners();
    const SPLITTER_ADDRESS = process.env.REVENUE_SPLITTER_ADDRESS || "0x027932cE4b00b4d89BC2209Df556cfdD7e72E822";
    const USDT_ADDRESS = process.env.USDT_TOKEN_ADDRESS || "0x55d398326f99059fF775485246999027B3197955";

    const usdt = await ethers.getContractAt("IERC20", USDT_ADDRESS);
    const balance = await usdt.balanceOf(SPLITTER_ADDRESS);

    if (balance === 0n) {
        console.log("No balance to distribute in RevenueSplitter.");
        return;
    }

    console.log(`Distributing ${ethers.formatEther(balance)} USDT from RevenueSplitter...`);
    const RevenueSplitter = await ethers.getContractAt("RevenueSplitter", SPLITTER_ADDRESS);
    const tx = await RevenueSplitter.distribute();
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
