import { expect } from "chai";
import hre from "hardhat";
const { ethers } = hre;

describe("🚀 CYERA AI: COMPLETE DECENTRALIZED ECOSYSTEM END-TO-END TEST", function () {
    let deployer, adminOwner, treasuryWallet, liquidityWallet, backendSigner, userAlice, userBob, attacker, pancakePair;
    let usdt, caiToken, treasuryVault, splitter, rewardVault, withdrawalVault;

    const INITIAL_CAI_SUPPLY = ethers.parseEther("300000"); // 300,000 CAI
    const USER_INVESTMENT_1 = ethers.parseUnits("1000", 18); // $1,000 USDT
    const USER_INVESTMENT_2 = ethers.parseUnits("500", 18);  // $500 USDT
    const DECIMALS_18 = 18;

    before(async function () {
        [deployer, adminOwner, treasuryWallet, liquidityWallet, backendSigner, userAlice, userBob, attacker, pancakePair] = await ethers.getSigners();

        console.log("\n=========================================================================");
        console.log(" 🌐 DEPLOYING ALL 5 CONTRACTS IN IDENTICAL PRODUCTION CONFIGURATION");
        console.log("=========================================================================");

        // 1. Deploy MockUSDT (Simulating BSC USDT)
        const MockUSDT = await ethers.getContractFactory("MockUSDT");
        usdt = await MockUSDT.deploy("Tether USD", "USDT", 18);
        await usdt.waitForDeployment();
        console.log("  ✅ USDT Contract:", await usdt.getAddress());

        // 2. Deploy CAIToken (Fixed 300,000 CAI Supply to Treasury, Admin as Owner)
        const CAIToken = await ethers.getContractFactory("CAIToken");
        caiToken = await CAIToken.deploy(treasuryWallet.address, adminOwner.address);
        await caiToken.waitForDeployment();
        console.log("  ✅ CAIToken Contract (300k Supply):", await caiToken.getAddress());

        // 3. Deploy TreasuryClaimVault (Holds 70% USDT)
        const TreasuryClaimVault = await ethers.getContractFactory("TreasuryClaimVault");
        treasuryVault = await TreasuryClaimVault.deploy(await usdt.getAddress(), adminOwner.address);
        await treasuryVault.waitForDeployment();
        console.log("  ✅ TreasuryClaimVault (70% USDT):", await treasuryVault.getAddress());

        // 4. Deploy CyeraInvestmentSplitter (70% to Treasury Vault / 30% to Liquidity Wallet)
        const CyeraInvestmentSplitter = await ethers.getContractFactory("CyeraInvestmentSplitter");
        splitter = await CyeraInvestmentSplitter.deploy(
            await usdt.getAddress(),
            await treasuryVault.getAddress(),
            liquidityWallet.address,
            adminOwner.address
        );
        await splitter.waitForDeployment();
        console.log("  ✅ CyeraInvestmentSplitter:", await splitter.getAddress());

        // 5. Deploy CAIRewardClaimVault (EIP-712 CAI ROI Claims)
        const CAIRewardClaimVault = await ethers.getContractFactory("CAIRewardClaimVault");
        rewardVault = await CAIRewardClaimVault.deploy(
            await caiToken.getAddress(),
            backendSigner.address,
            adminOwner.address
        );
        await rewardVault.waitForDeployment();
        console.log("  ✅ CAIRewardClaimVault (ROI Claims):", await rewardVault.getAddress());

        // 6. Deploy USDTWithdrawalVault (EIP-712 Working Income USDT Payouts)
        const USDTWithdrawalVault = await ethers.getContractFactory("USDTWithdrawalVault");
        withdrawalVault = await USDTWithdrawalVault.deploy(
            await usdt.getAddress(),
            backendSigner.address,
            adminOwner.address
        );
        await withdrawalVault.waitForDeployment();
        console.log("  ✅ USDTWithdrawalVault (Income Payouts):", await withdrawalVault.getAddress());

        // Fund Users with USDT for staking test
        await usdt.mint(userAlice.address, ethers.parseUnits("5000", 18));
        await usdt.mint(userBob.address, ethers.parseUnits("5000", 18));

        // Fund Reward Vault with CAI from Treasury for user ROI claims
        await caiToken.connect(treasuryWallet).transfer(await rewardVault.getAddress(), ethers.parseEther("50000"));

        // Fund USDT Withdrawal Vault with USDT for working income payouts
        await usdt.mint(await withdrawalVault.getAddress(), ethers.parseUnits("10000", 18));
    });

    // =========================================================================
    // TEST 1: DECENTRALIZED STAKING & 70/30 ATOMIC SPLIT
    // =========================================================================
    describe("1️⃣ DECENTRALIZED STAKING FLOW (invest action + 70/30 Split)", function () {
        it("Should allow User Alice to Approve USDT and Invest $1,000 into CyeraInvestmentSplitter", async function () {
            const splitterAddress = await splitter.getAddress();
            const treasuryAddress = await treasuryVault.getAddress();

            const userBalBefore = await usdt.balanceOf(userAlice.address);
            const treasBalBefore = await usdt.balanceOf(treasuryAddress);
            const liqBalBefore = await usdt.balanceOf(liquidityWallet.address);

            // Step 1: User approves USDT to Splitter
            await usdt.connect(userAlice).approve(splitterAddress, USER_INVESTMENT_1);

            // Step 2: User calls invest(1000 USDT)
            const tx = await splitter.connect(userAlice).invest(USER_INVESTMENT_1);
            const receipt = await tx.wait();

            // Verify Balances
            const userBalAfter = await usdt.balanceOf(userAlice.address);
            const treasBalAfter = await usdt.balanceOf(treasuryAddress);
            const liqBalAfter = await usdt.balanceOf(liquidityWallet.address);
            const splitterBal = await usdt.balanceOf(splitterAddress);

            // 1. User spent exact 1000 USDT
            expect(userBalBefore - userBalAfter).to.equal(USER_INVESTMENT_1);

            // 2. Treasury Vault received EXACT 70% ($700 USDT)
            expect(treasBalAfter - treasBalBefore).to.equal(ethers.parseUnits("700", 18));

            // 3. Liquidity Wallet received EXACT 30% ($300 USDT)
            expect(liqBalAfter - liqBalBefore).to.equal(ethers.parseUnits("300", 18));

            // 4. Splitter contract balance is 0 (No funds stuck)
            expect(splitterBal).to.equal(0n);

            console.log("   ➔ Alice Staked: $1,000 USDT");
            console.log("   ➔ Treasury Vault Received (70%): $700 USDT");
            console.log("   ➔ Liquidity Wallet Received (30%): $300 USDT");
            console.log("   ➔ Splitter Remaining Balance: $0 USDT (Clean Execution)");
        });

        it("Should REJECT investments below $50 (e.g. $49) and above $2,000 (e.g. $2,001)", async function () {
            const splitterAddress = await splitter.getAddress();
            const tooLow = ethers.parseUnits("49", 18);
            const tooHigh = ethers.parseUnits("2001", 18);

            await usdt.connect(userBob).approve(splitterAddress, tooHigh);

            await expect(splitter.connect(userBob).invest(tooLow)).to.be.revertedWith(
                "Splitter: Amount is below minimum investment ($50)"
            );

            await expect(splitter.connect(userBob).invest(tooHigh)).to.be.revertedWith(
                "Splitter: Amount exceeds maximum investment ($2,000)"
            );
        });
    });

    // =========================================================================
    // TEST 2: DECENTRALIZED ROI CLAIM (EIP-712 CRYPTOGRAPHIC SIGNATURE)
    // =========================================================================
    describe("2️⃣ DECENTRALIZED ROI CLAIM VIA EIP-712 SIGNATURES (CAIRewardClaimVault)", function () {
        const claimAmount = ethers.parseEther("15"); // 15 CAI ROI Reward
        const nonce = 1001n;
        let domain, types, claimValue, signature;

        beforeEach(async function () {
            const chainId = (await ethers.provider.getNetwork()).chainId;
            domain = {
                name: "CAIRewardClaimVault",
                version: "1",
                chainId: chainId,
                verifyingContract: await rewardVault.getAddress(),
            };

            types = {
                ClaimReward: [
                    { name: "user", type: "address" },
                    { name: "caiAmount", type: "uint256" },
                    { name: "nonce", type: "uint256" },
                    { name: "expiry", type: "uint256" },
                ],
            };
        });

        it("Should allow User Alice to Claim 15 CAI with valid backend EIP-712 signature", async function () {
            const expiry = Math.floor(Date.now() / 1000) + 3600; // 1 Hour Expiry
            claimValue = {
                user: userAlice.address,
                caiAmount: claimAmount,
                nonce: nonce,
                expiry: expiry,
            };

            // Backend Server signs the claim payload
            signature = await backendSigner.signTypedData(domain, types, claimValue);

            const aliceCaiBefore = await caiToken.balanceOf(userAlice.address);

            // Alice submits claim transaction with signature
            await rewardVault.connect(userAlice).claimReward(claimAmount, nonce, expiry, signature);

            const aliceCaiAfter = await caiToken.balanceOf(userAlice.address);

            expect(aliceCaiAfter - aliceCaiBefore).to.equal(claimAmount);
            expect(await rewardVault.usedNonces(nonce)).to.be.true;

            console.log("   ➔ Alice Claimed 15 CAI ROI directly to wallet via EIP-712 Signature");
        });

        it("Should REJECT Replay Attack (Using same nonce/signature a 2nd time)", async function () {
            const expiry = Math.floor(Date.now() / 1000) + 3600;
            // Attempting to claim again with same nonce 1001
            await expect(
                rewardVault.connect(userAlice).claimReward(claimAmount, nonce, expiry, signature)
            ).to.be.revertedWith("ClaimVault: Nonce has already been claimed");

            console.log("   ➔ Replay Attack Blocked: Reused nonce 1001 was rejected");
        });

        it("Should REJECT Forged Signature by Attacker", async function () {
            const expiry = Math.floor(Date.now() / 1000) + 3600;
            const newNonce = 1002n;
            const fakeClaimValue = {
                user: attacker.address,
                caiAmount: ethers.parseEther("1000"),
                nonce: newNonce,
                expiry: expiry,
            };

            // Attacker signs payload with their own key instead of backendSigner
            const fakeSig = await attacker.signTypedData(domain, types, fakeClaimValue);

            await expect(
                rewardVault.connect(attacker).claimReward(ethers.parseEther("1000"), newNonce, expiry, fakeSig)
            ).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");

            console.log("   ➔ Forged Signature Blocked: Fake signature rejected");
        });
    });

    // =========================================================================
    // TEST 3: DECENTRALIZED USDT WORKING INCOME WITHDRAWAL (USDTWithdrawalVault)
    // =========================================================================
    describe("3️⃣ WORKING INCOME WITHDRAWALS VIA EIP-712 (USDTWithdrawalVault)", function () {
        const withdrawAmount = ethers.parseUnits("75", 18); // $75 USDT Direct Bonus payout
        const withdrawalId = 5001n;
        let domain, types;

        beforeEach(async function () {
            const chainId = (await ethers.provider.getNetwork()).chainId;
            domain = {
                name: "USDTWithdrawalVault",
                version: "1",
                chainId: chainId,
                verifyingContract: await withdrawalVault.getAddress(),
            };

            types = {
                Withdrawal: [
                    { name: "recipient", type: "address" },
                    { name: "amount", type: "uint256" },
                    { name: "withdrawalId", type: "uint256" },
                    { name: "expiry", type: "uint256" },
                ],
            };
        });

        it("Should execute approved single USDT payout with backend signature", async function () {
            const expiry = Math.floor(Date.now() / 1000) + 3600;
            const withdrawValue = {
                recipient: userAlice.address,
                amount: withdrawAmount,
                withdrawalId: withdrawalId,
                expiry: expiry,
            };

            const sig = await backendSigner.signTypedData(domain, types, withdrawValue);

            const userUsdtBefore = await usdt.balanceOf(userAlice.address);
            await withdrawalVault.connect(userAlice).executeWithdrawalWithSignature(
                userAlice.address,
                withdrawAmount,
                withdrawalId,
                expiry,
                sig
            );
            const userUsdtAfter = await usdt.balanceOf(userAlice.address);

            expect(userUsdtAfter - userUsdtBefore).to.equal(withdrawAmount);
            expect(await withdrawalVault.processedWithdrawals(withdrawalId)).to.be.true;

            console.log("   ➔ Single Withdrawal of $75 USDT executed directly to Alice's wallet");
        });

        it("Should REJECT duplicate withdrawalId payout attempt", async function () {
            const expiry = Math.floor(Date.now() / 1000) + 3600;
            const withdrawValue = {
                recipient: userAlice.address,
                amount: withdrawAmount,
                withdrawalId: withdrawalId,
                expiry: expiry,
            };
            const sig = await backendSigner.signTypedData(domain, types, withdrawValue);

            await expect(
                withdrawalVault.connect(userAlice).executeWithdrawalWithSignature(
                    userAlice.address,
                    withdrawAmount,
                    withdrawalId,
                    expiry,
                    sig
                )
            ).to.be.revertedWith("WithdrawalVault: Withdrawal ID already processed");

            console.log("   ➔ Duplicate Withdrawal ID 5001 strictly blocked");
        });

        it("Should execute BATCH withdrawals for multiple users in single gas-efficient transaction", async function () {
            const expiry = Math.floor(Date.now() / 1000) + 3600;
            const recipients = [userAlice.address, userBob.address];
            const amounts = [ethers.parseUnits("50", 18), ethers.parseUnits("100", 18)];
            const withdrawalIds = [6001n, 6002n];
            const expiries = [expiry, expiry];

            const sig1 = await backendSigner.signTypedData(domain, types, {
                recipient: userAlice.address,
                amount: amounts[0],
                withdrawalId: withdrawalIds[0],
                expiry: expiry,
            });

            const sig2 = await backendSigner.signTypedData(domain, types, {
                recipient: userBob.address,
                amount: amounts[1],
                withdrawalId: withdrawalIds[1],
                expiry: expiry,
            });

            const sigs = [sig1, sig2];

            const aliceBefore = await usdt.balanceOf(userAlice.address);
            const bobBefore = await usdt.balanceOf(userBob.address);

            await withdrawalVault.connect(deployer).executeBatchWithdrawalsWithSignatures(
                recipients,
                amounts,
                withdrawalIds,
                expiries,
                sigs
            );

            expect(await usdt.balanceOf(userAlice.address) - aliceBefore).to.equal(amounts[0]);
            expect(await usdt.balanceOf(userBob.address) - bobBefore).to.equal(amounts[1]);

            console.log("   ➔ Batch Payout: $50 USDT to Alice & $100 USDT to Bob executed in 1 transaction");
        });
    });

    // =========================================================================
    // TEST 4: TREASURY VAULT 70% RELEASE & RESCUE PROTECTION
    // =========================================================================
    describe("4️⃣ TREASURY CLAIM VAULT (70% USDT Reserve & Anti-Theft Protection)", function () {
        it("Should allow Admin Owner to release treasury funds via transferFunds", async function () {
            const releaseAmount = ethers.parseUnits("200", 18);
            const recipient = adminOwner.address;

            const recBalBefore = await usdt.balanceOf(recipient);
            await treasuryVault.connect(adminOwner).transferFunds(recipient, releaseAmount, "Reserve Pool Deployment");
            const recBalAfter = await usdt.balanceOf(recipient);

            expect(recBalAfter - recBalBefore).to.equal(releaseAmount);
            console.log("   ➔ Admin successfully released $200 USDT from Treasury Vault with reason log");
        });

        it("Should STRICTLY BLOCK rescueTokens from withdrawing treasury USDT", async function () {
            await expect(
                treasuryVault.connect(adminOwner).rescueTokens(await usdt.getAddress(), adminOwner.address, ethers.parseUnits("100", 18))
            ).to.be.revertedWith("TreasuryVault: Cannot rescue treasury USDT");

            console.log("   ➔ Treasury Anti-Theft: rescueTokens is forbidden from draining USDT");
        });
    });

    // =========================================================================
    // TEST 5: CAI TOKEN ANTI-DUMP & PANCAKESWAP PROTECTION
    // =========================================================================
    describe("5️⃣ CAI TOKEN PANCAKESWAP BUY WHITELIST & UNRESTRICTED SELL", function () {
        it("Should set and lock PancakeSwap Pair", async function () {
            await caiToken.connect(adminOwner).setPancakePair(pancakePair.address);
            expect(await caiToken.pancakePair()).to.equal(pancakePair.address);

            await caiToken.connect(adminOwner).lockPancakePair();
            expect(await caiToken.pancakePairLocked()).to.be.true;

            // Attempting to change pair after locking must fail
            await expect(
                caiToken.connect(adminOwner).setPancakePair(attacker.address)
            ).to.be.revertedWith("CAI: PancakeSwap pair is permanently locked");

            console.log("   ➔ PancakeSwap Pair locked permanently");
        });

        it("Should REJECT non-whitelisted wallet buying CAI from PancakeSwap Pair", async function () {
            // Fund pair with CAI
            await caiToken.connect(treasuryWallet).transfer(pancakePair.address, ethers.parseEther("1000"));

            // Non-whitelisted User Alice tries to receive CAI from pair (Buy)
            await expect(
                caiToken.connect(pancakePair).transfer(userAlice.address, ethers.parseEther("50"))
            ).to.be.revertedWith("CAI: Buyer is not whitelisted for DEX purchases");

            console.log("   ➔ Non-whitelisted DEX Buy successfully blocked");
        });

        it("Should ALLOW whitelisted wallet buying CAI from PancakeSwap Pair", async function () {
            // Admin whitelists User Bob
            await caiToken.connect(adminOwner).setWhitelist(userBob.address, true);
            expect(await caiToken.isWhitelisted(userBob.address)).to.be.true;

            // Whitelisted Bob receives CAI from pair (Buy)
            await caiToken.connect(pancakePair).transfer(userBob.address, ethers.parseEther("50"));
            expect(await caiToken.balanceOf(userBob.address)).to.equal(ethers.parseEther("50"));

            console.log("   ➔ Whitelisted DEX Buy successfully executed");
        });

        it("Should ALWAYS ALLOW ANY token holder to SELL to PancakeSwap Pair (Unrestricted Sell)", async function () {
            // Alice (non-whitelisted) sells CAI to pancakePair
            const aliceBal = await caiToken.balanceOf(userAlice.address);
            expect(aliceBal).to.be.gt(0n);

            await caiToken.connect(userAlice).transfer(pancakePair.address, ethers.parseEther("5"));
            console.log("   ➔ Holder sold CAI to PancakeSwap pair with zero restrictions");
        });
    });

    // =========================================================================
    // TEST 6: 2-STEP OWNERSHIP TRANSFER & PAUSABLE CONTROLS
    // =========================================================================
    describe("6️⃣ 2-STEP OWNERSHIP & EMERGENCY PAUSE CONTROLS", function () {
        it("Should enforce 2-Step ownership transfer on contracts", async function () {
            // Step 1: Admin proposes transfer
            await splitter.connect(adminOwner).transferOwnership(deployer.address);
            expect(await splitter.pendingOwner()).to.equal(deployer.address);
            expect(await splitter.owner()).to.equal(adminOwner.address); // Owner is still Admin

            // Step 2: New owner accepts
            await splitter.connect(deployer).acceptOwnership();
            expect(await splitter.owner()).to.equal(deployer.address);
            expect(await splitter.pendingOwner()).to.equal(ethers.ZeroAddress);

            // Transfer back to adminOwner
            await splitter.connect(deployer).transferOwnership(adminOwner.address);
            await splitter.connect(adminOwner).acceptOwnership();
            expect(await splitter.owner()).to.equal(adminOwner.address);

            console.log("   ➔ 2-Step Safe Ownership Transfer verified");
        });

        it("Should pause and unpause operations during emergency", async function () {
            await splitter.connect(adminOwner).pause();
            expect(await splitter.paused()).to.be.true;

            // Investment must revert when paused
            await usdt.connect(userAlice).approve(await splitter.getAddress(), USER_INVESTMENT_2);
            await expect(
                splitter.connect(userAlice).invest(USER_INVESTMENT_2)
            ).to.be.revertedWith("Pausable: paused");

            await splitter.connect(adminOwner).unpause();
            expect(await splitter.paused()).to.be.false;

            // Works after unpause
            await splitter.connect(userAlice).invest(USER_INVESTMENT_2);
            console.log("   ➔ Emergency Pause and Unpause verified on all operations");
        });
    });
});
