// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import {Ownable} from "@openzeppelin/contracts/access/Ownable.sol";

interface IERC20 {
    function balanceOf(address account) external view returns (uint256);
    function transfer(address to, uint256 value) external returns (bool);
    function transferFrom(address from, address to, uint256 value) external returns (bool);
}

/**
 * @title RevenueSplitter
 * @notice Receives 32% of total investment USDT from CyeraMiningEngine and splits into:
 *         - 20% of total investment (20/32 of incoming USDT) -> Trading Operations Wallet
 *         - 2% of total investment (2/32 of incoming USDT)  -> Partner 1
 *         - 2% of total investment (2/32 of incoming USDT)  -> Partner 2
 *         - 2% of total investment (2/32 of incoming USDT)  -> Partner 3
 *         - 2% of total investment (2/32 of incoming USDT)  -> Partner 4
 *         - 2% of total investment (2/32 of incoming USDT)  -> Partner 5
 *         - 2% of total investment (2/32 of incoming USDT)  -> Operations / Reserve Wallet
 *
 * All wallet addresses are updatable exclusively by the contract Owner.
 */
contract RevenueSplitter is Ownable {
    IERC20 public immutable usdt;

    address public tradingWallet;
    address[5] public partnerWallets;
    address public reserveWallet;

    // Weight allocation (Total = 32 parts corresponding to 32% of package)
    uint256 public constant TRADING_WEIGHT = 20; // 20%
    uint256 public constant PARTNER_WEIGHT = 2;  // 2% each (x 5 = 10%)
    uint256 public constant RESERVE_WEIGHT = 2;  // 2%
    uint256 public constant TOTAL_WEIGHT   = 32; // 32%

    // Volume metrics
    uint256 public totalDistributedUsdt;
    uint256 public totalTradingPaid;
    uint256 public totalPartnersPaid;
    uint256 public totalReservePaid;

    event RevenueDistributed(
        uint256 totalAmount,
        uint256 tradingAmount,
        uint256 partnerAmountEach,
        uint256 reserveAmount,
        uint256 timestamp
    );

    event TradingWalletUpdated(address indexed oldWallet, address indexed newWallet);
    event PartnerWalletUpdated(uint8 indexed index, address indexed oldWallet, address indexed newWallet);
    event ReserveWalletUpdated(address indexed oldWallet, address indexed newWallet);

    error ZeroAddress();
    error InvalidPartnerIndex();
    error NoBalanceToDistribute();
    error TransferFailed();

    constructor(
        address _usdt,
        address _tradingWallet,
        address[5] memory _partnerWallets,
        address _reserveWallet,
        address _initialOwner
    ) Ownable(_initialOwner) {
        if (
            _usdt == address(0) ||
            _tradingWallet == address(0) ||
            _reserveWallet == address(0) ||
            _initialOwner == address(0)
        ) {
            revert ZeroAddress();
        }

        for (uint8 i = 0; i < 5; i++) {
            if (_partnerWallets[i] == address(0)) {
                revert ZeroAddress();
            }
        }

        usdt = IERC20(_usdt);
        tradingWallet = _tradingWallet;
        partnerWallets = _partnerWallets;
        reserveWallet = _reserveWallet;
    }

    /**
     * @notice Distributes all USDT currently held in this contract according to the 20/2/2/2/2/2/2 formula.
     * @dev Can be called automatically by MiningEngine or permissionlessly by anyone.
     */
    function distribute() public returns (
        uint256 tradingAmount,
        uint256 partnerAmountEach,
        uint256 reserveAmount
    ) {
        uint256 balance = usdt.balanceOf(address(this));
        if (balance == 0) revert NoBalanceToDistribute();

        // 20/32 to Trading Wallet
        tradingAmount = (balance * TRADING_WEIGHT) / TOTAL_WEIGHT;

        // 2/32 to each of the 5 Partner Wallets
        partnerAmountEach = (balance * PARTNER_WEIGHT) / TOTAL_WEIGHT;

        // Remainder to Reserve Wallet (guarantees exact 0-loss distribution)
        uint256 totalAllocated = tradingAmount + (partnerAmountEach * 5);
        reserveAmount = balance - totalAllocated;

        totalDistributedUsdt += balance;
        totalTradingPaid += tradingAmount;
        totalPartnersPaid += (partnerAmountEach * 5);
        totalReservePaid += reserveAmount;

        // Execute transfers
        bool success = usdt.transfer(tradingWallet, tradingAmount);
        if (!success) revert TransferFailed();

        for (uint8 i = 0; i < 5; i++) {
            success = usdt.transfer(partnerWallets[i], partnerAmountEach);
            if (!success) revert TransferFailed();
        }

        if (reserveAmount > 0) {
            success = usdt.transfer(reserveWallet, reserveAmount);
            if (!success) revert TransferFailed();
        }

        emit RevenueDistributed(balance, tradingAmount, partnerAmountEach, reserveAmount, block.timestamp);
    }

    /**
     * @notice Admin update for Trading Operations Wallet.
     */
    function setTradingWallet(address _newTrading) external onlyOwner {
        if (_newTrading == address(0)) revert ZeroAddress();
        address old = tradingWallet;
        tradingWallet = _newTrading;
        emit TradingWalletUpdated(old, _newTrading);
    }

    /**
     * @notice Admin update for a specific Partner Wallet (Index 0 to 4).
     */
    function setPartnerWallet(uint8 index, address _newPartner) external onlyOwner {
        if (index >= 5) revert InvalidPartnerIndex();
        if (_newPartner == address(0)) revert ZeroAddress();
        address old = partnerWallets[index];
        partnerWallets[index] = _newPartner;
        emit PartnerWalletUpdated(index, old, _newPartner);
    }

    /**
     * @notice Admin update for Reserve Wallet.
     */
    function setReserveWallet(address _newReserve) external onlyOwner {
        if (_newReserve == address(0)) revert ZeroAddress();
        address old = reserveWallet;
        reserveWallet = _newReserve;
        emit ReserveWalletUpdated(old, _newReserve);
    }

    /**
     * @notice Returns all 5 partner wallet addresses.
     */
    function getPartnerWallets() external view returns (address[5] memory) {
        return partnerWallets;
    }

    /**
     * @notice Emergency recovery for non-USDT tokens sent accidentally.
     */
    function rescueTokens(address token, address to, uint256 amount) external onlyOwner {
        if (to == address(0)) revert ZeroAddress();
        IERC20(token).transfer(to, amount);
    }
}
