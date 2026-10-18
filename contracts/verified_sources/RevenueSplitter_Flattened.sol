// Sources flattened with hardhat v2.29.1 https://hardhat.org

// SPDX-License-Identifier: MIT

// File @openzeppelin/contracts/utils/Context.sol@v5.6.1

// Original license: SPDX_License_Identifier: MIT
// OpenZeppelin Contracts (last updated v5.0.1) (utils/Context.sol)

pragma solidity ^0.8.20;

/**
 * @dev Provides information about the current execution context, including the
 * sender of the transaction and its data. While these are generally available
 * via msg.sender and msg.data, they should not be accessed in such a direct
 * manner, since when dealing with meta-transactions the account sending and
 * paying for execution may not be the actual sender (as far as an application
 * is concerned).
 *
 * This contract is only required for intermediate, library-like contracts.
 */
abstract contract Context {
    function _msgSender() internal view virtual returns (address) {
        return msg.sender;
    }

    function _msgData() internal view virtual returns (bytes calldata) {
        return msg.data;
    }

    function _contextSuffixLength() internal view virtual returns (uint256) {
        return 0;
    }
}


// File @openzeppelin/contracts/access/Ownable.sol@v5.6.1

// Original license: SPDX_License_Identifier: MIT
// OpenZeppelin Contracts (last updated v5.0.0) (access/Ownable.sol)

pragma solidity ^0.8.20;

/**
 * @dev Contract module which provides a basic access control mechanism, where
 * there is an account (an owner) that can be granted exclusive access to
 * specific functions.
 *
 * The initial owner is set to the address provided by the deployer. This can
 * later be changed with {transferOwnership}.
 *
 * This module is used through inheritance. It will make available the modifier
 * `onlyOwner`, which can be applied to your functions to restrict their use to
 * the owner.
 */
abstract contract Ownable is Context {
    address private _owner;

    /**
     * @dev The caller account is not authorized to perform an operation.
     */
    error OwnableUnauthorizedAccount(address account);

    /**
     * @dev The owner is not a valid owner account. (eg. `address(0)`)
     */
    error OwnableInvalidOwner(address owner);

    event OwnershipTransferred(address indexed previousOwner, address indexed newOwner);

    /**
     * @dev Initializes the contract setting the address provided by the deployer as the initial owner.
     */
    constructor(address initialOwner) {
        if (initialOwner == address(0)) {
            revert OwnableInvalidOwner(address(0));
        }
        _transferOwnership(initialOwner);
    }

    /**
     * @dev Throws if called by any account other than the owner.
     */
    modifier onlyOwner() {
        _checkOwner();
        _;
    }

    /**
     * @dev Returns the address of the current owner.
     */
    function owner() public view virtual returns (address) {
        return _owner;
    }

    /**
     * @dev Throws if the sender is not the owner.
     */
    function _checkOwner() internal view virtual {
        if (owner() != _msgSender()) {
            revert OwnableUnauthorizedAccount(_msgSender());
        }
    }

    /**
     * @dev Leaves the contract without owner. It will not be possible to call
     * `onlyOwner` functions. Can only be called by the current owner.
     *
     * NOTE: Renouncing ownership will leave the contract without an owner,
     * thereby disabling any functionality that is only available to the owner.
     */
    function renounceOwnership() public virtual onlyOwner {
        _transferOwnership(address(0));
    }

    /**
     * @dev Transfers ownership of the contract to a new account (`newOwner`).
     * Can only be called by the current owner.
     */
    function transferOwnership(address newOwner) public virtual onlyOwner {
        if (newOwner == address(0)) {
            revert OwnableInvalidOwner(address(0));
        }
        _transferOwnership(newOwner);
    }

    /**
     * @dev Transfers ownership of the contract to a new account (`newOwner`).
     * Internal function without access restriction.
     */
    function _transferOwnership(address newOwner) internal virtual {
        address oldOwner = _owner;
        _owner = newOwner;
        emit OwnershipTransferred(oldOwner, newOwner);
    }
}


// File contracts/RevenueSplitter.sol

// Original license: SPDX_License_Identifier: MIT
pragma solidity ^0.8.24;

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
