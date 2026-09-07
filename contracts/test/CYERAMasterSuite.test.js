import { expect } from "chai";
import hre from "hardhat";
const { ethers } = hre;

describe("CYERA (CAI) — COMPLETE MASTER TEST SUITE", function () {
    let owner, multisig, treasury, liquidityWallet, backendSigner, newSigner, attacker, user1, user2, user3;
    let mockUSDT, randomToken, caiToken, splitter, treasuryVault, rewardVault, withdrawalVault;

    const TOTAL_SUPPLY = ethers.parseEther("300000"); // 300,000 CAI

    beforeEach(async function () {
        [owner, multisig, treasury, liquidityWallet, backendSigner, newSigner, attacker, user1, user2, user3] = await ethers.getSigners();

        // 1. Deploy Mock USDT (18 decimals)
        const MockERC20 = await ethers.getContractFactory("MockUSDT");
        mockUSDT = await MockERC20.deploy("Tether USD", "USDT", 18);
        await mockUSDT.waitForDeployment();

        // Deploy Random ERC20 for rescue testing
        randomToken = await MockERC20.deploy("Random Token", "RND", 18);
        await randomToken.waitForDeployment();

        // 2. Deploy CAIToken
        const CAITokenFactory = await ethers.getContractFactory("CAIToken");
        caiToken = await CAITokenFactory.deploy(treasury.address, multisig.address);
        await caiToken.waitForDeployment();

        // 3. Deploy TreasuryClaimVault
        const TreasuryFactory = await ethers.getContractFactory("TreasuryClaimVault");
        treasuryVault = await TreasuryFactory.deploy(await mockUSDT.getAddress(), multisig.address);
        await treasuryVault.waitForDeployment();

        // 4. Deploy CyeraInvestmentSplitter
        const SplitterFactory = await ethers.getContractFactory("CyeraInvestmentSplitter");
        splitter = await SplitterFactory.deploy(
            await mockUSDT.getAddress(),
            await treasuryVault.getAddress(),
            liquidityWallet.address,
            multisig.address
        );
        await splitter.waitForDeployment();

        // 5. Deploy CAIRewardClaimVault
        const RewardVaultFactory = await ethers.getContractFactory("CAIRewardClaimVault");
        rewardVault = await RewardVaultFactory.deploy(
            await caiToken.getAddress(),
            backendSigner.address,
            multisig.address
        );
        await rewardVault.waitForDeployment();

        // 6. Deploy USDTWithdrawalVault
        const WithdrawalVaultFactory = await ethers.getContractFactory("USDTWithdrawalVault");
        withdrawalVault = await WithdrawalVaultFactory.deploy(
            await mockUSDT.getAddress(),
            backendSigner.address,
            multisig.address
        );
        await withdrawalVault.waitForDeployment();

        // Fund Reward Vault with CAI from Treasury
        await caiToken.connect(treasury).transfer(await rewardVault.getAddress(), ethers.parseEther("50000"));

        // Fund Withdrawal Vault with USDT
        await mockUSDT.mint(await withdrawalVault.getAddress(), ethers.parseEther("50000"));
    });

    // =========================================================================
    // 1. CAITOKEN TESTS
    // =========================================================================
    describe("1. CAIToken Tests", function () {
        it("Deployment: should set correct metadata and total supply", async function () {
            expect(await caiToken.name()).to.equal("Cyera");
            expect(await caiToken.symbol()).to.equal("CAI");
            expect(await caiToken.decimals()).to.equal(18);
            expect(await caiToken.totalSupply()).to.equal(TOTAL_SUPPLY);
            expect(await caiToken.balanceOf(treasury.address)).to.equal(TOTAL_SUPPLY - ethers.parseEther("50000"));
            expect(await caiToken.balanceOf(await rewardVault.getAddress())).to.equal(ethers.parseEther("50000"));
        });

        it("Deployment: should revert on zero address treasury or owner", async function () {
            const CAITokenFactory = await ethers.getContractFactory("CAIToken");
            await expect(CAITokenFactory.deploy(ethers.ZeroAddress, multisig.address)).to.be.revertedWith("CAI: Treasury wallet cannot be zero address");
            await expect(CAITokenFactory.deploy(treasury.address, ethers.ZeroAddress)).to.be.revertedWith("Ownable: initial owner is the zero address");
        });

        it("Basic ERC20: transfer, approve, allowance, transferFrom", async function () {
            // Transfer
            await caiToken.connect(treasury).transfer(user1.address, ethers.parseEther("100"));
            expect(await caiToken.balanceOf(user1.address)).to.equal(ethers.parseEther("100"));

            // Revert on insufficient balance
            await expect(
                caiToken.connect(user1).transfer(user2.address, ethers.parseEther("150"))
            ).to.be.revertedWith("ERC20: transfer amount exceeds balance");

            // Approve & Allowance
            await caiToken.connect(user1).approve(user2.address, ethers.parseEther("50"));
            expect(await caiToken.allowance(user1.address, user2.address)).to.equal(ethers.parseEther("50"));

            // TransferFrom
            await caiToken.connect(user2).transferFrom(user1.address, user3.address, ethers.parseEther("50"));
            expect(await caiToken.balanceOf(user3.address)).to.equal(ethers.parseEther("50"));
            expect(await caiToken.allowance(user1.address, user2.address)).to.equal(0);

            // TransferFrom insufficient allowance
            await expect(
                caiToken.connect(user2).transferFrom(user1.address, user3.address, ethers.parseEther("1"))
            ).to.be.revertedWith("ERC20: insufficient allowance");

            // Max uint allowance does not decrease
            await caiToken.connect(user1).approve(user2.address, ethers.MaxUint256);
            await caiToken.connect(user2).transferFrom(user1.address, user3.address, ethers.parseEther("10"));
            expect(await caiToken.allowance(user1.address, user2.address)).to.equal(ethers.MaxUint256);
        });

        it("Normal Wallet to Wallet transfer is always allowed", async function () {
            await caiToken.connect(treasury).transfer(user1.address, ethers.parseEther("100"));
            await expect(caiToken.connect(user1).transfer(user2.address, ethers.parseEther("40"))).to.not.be.reverted;
            expect(await caiToken.balanceOf(user2.address)).to.equal(ethers.parseEther("40"));
        });

        it("PancakeSwap Buy: Reverts for non-whitelisted, allows whitelisted", async function () {
            const pancakePairMock = attacker;
            await caiToken.connect(multisig).setPancakePair(pancakePairMock.address);
            await caiToken.connect(treasury).transfer(pancakePairMock.address, ethers.parseEther("1000"));

            // Non-whitelisted buy -> REVERT
            await expect(
                caiToken.connect(pancakePairMock).transfer(user1.address, ethers.parseEther("50"))
            ).to.be.revertedWith("CAI: Buyer is not whitelisted for DEX purchases");

            // Whitelist user1 -> SUCCESS
            await caiToken.connect(multisig).setWhitelist(user1.address, true);
            await expect(
                caiToken.connect(pancakePairMock).transfer(user1.address, ethers.parseEther("50"))
            ).to.not.be.reverted;
            expect(await caiToken.balanceOf(user1.address)).to.equal(ethers.parseEther("50"));
        });

        it("PancakeSwap Sell: ALWAYS unrestricted for any user", async function () {
            const pancakePairMock = attacker;
            await caiToken.connect(multisig).setPancakePair(pancakePairMock.address);
            await caiToken.connect(treasury).transfer(user2.address, ethers.parseEther("100"));

            // User2 (not whitelisted) sells to pair -> SUCCESS
            await expect(
                caiToken.connect(user2).transfer(pancakePairMock.address, ethers.parseEther("100"))
            ).to.not.be.reverted;
        });

        it("Alternate AMM Pair registration & Anti-Bypass Protection", async function () {
            const fakeAMMPair = user3;
            await caiToken.connect(treasury).transfer(fakeAMMPair.address, ethers.parseEther("500"));

            // Before registration: behaves as normal wallet transfer
            await expect(caiToken.connect(fakeAMMPair).transfer(user1.address, ethers.parseEther("10"))).to.not.be.reverted;

            // Register alternate AMM pair
            await caiToken.connect(multisig).setAMMPair(fakeAMMPair.address, true);
            expect(await caiToken.isAMMPair(fakeAMMPair.address)).to.be.true;

            // After registration: Buy from fake AMM by non-whitelisted -> REVERT
            await expect(
                caiToken.connect(fakeAMMPair).transfer(user2.address, ethers.parseEther("10"))
            ).to.be.revertedWith("CAI: Buyer is not whitelisted for DEX purchases");

            // Remove AMM pair registration -> back to normal
            await caiToken.connect(multisig).setAMMPair(fakeAMMPair.address, false);
            await expect(caiToken.connect(fakeAMMPair).transfer(user2.address, ethers.parseEther("10"))).to.not.be.reverted;
        });

        it("Pancake Pair Lock: Permanent locking and protection", async function () {
            const pair1 = attacker;
            const pair2 = user3;

            await caiToken.connect(multisig).setPancakePair(pair1.address);
            await caiToken.connect(multisig).lockPancakePair();
            expect(await caiToken.pancakePairLocked()).to.be.true;

            // Cannot set new pair
            await expect(
                caiToken.connect(multisig).setPancakePair(pair2.address)
            ).to.be.revertedWith("CAI: PancakeSwap pair is permanently locked");

            // Cannot unregister locked pair
            await expect(
                caiToken.connect(multisig).setAMMPair(pair1.address, false)
            ).to.be.revertedWith("CAI: Cannot unregister locked official PancakeSwap pair");
        });

        it("Ownership: Non-owner rejection & 2-step ownership transfer", async function () {
            await expect(caiToken.connect(attacker).setWhitelist(user1.address, true)).to.be.revertedWith("Ownable: caller is not the owner");
            await expect(caiToken.connect(attacker).setBatchWhitelist([user1.address], true)).to.be.revertedWith("Ownable: caller is not the owner");
            await expect(caiToken.connect(attacker).setPancakePair(user1.address)).to.be.revertedWith("Ownable: caller is not the owner");
            await expect(caiToken.connect(attacker).lockPancakePair()).to.be.revertedWith("Ownable: caller is not the owner");

            // 2-Step transfer
            await caiToken.connect(multisig).transferOwnership(user1.address);
            expect(await caiToken.pendingOwner()).to.equal(user1.address);
            await expect(caiToken.connect(attacker).acceptOwnership()).to.be.revertedWith("Ownable2Step: caller is not the new owner");
            await caiToken.connect(user1).acceptOwnership();
            expect(await caiToken.owner()).to.equal(user1.address);
        });
    });

    // =========================================================================
    // 2. CYERAINVESTMENTSPLITTER TESTS
    // =========================================================================
    describe("2. CyeraInvestmentSplitter Tests", function () {
        beforeEach(async function () {
            await mockUSDT.mint(user1.address, ethers.parseEther("10000"));
            await mockUSDT.connect(user1).approve(await splitter.getAddress(), ethers.MaxUint256);
        });

        it("Deployment zero address checks", async function () {
            const SplitterFactory = await ethers.getContractFactory("CyeraInvestmentSplitter");
            await expect(SplitterFactory.deploy(ethers.ZeroAddress, await treasuryVault.getAddress(), liquidityWallet.address, multisig.address)).to.be.revertedWith("Splitter: USDT token cannot be zero");
            await expect(SplitterFactory.deploy(await mockUSDT.getAddress(), ethers.ZeroAddress, liquidityWallet.address, multisig.address)).to.be.revertedWith("Splitter: Treasury vault cannot be zero");
            await expect(SplitterFactory.deploy(await mockUSDT.getAddress(), await treasuryVault.getAddress(), ethers.ZeroAddress, multisig.address)).to.be.revertedWith("Splitter: Liquidity wallet cannot be zero");
        });

        it("Investment Limits: Reverts on $49.99 and $2000.01, allows $50, $50.01, $1999.99, $2000", async function () {
            // $49.99 -> Revert
            await expect(splitter.connect(user1).invest(ethers.parseEther("49.99"))).to.be.revertedWith("Splitter: Amount is below minimum investment ($50)");
            
            // $50 -> Success
            await expect(splitter.connect(user1).invest(ethers.parseEther("50"))).to.not.be.reverted;

            // $50.01 -> Success
            await expect(splitter.connect(user1).invest(ethers.parseEther("50.01"))).to.not.be.reverted;

            // $1999.99 -> Success
            await expect(splitter.connect(user1).invest(ethers.parseEther("1999.99"))).to.not.be.reverted;

            // $2000 -> Success
            await expect(splitter.connect(user1).invest(ethers.parseEther("2000"))).to.not.be.reverted;

            // $2000.01 -> Revert
            await expect(splitter.connect(user1).invest(ethers.parseEther("2000.01"))).to.be.revertedWith("Splitter: Amount exceeds maximum investment ($2,000)");
        });

        it("70/30 Distribution Exact Math: Splitter balance returns to 0", async function () {
            const amount = ethers.parseEther("100");
            const tBalBefore = await mockUSDT.balanceOf(await treasuryVault.getAddress());
            const lBalBefore = await mockUSDT.balanceOf(liquidityWallet.address);

            await splitter.connect(user1).invest(amount);

            expect(await mockUSDT.balanceOf(await treasuryVault.getAddress())).to.equal(tBalBefore + ethers.parseEther("70"));
            expect(await mockUSDT.balanceOf(liquidityWallet.address)).to.equal(lBalBefore + ethers.parseEther("30"));
            expect(await mockUSDT.balanceOf(await splitter.getAddress())).to.equal(0);
        });

        it("Decimal & Dust Precision Tests: treasuryAmount + liquidityAmount == amount (No dust loss)", async function () {
            const testAmounts = [
                ethers.parseEther("50.000001"),
                ethers.parseEther("99.999999"),
                ethers.parseEther("100.000001"),
                ethers.parseEther("123.456789"),
                ethers.parseEther("777.777777"),
                ethers.parseEther("1999.999999")
            ];

            for (const amt of testAmounts) {
                const tBefore = await mockUSDT.balanceOf(await treasuryVault.getAddress());
                const lBefore = await mockUSDT.balanceOf(liquidityWallet.address);

                await splitter.connect(user1).invest(amt);

                const tAfter = await mockUSDT.balanceOf(await treasuryVault.getAddress());
                const lAfter = await mockUSDT.balanceOf(liquidityWallet.address);

                const tDiff = tAfter - tBefore;
                const lDiff = lAfter - lBefore;

                expect(tDiff + lDiff).to.equal(amt);
                expect(await mockUSDT.balanceOf(await splitter.getAddress())).to.equal(0);
            }
        });

        it("Investment Counters and Clean Events", async function () {
            expect(await splitter.investmentCount()).to.equal(0);
            expect(await splitter.totalInvested()).to.equal(0);

            const amt1 = ethers.parseEther("100");
            const amt2 = ethers.parseEther("200");

            await expect(splitter.connect(user1).invest(amt1))
                .to.emit(splitter, "Invested")
                .withArgs(user1.address, amt1, (val) => true, 1);

            expect(await splitter.investmentCount()).to.equal(1);
            expect(await splitter.totalInvested()).to.equal(amt1);

            await expect(splitter.connect(user1).invest(amt2))
                .to.emit(splitter, "Invested")
                .withArgs(user1.address, amt2, (val) => true, 2);

            expect(await splitter.investmentCount()).to.equal(2);
            expect(await splitter.totalInvested()).to.equal(amt1 + amt2);
        });

        it("Splitter Admin & Pause tests", async function () {
            await expect(splitter.connect(attacker).setInvestmentLimits(10, 100)).to.be.revertedWith("Ownable: caller is not the owner");
            await expect(splitter.connect(attacker).pause()).to.be.revertedWith("Ownable: caller is not the owner");

            await splitter.connect(multisig).pause();
            await expect(splitter.connect(user1).invest(ethers.parseEther("100"))).to.be.revertedWith("Pausable: paused");

            await splitter.connect(multisig).unpause();
            await expect(splitter.connect(user1).invest(ethers.parseEther("100"))).to.not.be.reverted;
        });
    });

    // =========================================================================
    // 3. TREASURYCLAIMVAULT TESTS
    // =========================================================================
    describe("3. TreasuryClaimVault Tests", function () {
        beforeEach(async function () {
            await mockUSDT.mint(await treasuryVault.getAddress(), ethers.parseEther("10000"));
        });

        it("Deployment zero address checks", async function () {
            const TreasuryFactory = await ethers.getContractFactory("TreasuryClaimVault");
            await expect(TreasuryFactory.deploy(ethers.ZeroAddress, multisig.address)).to.be.revertedWith("TreasuryVault: USDT token cannot be zero address");
        });

        it("getVaultBalance returns exact balance", async function () {
            expect(await treasuryVault.getVaultBalance()).to.equal(ethers.parseEther("10000"));
        });

        it("transferFunds: Valid transfers with reason event", async function () {
            await expect(
                treasuryVault.connect(multisig).transferFunds(user1.address, ethers.parseEther("500"), "Liquidity Provisioning")
            ).to.emit(treasuryVault, "FundsClaimed")
            .withArgs(user1.address, ethers.parseEther("500"), "Liquidity Provisioning", (val) => true);

            expect(await mockUSDT.balanceOf(user1.address)).to.equal(ethers.parseEther("500"));
            expect(await treasuryVault.getVaultBalance()).to.equal(ethers.parseEther("9500"));
        });

        it("transferFunds: Reverts on zero recipient, zero amount, and exceeding balance", async function () {
            await expect(
                treasuryVault.connect(multisig).transferFunds(ethers.ZeroAddress, ethers.parseEther("100"), "Test")
            ).to.be.revertedWith("TreasuryVault: Recipient cannot be zero address");

            await expect(
                treasuryVault.connect(multisig).transferFunds(user1.address, 0, "Test")
            ).to.be.revertedWith("TreasuryVault: Amount must be greater than zero");

            await expect(
                treasuryVault.connect(multisig).transferFunds(user1.address, ethers.parseEther("20000"), "Test")
            ).to.be.revertedWith("TreasuryVault: Insufficient balance");
        });

        it("rescueTokens: Rescues accidental ERC20 tokens but STRICTLY BLOCKS USDT", async function () {
            // Send random token to vault
            await randomToken.mint(await treasuryVault.getAddress(), ethers.parseEther("500"));
            expect(await randomToken.balanceOf(await treasuryVault.getAddress())).to.equal(ethers.parseEther("500"));

            // Rescue random token -> SUCCESS
            await treasuryVault.connect(multisig).rescueTokens(await randomToken.getAddress(), user1.address, ethers.parseEther("500"));
            expect(await randomToken.balanceOf(user1.address)).to.equal(ethers.parseEther("500"));

            // Attempting to rescue USDT -> MUST REVERT
            await expect(
                treasuryVault.connect(multisig).rescueTokens(await mockUSDT.getAddress(), user1.address, ethers.parseEther("500"))
            ).to.be.revertedWith("TreasuryVault: Cannot rescue treasury USDT");
        });
    });

    // =========================================================================
    // 4. CAIREWARDCLAIMVAULT TESTS (EIP-712 ROI CLAIMS)
    // =========================================================================
    describe("4. CAIRewardClaimVault Tests", function () {
        let domain, types;

        beforeEach(async function () {
            const chainId = (await ethers.provider.getNetwork()).chainId;
            domain = {
                name: "CAIRewardClaimVault",
                version: "1",
                chainId: chainId,
                verifyingContract: await rewardVault.getAddress()
            };

            types = {
                ClaimReward: [
                    { name: "user", type: "address" },
                    { name: "caiAmount", type: "uint256" },
                    { name: "nonce", type: "uint256" },
                    { name: "expiry", type: "uint256" }
                ]
            };
        });

        it("Valid EIP-712 Claim: transfers exact CAI and marks nonce used", async function () {
            const amount = ethers.parseEther("100");
            const nonce = 1;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const sig = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: nonce,
                expiry: expiry
            });

            await expect(rewardVault.connect(user1).claimReward(amount, nonce, expiry, sig))
                .to.emit(rewardVault, "RewardClaimed")
                .withArgs(user1.address, amount, nonce, (val) => true);

            expect(await caiToken.balanceOf(user1.address)).to.equal(amount);
            expect(await rewardVault.usedNonces(nonce)).to.be.true;
        });

        it("Replay Attack: Rejects reuse of same nonce", async function () {
            const amount = ethers.parseEther("100");
            const nonce = 2;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const sig = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: nonce,
                expiry: expiry
            });

            await rewardVault.connect(user1).claimReward(amount, nonce, expiry, sig);

            await expect(
                rewardVault.connect(user1).claimReward(amount, nonce, expiry, sig)
            ).to.be.revertedWith("ClaimVault: Nonce has already been claimed");
        });

        it("Security: Rejects wrong user, wrong amount, wrong nonce, expired signature, forged signature", async function () {
            const amount = ethers.parseEther("100");
            const nonce = 3;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const sig = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: nonce,
                expiry: expiry
            });

            // Wrong User calls claim -> Revert
            await expect(rewardVault.connect(user2).claimReward(amount, nonce, expiry, sig)).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");

            // Wrong Amount submitted -> Revert
            await expect(rewardVault.connect(user1).claimReward(ethers.parseEther("200"), nonce, expiry, sig)).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");

            // Wrong Nonce submitted -> Revert
            await expect(rewardVault.connect(user1).claimReward(amount, 999, expiry, sig)).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");

            // Expired signature -> Revert
            const expiredTimestamp = Math.floor(Date.now() / 1000) - 100;
            const expiredSig = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: 4,
                expiry: expiredTimestamp
            });
            await expect(rewardVault.connect(user1).claimReward(amount, 4, expiredTimestamp, expiredSig)).to.be.revertedWith("ClaimVault: Claim signature has expired");

            // Forged signature -> Revert
            const forgedSig = await attacker.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: 5,
                expiry: expiry
            });
            await expect(rewardVault.connect(user1).claimReward(amount, 5, expiry, forgedSig)).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");
        });

        it("Insufficient Vault Balance Revert", async function () {
            const hugeAmount = ethers.parseEther("100000"); // Vault only has 50k
            const nonce = 6;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const sig = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: hugeAmount,
                nonce: nonce,
                expiry: expiry
            });

            await expect(
                rewardVault.connect(user1).claimReward(hugeAmount, nonce, expiry, sig)
            ).to.be.revertedWith("ClaimVault: Insufficient CAI reward liquidity in vault");
        });

        it("Signer Change: Old signatures fail, new signatures work", async function () {
            const amount = ethers.parseEther("50");
            const nonce = 7;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const oldSig = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: nonce,
                expiry: expiry
            });

            // Multisig updates backend signer
            await rewardVault.connect(multisig).setBackendSigner(newSigner.address);
            expect(await rewardVault.backendSigner()).to.equal(newSigner.address);

            // Old signature now fails
            await expect(rewardVault.connect(user1).claimReward(amount, nonce, expiry, oldSig)).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");

            // New signature succeeds
            const newSig = await newSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: amount,
                nonce: nonce,
                expiry: expiry
            });
            await expect(rewardVault.connect(user1).claimReward(amount, nonce, expiry, newSig)).to.not.be.reverted;
        });

        it("Emergency Withdraw CAI: Multisig only", async function () {
            await expect(rewardVault.connect(attacker).emergencyWithdrawCAI(attacker.address, ethers.parseEther("100"))).to.be.revertedWith("Ownable: caller is not the owner");
            await expect(rewardVault.connect(multisig).emergencyWithdrawCAI(ethers.ZeroAddress, ethers.parseEther("100"))).to.be.revertedWith("ClaimVault: Destination cannot be zero address");

            await rewardVault.connect(multisig).emergencyWithdrawCAI(treasury.address, ethers.parseEther("1000"));
            expect(await caiToken.balanceOf(treasury.address)).to.be.above(0);
        });
    });

    // =========================================================================
    // 5. USDTWITHDRAWALVAULT TESTS (WORKING INCOME PAYOUTS)
    // =========================================================================
    describe("5. USDTWithdrawalVault Tests", function () {
        let domain, types;

        beforeEach(async function () {
            const chainId = (await ethers.provider.getNetwork()).chainId;
            domain = {
                name: "USDTWithdrawalVault",
                version: "1",
                chainId: chainId,
                verifyingContract: await withdrawalVault.getAddress()
            };

            types = {
                Withdrawal: [
                    { name: "recipient", type: "address" },
                    { name: "amount", type: "uint256" },
                    { name: "withdrawalId", type: "uint256" },
                    { name: "expiry", type: "uint256" }
                ]
            };
        });

        it("Single Withdrawal: Valid execution marks withdrawalId processed", async function () {
            const amount = ethers.parseEther("200");
            const wId = 1001;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const sig = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amount,
                withdrawalId: wId,
                expiry: expiry
            });

            await expect(withdrawalVault.connect(user1).executeWithdrawalWithSignature(user1.address, amount, wId, expiry, sig))
                .to.emit(withdrawalVault, "WithdrawalPaid")
                .withArgs(user1.address, amount, wId, (val) => true);

            expect(await mockUSDT.balanceOf(user1.address)).to.equal(amount);
            expect(await withdrawalVault.processedWithdrawals(wId)).to.be.true;
        });

        it("Duplicate Withdrawal Attack Revert", async function () {
            const amount = ethers.parseEther("200");
            const wId = 1002;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const sig = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amount,
                withdrawalId: wId,
                expiry: expiry
            });

            await withdrawalVault.connect(user1).executeWithdrawalWithSignature(user1.address, amount, wId, expiry, sig);

            // Second execution attempt with same withdrawal ID
            await expect(
                withdrawalVault.connect(user1).executeWithdrawalWithSignature(user1.address, amount, wId, expiry, sig)
            ).to.be.revertedWith("WithdrawalVault: Withdrawal ID already processed");
        });

        it("Batch Withdrawals: Success and duplicate ID check", async function () {
            const amounts = [ethers.parseEther("50"), ethers.parseEther("150")];
            const wIds = [2001, 2002];
            const expiries = [Math.floor(Date.now() / 1000) + 3600, Math.floor(Date.now() / 1000) + 3600];
            const recipients = [user1.address, user2.address];

            const sig1 = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amounts[0],
                withdrawalId: wIds[0],
                expiry: expiries[0]
            });

            const sig2 = await backendSigner.signTypedData(domain, types, {
                recipient: user2.address,
                amount: amounts[1],
                withdrawalId: wIds[1],
                expiry: expiries[1]
            });

            await expect(
                withdrawalVault.connect(owner).executeBatchWithdrawalsWithSignatures(
                    recipients,
                    amounts,
                    wIds,
                    expiries,
                    [sig1, sig2]
                )
            ).to.emit(withdrawalVault, "BatchWithdrawalsPaid");

            expect(await mockUSDT.balanceOf(user1.address)).to.equal(amounts[0]);
            expect(await mockUSDT.balanceOf(user2.address)).to.equal(amounts[1]);
            expect(await withdrawalVault.processedWithdrawals(2001)).to.be.true;
            expect(await withdrawalVault.processedWithdrawals(2002)).to.be.true;
        });

        it("Batch Array Length Mismatch Revert", async function () {
            await expect(
                withdrawalVault.connect(owner).executeBatchWithdrawalsWithSignatures(
                    [user1.address, user2.address],
                    [ethers.parseEther("100")], // length 1
                    [3001, 3002],
                    [1000, 1000],
                    ["0x", "0x"]
                )
            ).to.be.revertedWith("WithdrawalVault: Array length mismatch");
        });

        it("Batch Atomicity: Entire batch reverts if one signature is invalid (No partial payments)", async function () {
            const u1Before = await mockUSDT.balanceOf(user1.address);
            const u2Before = await mockUSDT.balanceOf(user2.address);

            const amounts = [ethers.parseEther("100"), ethers.parseEther("100")];
            const wIds = [4001, 4002];
            const expiries = [Math.floor(Date.now() / 1000) + 3600, Math.floor(Date.now() / 1000) + 3600];
            const recipients = [user1.address, user2.address];

            const sig1 = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amounts[0],
                withdrawalId: wIds[0],
                expiry: expiries[0]
            });

            const forgedSig2 = await attacker.signTypedData(domain, types, {
                recipient: user2.address,
                amount: amounts[1],
                withdrawalId: wIds[1],
                expiry: expiries[1]
            });

            // Batch execution must completely revert
            await expect(
                withdrawalVault.connect(owner).executeBatchWithdrawalsWithSignatures(
                    recipients,
                    amounts,
                    wIds,
                    expiries,
                    [sig1, forgedSig2]
                )
            ).to.be.revertedWith("WithdrawalVault: Invalid cryptographic signature");

            // User1 must NOT receive payment due to atomic revert
            expect(await mockUSDT.balanceOf(user1.address)).to.equal(u1Before);
            expect(await mockUSDT.balanceOf(user2.address)).to.equal(u2Before);
            expect(await withdrawalVault.processedWithdrawals(4001)).to.be.false;
        });

        it("Signer Change & Emergency Withdrawal in USDTWithdrawalVault", async function () {
            await withdrawalVault.connect(multisig).setBackendSigner(newSigner.address);
            expect(await withdrawalVault.backendSigner()).to.equal(newSigner.address);

            // Emergency withdraw by multisig
            await withdrawalVault.connect(multisig).emergencyWithdraw(treasury.address, ethers.parseEther("1000"));
            expect(await mockUSDT.balanceOf(treasury.address)).to.equal(ethers.parseEther("1000"));
        });
    });
});
