import hre from "hardhat";
const { ethers } = hre;

async function main() {
    const caiAddress = "0x5FC8d32690cc91D4c39d9d3abcBD16989F875707";
    const treasuryAddress = "0x00Bfd48cdC82D93b94e885efE7148166265Ee8c4";
    const adminAddress = "0x4D4a1f8625a2882797876B0de099127c2F21e15B";
    const rewardVaultAddress = "0x2279B7A0a67DB372996a5FaB50D91eAA73d2eBe6";

    // 1. Impersonate admin or use admin signer to withdraw from reward vault
    await hre.network.provider.request({
        method: "hardhat_impersonateAccount",
        params: [adminAddress],
    });
    const adminSigner = await ethers.getSigner(adminAddress);

    const rewardVault = await ethers.getContractAt("CAIRewardClaimVault", rewardVaultAddress, adminSigner);
    const caiToken = await ethers.getContractAt("CAIToken", caiAddress, adminSigner);

    const vaultBalance = await caiToken.balanceOf(rewardVaultAddress);
    if (vaultBalance > 0n) {
        console.log(`Withdrawing ${ethers.formatEther(vaultBalance)} CAI from Reward Vault to Owner...`);
        await (await rewardVault.emergencyWithdrawCAI(adminAddress, vaultBalance)).wait();
    }

    // 2. Transfer remaining CAI from Treasury to Admin Owner
    await hre.network.provider.request({
        method: "hardhat_impersonateAccount",
        params: [treasuryAddress],
    });
    const treasurySigner = await ethers.getSigner(treasuryAddress);
    const caiTokenTreasury = await ethers.getContractAt("CAIToken", caiAddress, treasurySigner);

    const treasuryBal = await caiTokenTreasury.balanceOf(treasuryAddress);
    if (treasuryBal > 0n) {
        console.log(`Transferring remaining ${ethers.formatEther(treasuryBal)} CAI from Treasury to Owner...`);
        await (await caiTokenTreasury.transfer(adminAddress, treasuryBal)).wait();
    }

    const finalAdminBal = await caiToken.balanceOf(adminAddress);
    const finalVaultBal = await caiToken.balanceOf(rewardVaultAddress);
    const finalTreasBal = await caiToken.balanceOf(treasuryAddress);

    console.log("\n=========================================");
    console.log("🎉 ALL CAI TRANSFERRED TO ADMIN OWNER WALLET!");
    console.log("=========================================");
    console.log("💎 Admin Owner Wallet Balance:", ethers.formatEther(finalAdminBal), "CAI");
    console.log("🏦 Reward Claim Vault Balance:", ethers.formatEther(finalVaultBal), "CAI");
    console.log("📦 Treasury Wallet Balance:", ethers.formatEther(finalTreasBal), "CAI");
    console.log("=========================================");
}

main().catch(console.error);
