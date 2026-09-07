// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

interface IERC20 {
    function transfer(address to, uint256 value) external returns (bool);
    function transferFrom(address from, address to, uint256 value) external returns (bool);
    function balanceOf(address account) external view returns (uint256);
}

interface IERC20Metadata is IERC20 {
    function decimals() external view returns (uint8);
}

/**
 * @dev OpenZeppelin SafeERC20 implementation to handle non-standard ERC20 tokens safely.
 */
library SafeERC20 {
    function safeTransfer(IERC20 token, address to, uint256 value) internal {
        _callOptionalReturn(token, abi.encodeWithSelector(token.transfer.selector, to, value));
    }

    function safeTransferFrom(IERC20 token, address from, address to, uint256 value) internal {
        _callOptionalReturn(token, abi.encodeWithSelector(token.transferFrom.selector, from, to, value));
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
 * @title CyeraInvestmentSplitter
 * @notice Accepts user USDT investments ($50 - $2,000 USDT) via invest() action and atomically executes:
 *         1. Single transferFrom (User -> Splitter Contract)
 *         2. 70% safeTransfer -> TreasuryClaimVault
 *         3. 30% safeTransfer -> LiquidityTreasuryWallet
 * @dev BscScan Method displays clean 'invest' action and emits standard Invested event.
 */
contract CyeraInvestmentSplitter is Context, Ownable2Step, ReentrancyGuard, Pausable {
    using SafeERC20 for IERC20;

    IERC20 public immutable usdtToken;
    uint8 public immutable usdtDecimals;

    address public treasuryClaimVault;
    address public liquidityTreasuryWallet;

    uint256 public minInvestment;
    uint256 public maxInvestment;
    uint256 public totalInvested;
    uint256 public investmentCount;

    // Clean user-facing events
    event Invested(
        address indexed user,
        uint256 amountUSDT,
        uint256 timestamp,
        uint256 investmentId
    );
    event TreasuryVaultUpdated(address indexed oldVault, address indexed newVault);
    event LiquidityWalletUpdated(address indexed oldWallet, address indexed newWallet);
    event InvestmentLimitsUpdated(uint256 minInvestment, uint256 maxInvestment);

    constructor(
        address _usdtToken,
        address _treasuryClaimVault,
        address _liquidityTreasuryWallet,
        address _initialOwner
    ) Ownable2Step(_initialOwner) {
        require(_usdtToken != address(0), "Splitter: USDT token cannot be zero");
        require(_treasuryClaimVault != address(0), "Splitter: Treasury vault cannot be zero");
        require(_liquidityTreasuryWallet != address(0), "Splitter: Liquidity wallet cannot be zero");

        usdtToken = IERC20(_usdtToken);
        treasuryClaimVault = _treasuryClaimVault;
        liquidityTreasuryWallet = _liquidityTreasuryWallet;

        // Query token decimals safely
        uint8 dec = 18;
        try IERC20Metadata(_usdtToken).decimals() returns (uint8 _dec) {
            dec = _dec;
        } catch {}
        usdtDecimals = dec;

        // Default $50 to $2,000 USDT limits scaled with decimals
        minInvestment = 50 * (10 ** dec);
        maxInvestment = 2_000 * (10 ** dec);
    }

    /**
     * @notice User investment entry point for CYERA.
     * @dev Executes single transferFrom into contract, then immediately distributes 70% and 30%.
     * @param amount USDT amount with token decimals.
     */
    function invest(uint256 amount) external nonReentrant whenNotPaused {
        require(amount >= minInvestment, "Splitter: Amount is below minimum investment ($50)");
        require(amount <= maxInvestment, "Splitter: Amount exceeds maximum investment ($2,000)");

        // Calculate 70% and 30% split internally
        uint256 treasuryAmount = (amount * 70) / 100;
        uint256 liquidityAmount = amount - treasuryAmount; // Exact 30% without rounding loss

        investmentCount++;
        totalInvested += amount;

        // 1. Single safe transfer from user to this splitter contract
        usdtToken.safeTransferFrom(_msgSender(), address(this), amount);

        // 2. Immediate distribution
        usdtToken.safeTransfer(treasuryClaimVault, treasuryAmount);
        usdtToken.safeTransfer(liquidityTreasuryWallet, liquidityAmount);

        // 3. Emit clean user investment event
        emit Invested(
            _msgSender(),
            amount,
            block.timestamp,
            investmentCount
        );
    }

    // --- Admin Configuration Functions (Controlled by Admin Owner) ---

    function setTreasuryClaimVault(address _vault) external onlyOwner {
        require(_vault != address(0), "Splitter: Cannot set zero address");
        emit TreasuryVaultUpdated(treasuryClaimVault, _vault);
        treasuryClaimVault = _vault;
    }

    function setLiquidityTreasuryWallet(address _wallet) external onlyOwner {
        require(_wallet != address(0), "Splitter: Cannot set zero address");
        emit LiquidityWalletUpdated(liquidityTreasuryWallet, _wallet);
        liquidityTreasuryWallet = _wallet;
    }

    function setInvestmentLimits(uint256 _min, uint256 _max) external onlyOwner {
        require(_min > 0, "Splitter: Min investment must be > 0");
        require(_max >= _min, "Splitter: Max investment must be >= min investment");
        minInvestment = _min;
        maxInvestment = _max;
        emit InvestmentLimitsUpdated(_min, _max);
    }

    function pause() external onlyOwner {
        _pause();
    }

    function unpause() external onlyOwner {
        _unpause();
    }
}
