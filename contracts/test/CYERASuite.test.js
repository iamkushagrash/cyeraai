import { expect } from "chai";
import hre from "hardhat";
const { ethers } = hre;

describe("CYERA (CAI) Hybrid Ecosystem — Comprehensive Mainnet Security Test Suite", function () {
    let owner, multisig, treasury, liquidityWallet, backendSigner, attacker, user1, user2;
    let mockUSDT, caiToken, splitter, treasuryVault, rewardVault, withdrawalVault;

    const TOTAL_SUPPLY = ethers.parseEther("300000"); // 300,000 CAI
    const INITIAL_USDT = ethers.parseEther("1000000"); // 1,000,000 USDT

    beforeEach(async function () {
        [owner, multisig, treasury, liquidityWallet, backendSigner, attacker, user1, user2] = await ethers.getSigners();

        // 1. Deploy Mock USDT (18 decimals)
        const MockERC20 = await ethers.getContractFactory("MockUSDT");
        mockUSDT = await MockERC20.deploy("Tether USD", "USDT", 18);
        await mockUSDT.waitForDeployment();

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

    describe("1. CAIToken — Whitelist Buy & Unrestricted Sell", function () {
        let pancakePairMock;

        beforeEach(async function () {
            // Mock PancakeSwap pair
            pancakePairMock = attacker; // Representing AMM Pair
            await caiToken.connect(multisig).setPancakePair(pancakePairMock.address);
            // Fund pair with CAI
            await caiToken.connect(treasury).transfer(pancakePairMock.address, ethers.parseEther("10000"));
        });

        it("Should REJECT non-whitelisted wallet buying from official pair", async function () {
            await expect(
                caiToken.connect(pancakePairMock).transfer(user1.address, ethers.parseEther("100"))
            ).to.be.revertedWith("CAI: Buyer is not whitelisted for DEX purchases");
        });

        it("Should ALLOW whitelisted wallet buying from official pair", async function () {
            await caiToken.connect(multisig).setWhitelist(user1.address, true);
            await expect(
                caiToken.connect(pancakePairMock).transfer(user1.address, ethers.parseEther("100"))
            ).to.not.be.reverted;
            expect(await caiToken.balanceOf(user1.address)).to.equal(ethers.parseEther("100"));
        });

        it("Should ALLOW ANY holder to SELL to pair without whitelist", async function () {
            // Transfer CAI to user2
            await caiToken.connect(treasury).transfer(user2.address, ethers.parseEther("50"));
            // User2 sells to pair
            await expect(
                caiToken.connect(user2).transfer(pancakePairMock.address, ethers.parseEther("50"))
            ).to.not.be.reverted;
        });

        it("Should LOCK PancakeSwap pair permanently", async function () {
            await caiToken.connect(multisig).lockPancakePair();
            expect(await caiToken.pancakePairLocked()).to.be.true;

            // Attempting to change pair should revert
            await expect(
                caiToken.connect(multisig).setPancakePair(user2.address)
            ).to.be.revertedWith("CAI: PancakeSwap pair is permanently locked");
        });

        it("Should block alternate AMM pair creation bypass", async function () {
            const alternateAMMPair = user2;
            await caiToken.connect(multisig).setAMMPair(alternateAMMPair.address, true);
            await caiToken.connect(treasury).transfer(alternateAMMPair.address, ethers.parseEther("1000"));

            // Non-whitelisted buy from alternate AMM pair must be blocked
            await expect(
                caiToken.connect(alternateAMMPair).transfer(user1.address, ethers.parseEther("50"))
            ).to.be.revertedWith("CAI: Buyer is not whitelisted for DEX purchases");
        });
    });

    describe("2. CyeraInvestmentSplitter — invest() Action & Limits", function () {
        beforeEach(async function () {
            await mockUSDT.mint(user1.address, ethers.parseEther("5000"));
            await mockUSDT.connect(user1).approve(await splitter.getAddress(), ethers.parseEther("5000"));
        });

        it("Should REJECT investments below $50 and above $2,000", async function () {
            await expect(
                splitter.connect(user1).invest(ethers.parseEther("49"))
            ).to.be.revertedWith("Splitter: Amount is below minimum investment ($50)");

            await expect(
                splitter.connect(user1).invest(ethers.parseEther("2001"))
            ).to.be.revertedWith("Splitter: Amount exceeds maximum investment ($2,000)");
        });

        it("Should ATOMICALLY split 70% to Treasury and 30% to Liquidity Wallet via invest()", async function () {
            const investmentAmount = ethers.parseEther("100"); // $100 USDT
            const initialTreasuryBal = await mockUSDT.balanceOf(await treasuryVault.getAddress());
            const initialLiquidityBal = await mockUSDT.balanceOf(liquidityWallet.address);

            await expect(splitter.connect(user1).invest(investmentAmount))
                .to.emit(splitter, "Invested")
                .withArgs(user1.address, investmentAmount, (val) => true, 1);

            expect(await mockUSDT.balanceOf(await treasuryVault.getAddress())).to.equal(initialTreasuryBal + ethers.parseEther("70"));
            expect(await mockUSDT.balanceOf(liquidityWallet.address)).to.equal(initialLiquidityBal + ethers.parseEther("30"));
        });
    });

    describe("3. TreasuryClaimVault — Security & Rescue Loophole Fix", function () {
        beforeEach(async function () {
            await mockUSDT.mint(await treasuryVault.getAddress(), ethers.parseEther("10000"));
        });

        it("Should PREVENT rescuing treasury USDT via rescueTokens", async function () {
            await expect(
                treasuryVault.connect(multisig).rescueTokens(await mockUSDT.getAddress(), multisig.address, ethers.parseEther("1000"))
            ).to.be.revertedWith("TreasuryVault: Cannot rescue treasury USDT");
        });

        it("Should ALLOW authorized transferFunds by multisig owner", async function () {
            await expect(
                treasuryVault.connect(multisig).transferFunds(user1.address, ethers.parseEther("500"), "Project Development")
            ).to.emit(treasuryVault, "FundsClaimed");

            expect(await mockUSDT.balanceOf(user1.address)).to.equal(ethers.parseEther("500"));
        });
    });

    describe("4. CAIRewardClaimVault — EIP-712 Signatures & Replay Protection", function () {
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

        it("Should SUCCESSFUL claim CAI with valid EIP-712 backend signature", async function () {
            const caiAmount = ethers.parseEther("150");
            const nonce = 1001;
            const expiry = Math.floor(Date.now() / 1000) + 3600; // 1 hour

            const signature = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: caiAmount,
                nonce: nonce,
                expiry: expiry
            });

            await expect(
                rewardVault.connect(user1).claimReward(caiAmount, nonce, expiry, signature)
            ).to.emit(rewardVault, "RewardClaimed")
            .withArgs(user1.address, caiAmount, nonce, (val) => true);

            expect(await caiToken.balanceOf(user1.address)).to.equal(caiAmount);
            expect(await rewardVault.usedNonces(nonce)).to.be.true;
        });

        it("Should REJECT duplicate claim with same nonce (Replay Protection)", async function () {
            const caiAmount = ethers.parseEther("150");
            const nonce = 1002;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const signature = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: caiAmount,
                nonce: nonce,
                expiry: expiry
            });

            await rewardVault.connect(user1).claimReward(caiAmount, nonce, expiry, signature);

            // Replay attempt must revert
            await expect(
                rewardVault.connect(user1).claimReward(caiAmount, nonce, expiry, signature)
            ).to.be.revertedWith("ClaimVault: Nonce has already been claimed");
        });

        it("Should REJECT expired signature", async function () {
            const caiAmount = ethers.parseEther("150");
            const nonce = 1003;
            const expiredTimestamp = Math.floor(Date.now() / 1000) - 100; // In the past

            const signature = await backendSigner.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: caiAmount,
                nonce: nonce,
                expiry: expiredTimestamp
            });

            await expect(
                rewardVault.connect(user1).claimReward(caiAmount, nonce, expiredTimestamp, signature)
            ).to.be.revertedWith("ClaimVault: Claim signature has expired");
        });

        it("Should REJECT signature forged by attacker", async function () {
            const caiAmount = ethers.parseEther("150");
            const nonce = 1004;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const forgedSignature = await attacker.signTypedData(domain, types, {
                user: user1.address,
                caiAmount: caiAmount,
                nonce: nonce,
                expiry: expiry
            });

            await expect(
                rewardVault.connect(user1).claimReward(caiAmount, nonce, expiry, forgedSignature)
            ).to.be.revertedWith("ClaimVault: Invalid cryptographic signature");
        });
    });

    describe("5. USDTWithdrawalVault — Duplicate ID Protection & EIP-712 Verification", function () {
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

        it("Should PROCESS valid single working income withdrawal", async function () {
            const amount = ethers.parseEther("250");
            const withdrawalId = 5001;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const signature = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amount,
                withdrawalId: withdrawalId,
                expiry: expiry
            });

            await expect(
                withdrawalVault.connect(user1).executeWithdrawalWithSignature(user1.address, amount, withdrawalId, expiry, signature)
            ).to.emit(withdrawalVault, "WithdrawalPaid")
            .withArgs(user1.address, amount, withdrawalId, (val) => true);

            expect(await mockUSDT.balanceOf(user1.address)).to.equal(amount);
            expect(await withdrawalVault.processedWithdrawals(withdrawalId)).to.be.true;
        });

        it("Should REJECT duplicate withdrawal ID execution", async function () {
            const amount = ethers.parseEther("250");
            const withdrawalId = 5002;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const signature = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amount,
                withdrawalId: withdrawalId,
                expiry: expiry
            });

            await withdrawalVault.connect(user1).executeWithdrawalWithSignature(user1.address, amount, withdrawalId, expiry, signature);

            // Duplicate attempt with same withdrawal ID
            await expect(
                withdrawalVault.connect(user1).executeWithdrawalWithSignature(user1.address, amount, withdrawalId, expiry, signature)
            ).to.be.revertedWith("WithdrawalVault: Withdrawal ID already processed");
        });

        it("Should BATCH process multiple withdrawals and enforce duplicate protection", async function () {
            const amounts = [ethers.parseEther("100"), ethers.parseEther("200")];
            const withdrawalIds = [5003, 5004];
            const expiries = [Math.floor(Date.now() / 1000) + 3600, Math.floor(Date.now() / 1000) + 3600];
            const recipients = [user1.address, user2.address];

            const sig1 = await backendSigner.signTypedData(domain, types, {
                recipient: user1.address,
                amount: amounts[0],
                withdrawalId: withdrawalIds[0],
                expiry: expiries[0]
            });

            const sig2 = await backendSigner.signTypedData(domain, types, {
                recipient: user2.address,
                amount: amounts[1],
                withdrawalId: withdrawalIds[1],
                expiry: expiries[1]
            });

            await expect(
                withdrawalVault.connect(owner).executeBatchWithdrawalsWithSignatures(
                    recipients,
                    amounts,
                    withdrawalIds,
                    expiries,
                    [sig1, sig2]
                )
            ).to.emit(withdrawalVault, "BatchWithdrawalsPaid");

            expect(await withdrawalVault.processedWithdrawals(5003)).to.be.true;
            expect(await withdrawalVault.processedWithdrawals(5004)).to.be.true;
        });
    });

    describe("6. 2-Step Ownable & Pause Functionality", function () {
        it("Should ENFORCE 2-step ownership transfer", async function () {
            await splitter.connect(multisig).transferOwnership(user1.address);
            expect(await splitter.pendingOwner()).to.equal(user1.address);
            expect(await splitter.owner()).to.equal(multisig.address);

            // User1 accepts ownership
            await splitter.connect(user1).acceptOwnership();
            expect(await splitter.owner()).to.equal(user1.address);
            expect(await splitter.pendingOwner()).to.equal(ethers.ZeroAddress);
        });

        it("Should BLOCK operations when paused", async function () {
            await splitter.connect(multisig).pause();
            expect(await splitter.paused()).to.be.true;

            await mockUSDT.mint(user1.address, ethers.parseEther("100"));
            await mockUSDT.connect(user1).approve(await splitter.getAddress(), ethers.parseEther("100"));

            await expect(
                splitter.connect(user1).invest(ethers.parseEther("100"))
            ).to.be.revertedWith("Pausable: paused");
        });
    });
});
