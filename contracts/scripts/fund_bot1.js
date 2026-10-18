import hardhat from "hardhat";
const { ethers } = hardhat;
import dotenv from "dotenv";

dotenv.config();

async function main() {
    const [deployer] = await ethers.getSigners();
    const network = await ethers.provider.getNetwork();

    console.log("================================================================");
    console.log("   ⛽ FUNDING BOT1 WITH BNB GAS FOR 100 TRANSACTIONS");
    console.log("================================================================");
    console.log(`📡 Network: ${network.name}`);
    console.log(`👤 Deployer: ${deployer.address}`);

    const bot1Pk = process.env.bot1pvt.startsWith("0x") ? process.env.bot1pvt : "0x" + process.env.bot1pvt;
    const bot1Wallet = new ethers.Wallet(bot1Pk, ethers.provider);
    console.log(`🤖 Bot1 Address: ${bot1Wallet.address}`);

    const deployerBal = await ethers.provider.getBalance(deployer.address);
    const bot1Bal = await ethers.provider.getBalance(bot1Wallet.address);
    console.log(`💰 Deployer BNB: ${ethers.formatEther(deployerBal)}`);
    console.log(`💰 Bot1 BNB:     ${ethers.formatEther(bot1Bal)}`);

    if (bot1Bal < ethers.parseEther("0.02")) {
        const gasAmount = ethers.parseEther("0.03"); // 0.03 BNB
        console.log(`\n⏳ Sending ${ethers.formatEther(gasAmount)} BNB from Deployer to Bot1...`);
        const tx = await deployer.sendTransaction({
            to: bot1Wallet.address,
            value: gasAmount
        });
        console.log(`Tx submitted: ${tx.hash}`);
        await tx.wait();
        console.log("✅ Bot1 successfully funded with 0.03 BNB gas!\n");
    } else {
        console.log("✔ Bot1 already has sufficient BNB gas.\n");
    }

    const finalBotBal = await ethers.provider.getBalance(bot1Wallet.address);
    console.log(`🤖 Final Bot1 BNB Balance: ${ethers.formatEther(finalBotBal)} BNB`);
}

main()
    .then(() => process.exit(0))
    .catch((error) => {
        console.error("❌ Funding failed:", error);
        process.exit(1);
    });
