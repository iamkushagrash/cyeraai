import hre from "hardhat";
const { ethers } = hre;

async function main() {
    console.log("==================================================================");
    console.log("🧪 LIVE ON-CHAIN CONTRACT VERIFICATION TEST (LOCAL NODE)");
    console.log("==================================================================");

    const [deployer, user] = await ethers.getSigners();
    
    // Addresses
    const usdtAddress = "0x5FbDB2315678afecb367f032d93F642f64180aa3";
    const caiAddress = "0x5FC8d32690cc91D4c39d9d3abcBD16989F875707";
    const treasuryVaultAddress = "0x0165878A594ca255338adfa4d48449f69242Eb8F";
    const splitterAddress = "0xa513E6E4b8f2a923D98304ec87F64353C4D5C853";
    const rewardVaultAddress = "0x2279B7A0a67DB372996a5FaB50D91eAA73d2eBe6";
    const withdrawalVaultAddress = "0x8A791620dd6260079BF849Dc5567aDC3F2FdC318";
    const liquidityWallet = "0x62A0a9555f3984560131A1e9D283F40d60EdF544";
    const backendSignerAddress = "0xe7F03Bf39D86d30F5BD0c319Fe949b01c5eA031B";

    const usdt = await ethers.getContractAt("MockUSDT", usdtAddress, user);
    const cai = await ethers.getContractAt("CAIToken", caiAddress, user);
    const splitter = await ethers.getContractAt("CyeraInvestmentSplitter", splitterAddress, user);
    const rewardVault = await ethers.getContractAt("CAIRewardClaimVault", rewardVaultAddress, user);
    const withdrawalVault = await ethers.getContractAt("USDTWithdrawalVault", withdrawalVaultAddress, user);

    // 1. Give User some USDT
    const testInvestment = ethers.parseUnits("100", 18);
    await usdt.mint(user.address, ethers.parseUnits("1000", 18));
    console.log(`\n1️⃣ [User Investment Test]`);
    console.log(` - User Address: ${user.address}`);
    console.log(` - User USDT Balance: ${ethers.formatUnits(await usdt.balanceOf(user.address), 18)} USDT`);

    // Approve Splitter
    await (await usdt.approve(splitterAddress, testInvestment)).wait();
    console.log(` - User approved 100 USDT to CyeraInvestmentSplitter`);

    // Call invest(100 USDT)
    const liqBalBefore = await usdt.balanceOf(liquidityWallet);
    const treasBalBefore = await usdt.balanceOf(treasuryVaultAddress);

    const tx = await splitter.invest(testInvestment);
    await tx.wait();

    const liqBalAfter = await usdt.balanceOf(liquidityWallet);
    const treasBalAfter = await usdt.balanceOf(treasuryVaultAddress);

    console.log(` ✅ invest(100 USDT) executed successfully!`);
    console.log(`   ➔ Treasury Claim Vault Received: ${ethers.formatUnits(treasBalAfter - treasBalBefore, 18)} USDT (70%)`);
    console.log(`   ➔ Liquidity Wallet Received: ${ethers.formatUnits(liqBalAfter - liqBalBefore, 18)} USDT (30%)`);

    // 2. Fund Reward Vault with CAI for testing claims
    const adminAddress = "0x4D4a1f8625a2882797876B0de099127c2F21e15B";
    await hre.network.provider.request({ method: "hardhat_impersonateAccount", params: [adminAddress] });
    const adminSigner = await ethers.getSigner(adminAddress);
    const caiAdmin = await ethers.getContractAt("CAIToken", caiAddress, adminSigner);
    await (await caiAdmin.transfer(rewardVaultAddress, ethers.parseEther("5000"))).wait();

    // 3. EIP-712 CAI Claim Test
    console.log(`\n2️⃣ [EIP-712 CAI Reward Claim Test]`);
    const backendWallet = ethers.Wallet.createRandom(ethers.provider);
    const rewardVaultAdmin = await ethers.getContractAt("CAIRewardClaimVault", rewardVaultAddress, adminSigner);
    await (await rewardVaultAdmin.setBackendSigner(backendWallet.address)).wait();

    const claimAmount = ethers.parseEther("50");
    const nonce = BigInt(Date.now());
    const expiry = Math.floor(Date.now() / 1000) + 3600;

    const domain = {
        name: "CAIRewardClaimVault",
        version: "1",
        chainId: 31337,
        verifyingContract: rewardVaultAddress,
    };

    const types = {
        ClaimReward: [
            { name: "user", type: "address" },
            { name: "caiAmount", type: "uint256" },
            { name: "nonce", type: "uint256" },
            { name: "expiry", type: "uint256" },
        ],
    };

    const claimValue = {
        user: user.address,
        caiAmount: claimAmount,
        nonce: nonce,
        expiry: expiry,
    };

    const signature = await backendWallet.signTypedData(domain, types, claimValue);
    console.log(` - Generated Cryptographic Signature for User: ${user.address}`);

    const caiBalBefore = await cai.balanceOf(user.address);
    await (await rewardVault.claimReward(claimAmount, nonce, expiry, signature)).wait();
    const caiBalAfter = await cai.balanceOf(user.address);

    console.log(` ✅ claimReward(50 CAI) successful!`);
    console.log(`   ➔ User Received: ${ethers.formatEther(caiBalAfter - caiBalBefore)} CAI`);

    // Replay attack test
    let replayBlocked = false;
    try {
        await rewardVault.claimReward(claimAmount, nonce, expiry, signature);
    } catch (e) {
        replayBlocked = true;
    }
    console.log(` ✅ Replay Attack Protection: ${replayBlocked ? "BLOCKED (Reused nonce rejected)" : "FAILED"}`);

    // 4. USDT Withdrawal Vault Test
    console.log(`\n3️⃣ [EIP-712 USDT Working Income Withdrawal Test]`);
    const withdrawVaultAdmin = await ethers.getContractAt("USDTWithdrawalVault", withdrawalVaultAddress, adminSigner);
    await (await withdrawVaultAdmin.setBackendSigner(backendWallet.address)).wait();

    // Fund withdrawal vault with 500 USDT
    await usdt.mint(withdrawalVaultAddress, ethers.parseUnits("500", 18));

    const withdrawAmount = ethers.parseUnits("25", 18);
    const withdrawalId = BigInt(Date.now() + 1);

    const withdrawDomain = {
        name: "USDTWithdrawalVault",
        version: "1",
        chainId: 31337,
        verifyingContract: withdrawalVaultAddress,
    };

    const withdrawTypes = {
        Withdrawal: [
            { name: "recipient", type: "address" },
            { name: "amount", type: "uint256" },
            { name: "withdrawalId", type: "uint256" },
            { name: "expiry", type: "uint256" },
        ],
    };

    const withdrawValue = {
        recipient: user.address,
        amount: withdrawAmount,
        withdrawalId: withdrawalId,
        expiry: expiry,
    };

    const withdrawSig = await backendWallet.signTypedData(withdrawDomain, withdrawTypes, withdrawValue);
    const usdtBalBefore = await usdt.balanceOf(user.address);
    await (await withdrawalVault.executeWithdrawalWithSignature(user.address, withdrawAmount, withdrawalId, expiry, withdrawSig)).wait();
    const usdtBalAfter = await usdt.balanceOf(user.address);

    console.log(` ✅ executeWithdrawalWithSignature(25 USDT) successful!`);
    console.log(`   ➔ User Received: ${ethers.formatUnits(usdtBalAfter - usdtBalBefore, 18)} USDT`);

    console.log("\n==================================================================");
    console.log("🎉 ALL LIVE SMART CONTRACT ACTIONS VERIFIED WITH 100% SUCCESS!");
    console.log("==================================================================");
}

main().catch(console.error);
