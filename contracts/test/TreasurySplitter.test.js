import { expect } from "chai";
import hardhat from "hardhat";
const { ethers } = hardhat;

describe("TreasurySplitter", function () {
    let deployer, ownerWallet, secondaryWallet, user;
    let mockUsdt, splitter;

    beforeEach(async function () {
        [deployer, ownerWallet, secondaryWallet, user] = await ethers.getSigners();

        // Deploy MockUSDT
        const MockUSDT = await ethers.getContractFactory("MockUSDT");
        mockUsdt = await MockUSDT.deploy();
        await mockUsdt.waitForDeployment();

        // Deploy TreasurySplitter
        const TreasurySplitter = await ethers.getContractFactory("TreasurySplitter");
        splitter = await TreasurySplitter.deploy(
            await mockUsdt.getAddress(),
            ownerWallet.address,
            secondaryWallet.address,
            deployer.address
        );
        await splitter.waitForDeployment();
    });

    it("Should split 70 USDT incoming funds into 65 USDT (Owner) and 5 USDT (Secondary)", async function () {
        const amount70Usdt = ethers.parseUnits("70", 18);

        // Send 70 USDT to TreasurySplitter
        await mockUsdt.mint(await splitter.getAddress(), amount70Usdt);

        const ownerBefore = await mockUsdt.balanceOf(ownerWallet.address);
        const secBefore = await mockUsdt.balanceOf(secondaryWallet.address);

        // Trigger distribute
        await splitter.distribute();

        const ownerAfter = await mockUsdt.balanceOf(ownerWallet.address);
        const secAfter = await mockUsdt.balanceOf(secondaryWallet.address);

        expect(ownerAfter - ownerBefore).to.equal(ethers.parseUnits("65", 18));
        expect(secAfter - secBefore).to.equal(ethers.parseUnits("5", 18));
    });

    it("Should correctly handle proportional split on 700 USDT (650 to Owner / 50 to Secondary)", async function () {
        const amount700Usdt = ethers.parseUnits("700", 18);

        await mockUsdt.mint(await splitter.getAddress(), amount700Usdt);
        await splitter.distribute();

        expect(await mockUsdt.balanceOf(ownerWallet.address)).to.equal(ethers.parseUnits("650", 18));
        expect(await mockUsdt.balanceOf(secondaryWallet.address)).to.equal(ethers.parseUnits("50", 18));
    });
});
