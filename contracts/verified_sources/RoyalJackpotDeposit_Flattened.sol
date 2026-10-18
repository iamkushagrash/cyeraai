// Sources flattened with hardhat v2.29.1 https://hardhat.org

// SPDX-License-Identifier: MIT

// File contracts/RoyalJackpotDeposit.sol

// Original license: SPDX_License_Identifier: MIT
pragma solidity ^0.8.20;

interface IERC20 {
    function transfer(address to, uint256 value) external returns (bool);
    function transferFrom(address from, address to, uint256 value) external returns (bool);
    function balanceOf(address account) external view returns (uint256);
}

/**
 * @title RoyalJackpotDeposit
 * @notice Dedicated deposit collection contract for Cyera Royal Jackpot.
 * Completely decoupled and isolated from Cyera core MLM / staking logic.
 *
 * Tier 1: $10 USDT
 * Tier 2: $25 USDT
 * Tier 3: $50 USDT
 * Tier 4: $100 USDT
 */
contract RoyalJackpotDeposit {
    address public owner;
    address public pendingOwner;
    address public whitelistedCollector;
    IERC20 public immutable usdt;
    bool public autoForward;
    bool public paused;

    // Track total volume
    uint256 public totalDepositsCount;
    uint256 public totalDepositedUsdt;

    // Tier amounts in 18 decimals
    uint256 public constant TIER_1_AMOUNT = 10 * 1e18;
    uint256 public constant TIER_2_AMOUNT = 25 * 1e18;
    uint256 public constant TIER_3_AMOUNT = 50 * 1e18;
    uint256 public constant TIER_4_AMOUNT = 100 * 1e18;

    // Reentrancy guard
    uint256 private _status;
    uint256 private constant _NOT_ENTERED = 1;
    uint256 private constant _ENTERED = 2;

    event JackpotDeposited(
        address indexed user,
        uint256 indexed poolTier,
        uint256 indexed roundId,
        uint256 amount,
        uint256 timestamp
    );

    event WhitelistedCollectorUpdated(address indexed previousCollector, address indexed newCollector);
    event AutoForwardToggled(bool autoForward);
    event FundsCollected(address indexed recipient, uint256 amount, uint256 timestamp);
    event OwnershipTransferStarted(address indexed previousOwner, address indexed newOwner);
    event OwnershipTransferred(address indexed previousOwner, address indexed newOwner);
    event PausedStateChanged(bool isPaused);

    error ZeroAddress();
    error InvalidTierOrAmount();
    error ContractPaused();
    error NotOwner();
    error NotPendingOwner();
    error NotAuthorized();
    error TransferFailed();
    error ReentrancyGuardReentrantCall();
    error InsufficientBalance();

    modifier onlyOwner() {
        if (msg.sender != owner) revert NotOwner();
        _;
    }

    modifier onlyAuthorized() {
        if (msg.sender != owner && msg.sender != whitelistedCollector) revert NotAuthorized();
        _;
    }

    modifier whenNotPaused() {
        if (paused) revert ContractPaused();
        _;
    }

    modifier nonReentrant() {
        if (_status == _ENTERED) revert ReentrancyGuardReentrantCall();
        _status = _ENTERED;
        _;
        _status = _NOT_ENTERED;
    }

    constructor(
        address _usdt,
        address _initialCollector,
        address _initialOwner,
        bool _autoForward
    ) {
        if (_usdt == address(0) || _initialCollector == address(0) || _initialOwner == address(0)) {
            revert ZeroAddress();
        }

        usdt = IERC20(_usdt);
        whitelistedCollector = _initialCollector;
        owner = _initialOwner;
        autoForward = _autoForward;
        _status = _NOT_ENTERED;
    }

    /**
     * @notice Deposit into Royal Jackpot pool
     * @param poolTier Tier (1 = $10, 2 = $25, 3 = $50, 4 = $100)
     * @param roundId Active round identifier
     * @param amountUsdt Expected USDT amount in Wei
     */
    function depositJackpot(
        uint256 poolTier,
        uint256 roundId,
        uint256 amountUsdt
    ) external nonReentrant whenNotPaused {
        uint256 expectedAmount;
        if (poolTier == 1) {
            expectedAmount = TIER_1_AMOUNT;
        } else if (poolTier == 2) {
            expectedAmount = TIER_2_AMOUNT;
        } else if (poolTier == 3) {
            expectedAmount = TIER_3_AMOUNT;
        } else if (poolTier == 4) {
            expectedAmount = TIER_4_AMOUNT;
        } else {
            revert InvalidTierOrAmount();
        }

        if (amountUsdt != expectedAmount) {
            revert InvalidTierOrAmount();
        }

        // Pull USDT from user
        bool success = usdt.transferFrom(msg.sender, address(this), amountUsdt);
        if (!success) revert TransferFailed();

        totalDepositsCount++;
        totalDepositedUsdt += amountUsdt;

        emit JackpotDeposited(msg.sender, poolTier, roundId, amountUsdt, block.timestamp);

        // If auto-forward is enabled, immediately forward to whitelisted collector wallet
        if (autoForward && whitelistedCollector != address(0)) {
            bool fwdSuccess = usdt.transfer(whitelistedCollector, amountUsdt);
            if (!fwdSuccess) revert TransferFailed();
            emit FundsCollected(whitelistedCollector, amountUsdt, block.timestamp);
        }
    }

    /**
     * @notice Withdraw collected USDT to the whitelisted collector wallet or designated address
     */
    function withdrawCollectedFunds(address recipient, uint256 amount) external onlyAuthorized nonReentrant {
        if (recipient == address(0)) revert ZeroAddress();
        uint256 currentBal = usdt.balanceOf(address(this));
        if (amount > currentBal) revert InsufficientBalance();

        bool success = usdt.transfer(recipient, amount);
        if (!success) revert TransferFailed();

        emit FundsCollected(recipient, amount, block.timestamp);
    }

    /**
     * @notice Sweep all available USDT in contract to whitelisted collector
     */
    function sweepAllToCollector() external onlyAuthorized nonReentrant {
        uint256 currentBal = usdt.balanceOf(address(this));
        if (currentBal == 0) revert InsufficientBalance();

        bool success = usdt.transfer(whitelistedCollector, currentBal);
        if (!success) revert TransferFailed();

        emit FundsCollected(whitelistedCollector, currentBal, block.timestamp);
    }

    /**
     * @notice Update whitelisted collector wallet address
     */
    function setWhitelistedCollector(address _newCollector) external onlyOwner {
        if (_newCollector == address(0)) revert ZeroAddress();
        address old = whitelistedCollector;
        whitelistedCollector = _newCollector;
        emit WhitelistedCollectorUpdated(old, _newCollector);
    }

    /**
     * @notice Toggle auto-forwarding on deposit
     */
    function setAutoForward(bool _autoForward) external onlyOwner {
        autoForward = _autoForward;
        emit AutoForwardToggled(_autoForward);
    }

    /**
     * @notice Pause/unpause deposits
     */
    function setPaused(bool _paused) external onlyOwner {
        paused = _paused;
        emit PausedStateChanged(_paused);
    }

    // Two-step ownership transfer
    function transferOwnership(address newOwner) external onlyOwner {
        if (newOwner == address(0)) revert ZeroAddress();
        pendingOwner = newOwner;
        emit OwnershipTransferStarted(owner, newOwner);
    }

    function acceptOwnership() external {
        if (msg.sender != pendingOwner) revert NotPendingOwner();
        address oldOwner = owner;
        owner = pendingOwner;
        pendingOwner = address(0);
        emit OwnershipTransferred(oldOwner, owner);
    }
}
