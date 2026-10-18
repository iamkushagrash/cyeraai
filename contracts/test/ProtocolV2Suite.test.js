import { expect } from "chai";
import hre from "hardhat";
const { ethers } = hre;

describe("🏛️ CYERA AI — PROTOCOL V2 COMPREHENSIVE TEST SUITE", function () {
    let deployer, backendSigner, investor, userAlice, userBob, attacker;
    let tradingWallet, partner1, partner2, partner3, partner4, partner5, reserveWallet;
    let mockUSDT, mockFactory, mockRouter, caiToken, revenueSplitter, usdtVault, miningEngine;
    let partnerAddresses;

    const SUPPLY_777777 = ethers.parseEther("777777");
    const INVEST_AMOUNT = ethers.parseUnits("1000", 18); // $1,000 USDT

    beforeEach(async function () {
        [
            deployer,
            backendSigner,
            investor,
            userAlice,
            userBob,
            attacker,
            tradingWallet,
            partner1,
            partner2,
            partner3,
            partner4,
            partner5,
            reserveWallet,
        ] = await ethers.getSigners();

        // 1. Deploy Mock USDT
        const MockUSDT = await ethers.getContractFactory("MockUSDT");
        mockUSDT = await MockUSDT.deploy();
        await mockUSDT.waitForDeployment();

        // 2. Deploy Mock PancakeFactory & Router
        const MockFactory = await ethers.getContractFactory("MockPancakeFactory");
        mockFactory = await MockFactory.deploy();
        await mockFactory.waitForDeployment();

        const MockRouter = await ethers.getContractFactory("MockPancakeRouter");
        mockRouter = await MockRouter.deploy(await mockFactory.getAddress());
        await mockRouter.waitForDeployment();

        // 3. Deploy CAIToken (777,777 Supply)
        const CAIToken = await ethers.getContractFactory("contracts/CAIToken.sol:CAIToken");
        caiToken = await CAIToken.deploy(
            await mockUSDT.getAddress(),
            await mockRouter.getAddress()
        );
        await caiToken.waitForDeployment();

        // 4. Deploy RevenueSplitter (20% Trading + 5x2% Partners + 2% Reserve = 32%)
        partnerAddresses = [
            partner1.address,
            partner2.address,
            partner3.address,
            partner4.address,
            partner5.address,
        ];

        const RevenueSplitter = await ethers.getContractFactory("contracts/RevenueSplitter.sol:RevenueSplitter");
        revenueSplitter = await RevenueSplitter.deploy(
            await mockUSDT.getAddress(),
            tradingWallet.address,
            partnerAddresses,
            reserveWallet.address,
            deployer.address
        );
        await revenueSplitter.waitForDeployment();

        // 5. Deploy USDTWithdrawalVault
        const USDTWithdrawalVault = await ethers.getContractFactory("contracts/USDTWithdrawalVault.sol:USDTWithdrawalVault");
        usdtVault = await USDTWithdrawalVault.deploy(
            await mockUSDT.getAddress(),
            backendSigner.address,
            deployer.address
        );
        await usdtVault.waitForDeployment();

        // 6. Deploy CyeraMiningEngine (30% Pancake + 38% Vault + 32% RevenueSplitter)
        const CyeraMiningEngine = await ethers.getContractFactory("contracts/CyeraMiningEngine.sol:CyeraMiningEngine");
        miningEngine = await CyeraMiningEngine.deploy(
            await mockUSDT.getAddress(),
            await caiToken.getAddress(),
            await mockRouter.getAddress(),
            await usdtVault.getAddress(),
            await revenueSplitter.getAddress(),
            backendSigner.address,
            deployer.address
        );
        await miningEngine.waitForDeployment();

        // Fund investor with USDT
        await mockUSDT.mint(investor.address, ethers.parseUnits("50000", 18));
        await mockUSDT.connect(investor).approve(await miningEngine.getAddress(), ethers.MaxUint256);

        // Pre-fund router with CAI so it can simulate PancakeSwap buy output
        await caiToken.transfer(await mockRouter.getAddress(), ethers.parseEther("50000"));
        // Pre-fund router with USDT so it can simulate PancakeSwap sell output
        await mockUSDT.mint(await mockRouter.getAddress(), ethers.parseUnits("50000", 18));
    });

    // =========================================================================
    // 1. CAITOKEN TESTS (777,777 Supply & Ownership Renunciation)
    // =========================================================================
    describe("1. CAIToken Tests", function () {
        it("Total supply must be exactly 777,777 CAI tokens (18 decimals)", async function () {
            expect(await caiToken.name()).to.equal("Cyera AI");
            expect(await caiToken.symbol()).to.equal("CAAI");
            expect(await caiToken.decimals()).to.equal(18);
            expect(await caiToken.totalSupply()).to.equal(SUPPLY_777777);
            expect(await caiToken.balanceOf(deployer.address)).to.equal(SUPPLY_777777 - ethers.parseEther("50000"));
        });

        it("PancakeSwap pair address must be auto-created in constructor", async function () {
            const pair = await caiToken.pancakePair();
            expect(pair).to.not.equal(ethers.ZeroAddress);
        });

        it("Setting MiningEngine permanently locks config and renounces ownership (100% Green Score)", async function () {
            expect(await caiToken.owner()).to.equal(deployer.address);
            expect(await caiToken.configurationLocked()).to.equal(false);

            await caiToken.setMiningContractAndRenounceOwnership(await miningEngine.getAddress());

            expect(await caiToken.configurationLocked()).to.equal(true);
            expect(await caiToken.miningContract()).to.equal(await miningEngine.getAddress());
            expect(await caiToken.owner()).to.equal(ethers.ZeroAddress); // Permanently Renounced!
        });

        it("Reverts if non-owner tries to set mining contract or if called twice", async function () {
            await expect(
                caiToken.connect(attacker).setMiningContractAndRenounceOwnership(await miningEngine.getAddress())
            ).to.be.revertedWithCustomError(caiToken, "OwnableUnauthorizedAccount");

            await caiToken.setMiningContractAndRenounceOwnership(await miningEngine.getAddress());

            await expect(
                caiToken.setMiningContractAndRenounceOwnership(await miningEngine.getAddress())
            ).to.be.revertedWithCustomError(caiToken, "OwnableUnauthorizedAccount");
        });

        it("Blocks public direct buys from PancakePair while allowing normal transfers", async function () {
            const pairAddress = await caiToken.pancakePair();
            await caiToken.setMiningContractAndRenounceOwnership(await miningEngine.getAddress());

            // Normal user-to-user transfer is allowed
            await caiToken.transfer(userAlice.address, ethers.parseEther("100"));
            expect(await caiToken.balanceOf(userAlice.address)).to.equal(ethers.parseEther("100"));

            // User selling to pair is allowed
            await caiToken.connect(userAlice).transfer(pairAddress, ethers.parseEther("10"));
            expect(await caiToken.balanceOf(pairAddress)).to.equal(ethers.parseEther("10"));

            // Pair transfer to user (public buy) is blocked
            await ethers.provider.send("hardhat_impersonateAccount", [pairAddress]);
            const pairSigner = await ethers.getSigner(pairAddress);

            // Fund pair with gas
            await ethers.provider.send("hardhat_setBalance", [pairAddress, "0x1000000000000000000"]);

            await expect(
                caiToken.connect(pairSigner).transfer(userBob.address, ethers.parseEther("5"))
            ).to.be.revertedWithCustomError(caiToken, "PancakeBuyBlocked");

            // Pair transfer to MiningEngine (30% protocol buy) is ALLOWED!
            await expect(
                caiToken.connect(pairSigner).transfer(await miningEngine.getAddress(), ethers.parseEther("5"))
            ).to.not.be.reverted;

            await ethers.provider.send("hardhat_stopImpersonatingAccount", [pairAddress]);
        });
    });

    // =========================================================================
    // 2. REVENUE SPLITTER TESTS (20% Trading + 10% [5 Partners @ 2%] + 2% Reserve)
    // =========================================================================
    describe("2. RevenueSplitter Tests (Exact 32% Distribution)", function () {
        it("Initializes with correct addresses and rejects zero address", async function () {
            expect(await revenueSplitter.tradingWallet()).to.equal(tradingWallet.address);
            expect(await revenueSplitter.reserveWallet()).to.equal(reserveWallet.address);

            const partners = await revenueSplitter.getPartnerWallets();
            expect(partners[0]).to.equal(partner1.address);
            expect(partners[1]).to.equal(partner2.address);
            expect(partners[2]).to.equal(partner3.address);
            expect(partners[3]).to.equal(partner4.address);
            expect(partners[4]).to.equal(partner5.address);

            const Factory = await ethers.getContractFactory("contracts/RevenueSplitter.sol:RevenueSplitter");
            await expect(
                Factory.deploy(
                    await mockUSDT.getAddress(),
                    ethers.ZeroAddress,
                    partnerAddresses,
                    reserveWallet.address,
                    deployer.address
                )
            ).to.be.revertedWithCustomError(revenueSplitter, "ZeroAddress");
        });

        it("Exact 0-loss split: 20/32 Trading, 2/32 each to 5 Partners, 2/32 Reserve", async function () {
            // Send $3,200 USDT (Simulating incoming 32% from a $10,000 total package)
            const splitInput = ethers.parseUnits("3200", 18);
            await mockUSDT.mint(await revenueSplitter.getAddress(), splitInput);

            const tradingBefore = await mockUSDT.balanceOf(tradingWallet.address);
            const p1Before = await mockUSDT.balanceOf(partner1.address);
            const p2Before = await mockUSDT.balanceOf(partner2.address);
            const p3Before = await mockUSDT.balanceOf(partner3.address);
            const p4Before = await mockUSDT.balanceOf(partner4.address);
            const p5Before = await mockUSDT.balanceOf(partner5.address);
            const reserveBefore = await mockUSDT.balanceOf(reserveWallet.address);

            await revenueSplitter.distribute();

            // Trading: 20/32 of $3,200 = $2,000 USDT (20% of package)
            expect(await mockUSDT.balanceOf(tradingWallet.address) - tradingBefore).to.equal(ethers.parseUnits("2000", 18));

            // Partners 1..5: 2/32 of $3,200 = $200 USDT each (2% of package each = $1,000 total)
            expect(await mockUSDT.balanceOf(partner1.address) - p1Before).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(partner2.address) - p2Before).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(partner3.address) - p3Before).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(partner4.address) - p4Before).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(partner5.address) - p5Before).to.equal(ethers.parseUnits("200", 18));

            // Reserve: 2/32 of $3,200 = $200 USDT (2% of package)
            expect(await mockUSDT.balanceOf(reserveWallet.address) - reserveBefore).to.equal(ethers.parseUnits("200", 18));

            // Remaining contract balance should be 0 (Zero-loss math!)
            expect(await mockUSDT.balanceOf(await revenueSplitter.getAddress())).to.equal(0);
        });

        it("Owner can update Trading, Partner, and Reserve wallet addresses anytime", async function () {
            // Update Trading Wallet
            await revenueSplitter.setTradingWallet(userAlice.address);
            expect(await revenueSplitter.tradingWallet()).to.equal(userAlice.address);

            // Update Partner 3 (Index 2)
            await revenueSplitter.setPartnerWallet(2, userBob.address);
            const partners = await revenueSplitter.getPartnerWallets();
            expect(partners[2]).to.equal(userBob.address);

            // Update Reserve Wallet
            await revenueSplitter.setReserveWallet(userAlice.address);
            expect(await revenueSplitter.reserveWallet()).to.equal(userAlice.address);

            // Non-owner cannot update
            await expect(
                revenueSplitter.connect(attacker).setTradingWallet(attacker.address)
            ).to.be.revertedWithCustomError(revenueSplitter, "OwnableUnauthorizedAccount");

            await expect(
                revenueSplitter.connect(attacker).setPartnerWallet(0, attacker.address)
            ).to.be.revertedWithCustomError(revenueSplitter, "OwnableUnauthorizedAccount");

            // Invalid index (>= 5) reverts
            await expect(
                revenueSplitter.setPartnerWallet(5, userBob.address)
            ).to.be.revertedWithCustomError(revenueSplitter, "InvalidPartnerIndex");
        });
    });

    // =========================================================================
    // 3. CYERA MINING ENGINE V2 TESTS (30% Pancake, 38% Vault, 32% Splitter)
    // =========================================================================
    describe("3. CyeraMiningEngine V2 Tests", function () {
        beforeEach(async function () {
            // Lock and renounce token to mining contract, simulating production ready state
            await caiToken.setMiningContractAndRenounceOwnership(await miningEngine.getAddress());
        });

        it("Investment Split: 30% Pancake Auto-Buy + 38% Withdrawal Vault + 32% Revenue Splitter", async function () {
            const vaultBefore = await mockUSDT.balanceOf(await usdtVault.getAddress());
            const tradingBefore = await mockUSDT.balanceOf(tradingWallet.address);
            const p1Before = await mockUSDT.balanceOf(partner1.address);
            const reserveBefore = await mockUSDT.balanceOf(reserveWallet.address);

            // Investor invests $1,000 USDT
            await miningEngine.connect(investor).invest(INVEST_AMOUNT, 1);

            // 1. Withdrawal Vault received 38% ($380 USDT)
            expect(await mockUSDT.balanceOf(await usdtVault.getAddress()) - vaultBefore).to.equal(ethers.parseUnits("380", 18));

            // 2. Revenue Splitter received 32% ($320 USDT) and auto-distributed:
            // Trading received 20% ($200 USDT)
            expect(await mockUSDT.balanceOf(tradingWallet.address) - tradingBefore).to.equal(ethers.parseUnits("200", 18));
            // Partner 1 received 2% ($20 USDT)
            expect(await mockUSDT.balanceOf(partner1.address) - p1Before).to.equal(ethers.parseUnits("20", 18));
            // Reserve received 2% ($20 USDT)
            expect(await mockUSDT.balanceOf(reserveWallet.address) - reserveBefore).to.equal(ethers.parseUnits("20", 18));

            // 3. MiningEngine total volume metrics updated
            expect(await miningEngine.totalInvestedUsdt()).to.equal(INVEST_AMOUNT);
            expect(await miningEngine.totalVaultFundedUsdt()).to.equal(ethers.parseUnits("380", 18));
            expect(await miningEngine.totalSplitterFundedUsdt()).to.equal(ethers.parseUnits("320", 18));
        });

        it("ROI Claim: sellPortfolio executes with valid EIP-712 signature and 0 slippage restriction", async function () {
            // Deposit buffer of CAI into MiningEngine so it can execute sell
            await caiToken.transfer(await miningEngine.getAddress(), ethers.parseEther("5000"));

            const claimCai = ethers.parseEther("100");
            const minUsdtOut = 0; // ZERO SLIPPAGE RESTRICTION!
            const nonce = 12345;
            const expiry = Math.floor(Date.now() / 1000) + 3600; // 1 hour valid

            // EIP-712 Sign
            const domain = {
                name: "CyeraMiningEngine",
                version: "1",
                chainId: 31337,
                verifyingContract: await miningEngine.getAddress(),
            };

            const types = {
                SellPortfolio: [
                    { name: "user", type: "address" },
                    { name: "caiAmount", type: "uint256" },
                    { name: "minUsdtOut", type: "uint256" },
                    { name: "nonce", type: "uint256" },
                    { name: "expiry", type: "uint256" },
                ],
            };

            const value = {
                user: investor.address,
                caiAmount: claimCai,
                minUsdtOut: minUsdtOut,
                nonce: nonce,
                expiry: expiry,
            };

            const signature = await backendSigner.signTypedData(domain, types, value);

            const userUsdtBefore = await mockUSDT.balanceOf(investor.address);

            // Execute sell
            await miningEngine.connect(investor).sellPortfolio(
                claimCai,
                minUsdtOut,
                nonce,
                expiry,
                signature
            );

            // User received USDT
            expect(await mockUSDT.balanceOf(investor.address)).to.be.gt(userUsdtBefore);

            // Nonce is consumed
            expect(await miningEngine.isNonceExecuted(investor.address, nonce)).to.equal(true);

            // Replay attack with same nonce MUST revert!
            await expect(
                miningEngine.connect(investor).sellPortfolio(
                    claimCai,
                    minUsdtOut,
                    nonce,
                    expiry,
                    signature
                )
            ).to.be.revertedWithCustomError(miningEngine, "NonceAlreadyUsed");
        });

        it("ROI Claim: Reverts on expired signature or invalid signer", async function () {
            const claimCai = ethers.parseEther("50");
            const expiredTimestamp = Math.floor(Date.now() / 1000) - 100; // Expired!

            const domain = {
                name: "CyeraMiningEngine",
                version: "1",
                chainId: 31337,
                verifyingContract: await miningEngine.getAddress(),
            };

            const types = {
                SellPortfolio: [
                    { name: "user", type: "address" },
                    { name: "caiAmount", type: "uint256" },
                    { name: "minUsdtOut", type: "uint256" },
                    { name: "nonce", type: "uint256" },
                    { name: "expiry", type: "uint256" },
                ],
            };

            // 1. Expired signature
            const sigExpired = await backendSigner.signTypedData(domain, types, {
                user: investor.address,
                caiAmount: claimCai,
                minUsdtOut: 0,
                nonce: 999,
                expiry: expiredTimestamp,
            });

            await expect(
                miningEngine.connect(investor).sellPortfolio(claimCai, 0, 999, expiredTimestamp, sigExpired)
            ).to.be.revertedWithCustomError(miningEngine, "SignatureExpired");

            // 2. Unauthorized signer
            const validExpiry = Math.floor(Date.now() / 1000) + 3600;
            const sigAttacker = await attacker.signTypedData(domain, types, {
                user: investor.address,
                caiAmount: claimCai,
                minUsdtOut: 0,
                nonce: 1001,
                expiry: validExpiry,
            });

            await expect(
                miningEngine.connect(investor).sellPortfolio(claimCai, 0, 1001, validExpiry, sigAttacker)
            ).to.be.revertedWithCustomError(miningEngine, "InvalidSignature");
        });
    });

    // =========================================================================
    // 4. USDT WITHDRAWAL VAULT TESTS
    // =========================================================================
    describe("4. USDTWithdrawalVault Tests", function () {
        it("Executes working income withdrawal with EIP-712 backend signature and protects against replay", async function () {
            // Fund vault with $10,000 USDT
            await mockUSDT.mint(await usdtVault.getAddress(), ethers.parseUnits("10000", 18));

            const withdrawAmount = ethers.parseUnits("500", 18);
            const withdrawalId = 88812;
            const expiry = Math.floor(Date.now() / 1000) + 3600;

            const domain = {
                name: "USDTWithdrawalVault",
                version: "1",
                chainId: 31337,
                verifyingContract: await usdtVault.getAddress(),
            };

            const types = {
                Withdrawal: [
                    { name: "recipient", type: "address" },
                    { name: "amount", type: "uint256" },
                    { name: "withdrawalId", type: "uint256" },
                    { name: "expiry", type: "uint256" },
                ],
            };

            const value = {
                recipient: userAlice.address,
                amount: withdrawAmount,
                withdrawalId: withdrawalId,
                expiry: expiry,
            };

            const signature = await backendSigner.signTypedData(domain, types, value);

            const aliceBefore = await mockUSDT.balanceOf(userAlice.address);

            // Execute withdrawal
            await usdtVault.executeWithdrawalWithSignature(
                userAlice.address,
                withdrawAmount,
                withdrawalId,
                expiry,
                signature
            );

            expect(await mockUSDT.balanceOf(userAlice.address) - aliceBefore).to.equal(withdrawAmount);
            expect(await usdtVault.processedWithdrawals(withdrawalId)).to.equal(true);

            // Duplicate withdrawal replay must revert
            await expect(
                usdtVault.executeWithdrawalWithSignature(
                    userAlice.address,
                    withdrawAmount,
                    withdrawalId,
                    expiry,
                    signature
                )
            ).to.be.revertedWith("WithdrawalVault: Withdrawal ID already processed");
        });
    });

    // =========================================================================
    // 5. PRODUCTION EXACT USER WALLETS VERIFICATION
    // =========================================================================
    describe("5. Exact User Production Wallets Test", function () {
        const USER_TRADING = ethers.getAddress("0xAC2aa717c6f4c18D2e4b1DD11aa17BFCA8cc8D53");
        const USER_P1 = ethers.getAddress("0xfb3548279b903dc2b371b059aeb1fe28ce5ae4f1");
        const USER_P2 = ethers.getAddress("0xA2007659Bc58791C51Ccdd31478c408b9064eEC0");
        const USER_P3 = ethers.getAddress("0x48dd4B393a9ad01788230ede2b73dC729Bcd0965");
        const USER_P4 = ethers.getAddress("0x7866964Ba833f5C3F16Bdf6bb78E62d86A798c88");
        const USER_P5 = ethers.getAddress("0xc321EAb16e72e523Af62c93129A0E4AC6693852d");
        const USER_RESERVE = ethers.getAddress("0x63BAF2Aa307DC8a456690a705c40d63194C0dd70");

        it("Deploys RevenueSplitter with user-supplied addresses and splits 100% cleanly", async function () {
            const userPartners = [USER_P1, USER_P2, USER_P3, USER_P4, USER_P5];
            const Factory = await ethers.getContractFactory("contracts/RevenueSplitter.sol:RevenueSplitter");
            const userSplitter = await Factory.deploy(
                await mockUSDT.getAddress(),
                USER_TRADING,
                userPartners,
                USER_RESERVE,
                deployer.address
            );
            await userSplitter.waitForDeployment();

            expect(await userSplitter.tradingWallet()).to.equal(USER_TRADING);
            expect(await userSplitter.reserveWallet()).to.equal(USER_RESERVE);
            const returnedPartners = await userSplitter.getPartnerWallets();
            expect(returnedPartners[0]).to.equal(USER_P1);
            expect(returnedPartners[1]).to.equal(USER_P2);
            expect(returnedPartners[2]).to.equal(USER_P3);
            expect(returnedPartners[3]).to.equal(USER_P4);
            expect(returnedPartners[4]).to.equal(USER_P5);

            // Send $3,200 USDT to simulate 32% cut from $10,000 package
            const splitInput = ethers.parseUnits("3200", 18);
            await mockUSDT.mint(await userSplitter.getAddress(), splitInput);

            await userSplitter.distribute();

            // Verify balances
            expect(await mockUSDT.balanceOf(USER_TRADING)).to.equal(ethers.parseUnits("2000", 18));
            expect(await mockUSDT.balanceOf(USER_P1)).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(USER_P2)).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(USER_P3)).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(USER_P4)).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(USER_P5)).to.equal(ethers.parseUnits("200", 18));
            expect(await mockUSDT.balanceOf(USER_RESERVE)).to.equal(ethers.parseUnits("200", 18));

            // Contract balance must be 0 (Zero-loss math)
            expect(await mockUSDT.balanceOf(await userSplitter.getAddress())).to.equal(0);
        });
    });
});
