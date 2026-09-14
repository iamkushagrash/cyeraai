// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import {Ownable} from "@openzeppelin/contracts/access/Ownable.sol";

interface IERC20 {
    function balanceOf(address account) external view returns (uint256);
    function transfer(address to, uint256 value) external returns (bool);
}

/**
 * @title TreasurySplitter
 * @notice Receives 70% treasury funds from CyeraMiningEngine and splits into:
 *         - 65% of total investment (65/70ths of incoming USDT) -> Owner Main Wallet
 *         - 5% of total investment (5/70ths of incoming USDT)  -> Secondary Wallet
 */
contract TreasurySplitter is Ownable {
    IERC20 public immutable usdt;

    address public ownerWallet;
    address public secondaryWallet;

    // Share weights: 65 parts and 5 parts (Total 70 parts)
    uint256 public constant OWNER_SHARE_WEIGHT = 65;
    uint256 public constant SECONDARY_SHARE_WEIGHT = 5;
    uint256 public constant TOTAL_WEIGHT = 70;

    // Track total distributed amounts
    uint256 public totalDistributedUsdt;
    uint256 public totalOwnerPaid;
    uint256 public totalSecondaryPaid;

    event FundsDistributed(
        uint256 totalAmount,
        uint256 ownerAmount,
        uint256 secondaryAmount,
        uint256 timestamp
    );
    event WalletsUpdated(address indexed newOwnerWallet, address indexed newSecondaryWallet);

    error ZeroAddress();
    error NoBalanceToDistribute();
    error TransferFailed();

    constructor(
        address _usdt,
        address _ownerWallet,
        address _secondaryWallet,
        address _initialOwner
    ) Ownable(_initialOwner) {
        if (
            _usdt == address(0) ||
            _ownerWallet == address(0) ||
            _secondaryWallet == address(0) ||
            _initialOwner == address(0)
        ) {
            revert ZeroAddress();
        }

        usdt = IERC20(_usdt);
        ownerWallet = _ownerWallet;
        secondaryWallet = _secondaryWallet;
    }

    /**
     * @notice Distributes all USDT currently in the splitter to Owner and Secondary wallets.
     * @dev Can be called permissionlessly by anyone, automated cron, or backend.
     */
    function distribute() public returns (uint256 ownerAmount, uint256 secondaryAmount) {
        uint256 balance = usdt.balanceOf(address(this));
        if (balance == 0) revert NoBalanceToDistribute();

        // 65/70 to Owner Wallet, remainder to Secondary Wallet (Exact 0-loss split)
        ownerAmount = (balance * OWNER_SHARE_WEIGHT) / TOTAL_WEIGHT;
        secondaryAmount = balance - ownerAmount;

        totalDistributedUsdt += balance;
        totalOwnerPaid += ownerAmount;
        totalSecondaryPaid += secondaryAmount;

        bool successOwner = usdt.transfer(ownerWallet, ownerAmount);
        if (!successOwner) revert TransferFailed();

        bool successSecondary = usdt.transfer(secondaryWallet, secondaryAmount);
        if (!successSecondary) revert TransferFailed();

        emit FundsDistributed(balance, ownerAmount, secondaryAmount, block.timestamp);
    }

    /**
     * @notice Update destination wallets.
     */
    function setWallets(address _newOwnerWallet, address _newSecondaryWallet) external onlyOwner {
        if (_newOwnerWallet == address(0) || _newSecondaryWallet == address(0)) {
            revert ZeroAddress();
        }
        ownerWallet = _newOwnerWallet;
        secondaryWallet = _newSecondaryWallet;
        emit WalletsUpdated(_newOwnerWallet, _newSecondaryWallet);
    }

    /**
     * @notice Emergency rescue for non-USDT tokens sent accidentally.
     */
    function rescueTokens(address token, address to, uint256 amount) external onlyOwner {
        if (to == address(0)) revert ZeroAddress();
        IERC20(token).transfer(to, amount);
    }
}
