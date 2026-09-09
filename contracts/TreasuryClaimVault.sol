// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

interface IERC20 {
    function transfer(address to, uint256 value) external returns (bool);
    function balanceOf(address account) external view returns (uint256);
}

/**
 * @dev OpenZeppelin SafeERC20 implementation.
 */
library SafeERC20 {
    function safeTransfer(IERC20 token, address to, uint256 value) internal {
        _callOptionalReturn(token, abi.encodeWithSelector(token.transfer.selector, to, value));
    }

    function _callOptionalReturn(IERC20 token, bytes memory data) private {
        (bool success, bytes memory returndata) = address(token).call(data);
        require(success, "SafeERC20: low-level call failed");

        if (returndata.length > 0) {
            require(abi.decode(returndata, (bool)), "SafeERC20: ERC20 operation did not succeed");
        }
    }
}

abstract contract Context {
    function _msgSender() internal view virtual returns (address) {
        return msg.sender;
    }
}

/**
 * @dev 2-Step Ownable contract. Ownership is intended to be assigned to a Admin Owner.
 */
abstract contract Ownable2Step is Context {
    address private _owner;
    address private _pendingOwner;

    event OwnershipTransferStarted(address indexed previousOwner, address indexed newOwner);
    event OwnershipTransferred(address indexed previousOwner, address indexed newOwner);

    constructor(address initialOwner) {
        require(initialOwner != address(0), "Ownable: initial owner is the zero address");
        _transferOwnership(initialOwner);
    }

    function owner() public view virtual returns (address) {
        return _owner;
    }

    function pendingOwner() public view virtual returns (address) {
        return _pendingOwner;
    }

    modifier onlyOwner() {
        require(owner() == _msgSender(), "Ownable: caller is not the owner");
        _;
    }

    function transferOwnership(address newOwner) public virtual onlyOwner {
        require(newOwner != address(0), "Ownable: new owner is the zero address");
        _pendingOwner = newOwner;
        emit OwnershipTransferStarted(owner(), newOwner);
    }

    function acceptOwnership() public virtual {
        require(pendingOwner() == _msgSender(), "Ownable2Step: caller is not the new owner");
        _transferOwnership(_pendingOwner);
        _pendingOwner = address(0);
    }

    function _transferOwnership(address newOwner) internal virtual {
        address oldOwner = _owner;
        _owner = newOwner;
        emit OwnershipTransferred(oldOwner, newOwner);
    }
}

abstract contract ReentrancyGuard {
    uint256 private constant _NOT_ENTERED = 1;
    uint256 private constant _ENTERED = 2;
    uint256 private _status;

    constructor() {
        _status = _NOT_ENTERED;
    }

    modifier nonReentrant() {
        require(_status != _ENTERED, "ReentrancyGuard: reentrant call");
        _status = _ENTERED;
        _;
        _status = _NOT_ENTERED;
    }
}

abstract contract Pausable is Context {
    event Paused(address account);
    event Unpaused(address account);

    bool private _paused;

    constructor() {
        _paused = false;
    }

    modifier whenNotPaused() {
        require(!_paused, "Pausable: paused");
        _;
    }

    modifier whenPaused() {
        require(_paused, "Pausable: not paused");
        _;
    }

    function paused() public view virtual returns (bool) {
        return _paused;
    }

    function _pause() internal virtual whenNotPaused {
        _paused = true;
        emit Paused(_msgSender());
    }

    function _unpause() internal virtual whenPaused {
        _paused = false;
        emit Unpaused(_msgSender());
    }
}

/**
 * @title TreasuryClaimVault
 * @notice Holds the 70% USDT treasury fund. 
 *         Only authorized Admin Owner can claim or release funds.
 * @dev Employs OpenZeppelin SafeERC20. `rescueTokens` is strictly forbidden from touching treasury USDT.
 */
contract TreasuryClaimVault is Context, Ownable2Step, ReentrancyGuard, Pausable {
    using SafeERC20 for IERC20;

    IERC20 public immutable usdtToken;

    // Events
    event FundsClaimed(address indexed recipient, uint256 amount, string reason, uint256 timestamp);
    event EmergencyTokensRecovered(address indexed token, address indexed to, uint256 amount);

    constructor(address _usdtToken, address _initialOwner) Ownable2Step(_initialOwner) {
        require(_usdtToken != address(0), "TreasuryVault: USDT token cannot be zero address");
        usdtToken = IERC20(_usdtToken);
    }

    /**
     * @notice Get current USDT balance in the vault.
     */
    function getVaultBalance() external view returns (uint256) {
        return usdtToken.balanceOf(address(this));
    }

    /**
     * @notice Transfer funds from Treasury Vault to authorized destination.
     * @param recipient The destination address.
     * @param amount The USDT amount to transfer.
     * @param reason Description/reason for record keeping.
     */
    function transferFunds(
        address recipient,
        uint256 amount,
        string calldata reason
    ) external onlyOwner nonReentrant whenNotPaused {
        require(recipient != address(0), "TreasuryVault: Recipient cannot be zero address");
        require(amount > 0, "TreasuryVault: Amount must be greater than zero");
        require(usdtToken.balanceOf(address(this)) >= amount, "TreasuryVault: Insufficient balance");

        usdtToken.safeTransfer(recipient, amount);
        emit FundsClaimed(recipient, amount, reason, block.timestamp);
    }

    /**
     * @notice Rescue accidentally sent non-treasury tokens.
     * @dev Blocked from withdrawing treasury USDT (Treasury USDT can only move via `transferFunds`).
     */
    function rescueTokens(address tokenAddress, address to, uint256 amount) external onlyOwner nonReentrant {
        require(to != address(0), "TreasuryVault: Destination cannot be zero address");
        require(tokenAddress != address(0), "TreasuryVault: Token cannot be zero address");
        require(tokenAddress != address(usdtToken), "TreasuryVault: Cannot rescue treasury USDT");
        
        IERC20(tokenAddress).safeTransfer(to, amount);
        emit EmergencyTokensRecovered(tokenAddress, to, amount);
    }

    function pause() external onlyOwner {
        _pause();
    }

    function unpause() external onlyOwner {
        _unpause();
    }
}
