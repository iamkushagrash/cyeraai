// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

/**
 * @title CAIToken (Cyera Token)
 * @notice Fixed supply BEP-20 / ERC-20 token on BNB Smart Chain.
 * @dev Enforces AMM / PancakeSwap Whitelist Protection:
 *      - BUY (from any registered AMM Pair): Only whitelisted wallets can buy.
 *      - SELL (to any registered AMM Pair): Unrestricted, open to all token holders.
 *      - Primary PancakeSwap pair can be permanently locked after initialization.
 *      - Protection against unauthorized / alternate AMM pair bypassing.
 */

interface IERC20 {
    event Transfer(address indexed from, address indexed to, uint256 value);
    event Approval(address indexed owner, address indexed spender, uint256 value);

    function totalSupply() external view returns (uint256);
    function balanceOf(address account) external view returns (uint256);
    function transfer(address to, uint256 value) external returns (bool);
    function allowance(address owner, address spender) external view returns (uint256);
    function approve(address spender, uint256 value) external returns (bool);
    function transferFrom(address from, address to, uint256 value) external returns (bool);
}

interface IERC20Metadata is IERC20 {
    function name() external view returns (string memory);
    function symbol() external view returns (string memory);
    function decimals() external view returns (uint8);
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

contract CAIToken is Context, IERC20, IERC20Metadata, Ownable2Step, Pausable {
    mapping(address => uint256) private _balances;
    mapping(address => mapping(address => uint256)) private _allowances;

    uint256 private constant _TOTAL_SUPPLY = 300_000 * 10**18; // Exactly 300,000 CAI (Fixed)
    string private constant _NAME = "Cyera";
    string private constant _SYMBOL = "CAI";
    uint8 private constant _DECIMALS = 18;

    // Official PancakeSwap DEX Integration & Alternate AMM Protection
    address public pancakePair;
    bool public pancakePairLocked;
    mapping(address => bool) public isAMMPair;
    mapping(address => bool) public isWhitelisted;

    // Events
    event WhitelistUpdated(address indexed account, bool status);
    event BatchWhitelistUpdated(uint256 totalUpdated, bool status);
    event PancakePairUpdated(address indexed pair);
    event PancakePairLocked(address indexed pair);
    event AMMPairStatusUpdated(address indexed pair, bool isPair);

    /**
     * @param treasuryWallet The initial recipient of the entire 300,000 CAI supply.
     * @param initialOwner The Admin Owner governance address.
     */
    constructor(address treasuryWallet, address initialOwner) Ownable2Step(initialOwner) {
        require(treasuryWallet != address(0), "CAI: Treasury wallet cannot be zero address");
        
        _balances[treasuryWallet] = _TOTAL_SUPPLY;
        emit Transfer(address(0), treasuryWallet, _TOTAL_SUPPLY);

        // Auto-whitelist treasury and owner for seamless initial liquidity provisioning
        isWhitelisted[treasuryWallet] = true;
        isWhitelisted[initialOwner] = true;
        emit WhitelistUpdated(treasuryWallet, true);
        emit WhitelistUpdated(initialOwner, true);
    }

    function name() public pure override returns (string memory) {
        return _NAME;
    }

    function symbol() public pure override returns (string memory) {
        return _SYMBOL;
    }

    function decimals() public pure override returns (uint8) {
        return _DECIMALS;
    }

    function totalSupply() public pure override returns (uint256) {
        return _TOTAL_SUPPLY;
    }

    function balanceOf(address account) public view override returns (uint256) {
        return _balances[account];
    }

    function transfer(address to, uint256 value) public override returns (bool) {
        _transfer(_msgSender(), to, value);
        return true;
    }

    function allowance(address tokenOwner, address spender) public view override returns (uint256) {
        return _allowances[tokenOwner][spender];
    }

    function approve(address spender, uint256 value) public override returns (bool) {
        _approve(_msgSender(), spender, value);
        return true;
    }

    function transferFrom(address from, address to, uint256 value) public override returns (bool) {
        _spendAllowance(from, _msgSender(), value);
        _transfer(from, to, value);
        return true;
    }

    /**
     * @notice Set official PancakeSwap pair address (only before locking).
     */
    function setPancakePair(address _pair) external onlyOwner {
        require(!pancakePairLocked, "CAI: PancakeSwap pair is permanently locked");
        require(_pair != address(0), "CAI: Pair cannot be zero address");
        
        if (pancakePair != address(0)) {
            isAMMPair[pancakePair] = false;
        }

        pancakePair = _pair;
        isAMMPair[_pair] = true;
        emit PancakePairUpdated(_pair);
        emit AMMPairStatusUpdated(_pair, true);
    }

    /**
     * @notice Permanently lock the official PancakeSwap pair address.
     */
    function lockPancakePair() external onlyOwner {
        require(pancakePair != address(0), "CAI: Pair address is not set yet");
        require(!pancakePairLocked, "CAI: Pair is already locked");
        pancakePairLocked = true;
        emit PancakePairLocked(pancakePair);
    }

    /**
     * @notice Register or unregister secondary AMM pairs (e.g. PancakeSwap V3, ApeSwap, Biswap)
     *         to prevent unauthorized pair creation from bypassing buy whitelist protection.
     */
    function setAMMPair(address _pair, bool _isPair) external onlyOwner {
        require(_pair != address(0), "CAI: Cannot set zero address as AMM pair");
        if (_pair == pancakePair && pancakePairLocked && !_isPair) {
            revert("CAI: Cannot unregister locked official PancakeSwap pair");
        }
        isAMMPair[_pair] = _isPair;
        emit AMMPairStatusUpdated(_pair, _isPair);
    }

    /**
     * @notice Add or remove a wallet from the buy whitelist.
     */
    function setWhitelist(address account, bool status) external onlyOwner {
        require(account != address(0), "CAI: Cannot whitelist zero address");
        isWhitelisted[account] = status;
        emit WhitelistUpdated(account, status);
    }

    /**
     * @notice Batch whitelist multiple wallets.
     */
    function setBatchWhitelist(address[] calldata accounts, bool status) external onlyOwner {
        for (uint256 i = 0; i < accounts.length; i++) {
            require(accounts[i] != address(0), "CAI: Cannot whitelist zero address");
            isWhitelisted[accounts[i]] = status;
        }
        emit BatchWhitelistUpdated(accounts.length, status);
    }

    /**
     * @dev Internal transfer logic enforcing AMM Whitelist on Buys & Pausable control.
     *      - If `from` is a registered AMM Pair (Buy): `to` must be whitelisted.
     *      - If `to` is a registered AMM Pair (Sell): Always allowed (unrestricted for all holders).
     */
    function _transfer(address from, address to, uint256 value) internal whenNotPaused {
        require(from != address(0), "ERC20: transfer from the zero address");
        require(to != address(0), "ERC20: transfer to the zero address");

        // Enforce whitelist check on any registered AMM Pair Buys
        if (isAMMPair[from]) {
            require(isWhitelisted[to], "CAI: Buyer is not whitelisted for DEX purchases");
        }

        uint256 fromBalance = _balances[from];
        require(fromBalance >= value, "ERC20: transfer amount exceeds balance");
        unchecked {
            _balances[from] = fromBalance - value;
            _balances[to] += value;
        }

        emit Transfer(from, to, value);
    }

    function _approve(address tokenOwner, address spender, uint256 value) internal {
        require(tokenOwner != address(0), "ERC20: approve from the zero address");
        require(spender != address(0), "ERC20: approve to the zero address");

        _allowances[tokenOwner][spender] = value;
        emit Approval(tokenOwner, spender, value);
    }

    function _spendAllowance(address tokenOwner, address spender, uint256 value) internal {
        uint256 currentAllowance = allowance(tokenOwner, spender);
        if (currentAllowance != type(uint256).max) {
            require(currentAllowance >= value, "ERC20: insufficient allowance");
            unchecked {
                _approve(tokenOwner, spender, currentAllowance - value);
            }
        }
    }

    /**
     * @notice Emergency Pause / Unpause for all token transfers (Admin Owner only).
     */
    function pause() external onlyOwner {
        _pause();
    }

    function unpause() external onlyOwner {
        _unpause();
    }
}
