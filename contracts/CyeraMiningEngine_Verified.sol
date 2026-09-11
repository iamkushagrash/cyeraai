// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

// Sources flattened with hardhat v2.29.1 https://hardhat.org


// File @openzeppelin/contracts/utils/Context.sol@v5.6.1

// Original license: SPDX_License_Identifier: MIT
// OpenZeppelin Contracts (last updated v5.0.1) (utils/Context.sol)


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


// File contracts/CyeraMiningEngine.sol

// Original license: SPDX_License_Identifier: MIT

interface IERC20 {
    function totalSupply() external view returns (uint256);
    function balanceOf(address account) external view returns (uint256);
    function transfer(address to, uint256 value) external returns (bool);
    function allowance(address owner, address spender) external view returns (uint256);
    function approve(address spender, uint256 value) external returns (bool);
    function transferFrom(address from, address to, uint256 value) external returns (bool);
}

interface IPancakeRouterV2 {
    function swapExactTokensForTokensSupportingFeeOnTransferTokens(
        uint256 amountIn,
        uint256 amountOutMin,
        address[] calldata path,
        address to,
        uint256 deadline
    ) external;

    function getAmountsOut(uint256 amountIn, address[] calldata path)
        external
        view
        returns (uint256[] memory amounts);
}

/**
 * @title CyeraMiningEngine
 * @notice Central Investment & ROI Liquidity Execution Engine for Cyera AI.
 * 
 * EXECUTION FLOW
 * --------------
 * 1. User Investment (invest):
 *    - 70% USDT -> Directly sent to Owner Treasury Wallet (Instant USDT reserve).
 *    - 30% USDT -> Automatically swapped on PancakeSwap for CAI (Green Buy on DEX chart 🟢).
 *    - Bought CAI tokens are safely held in this engine contract.
 *
 * 2. User ROI Claim (sellPortfolio):
 *    - User claims daily mining/staking ROI via EIP-712 authorized signature.
 *    - Engine swaps CAI for USDT on PancakeSwap (Red Sell on DEX chart 🔴).
 *    - Resulting USDT is delivered directly to the user's wallet.
 */
contract CyeraMiningEngine is Ownable {
    IERC20 public immutable usdt;
    IERC20 public immutable caiToken;
    IPancakeRouterV2 public immutable pancakeRouter;

    address public treasuryWallet;
    address public backendSigner;

    // EIP-712 Domain Separator constants
    bytes32 private constant CLAIM_TYPEHASH = keccak256(
        "SellPortfolio(address user,uint256 caiAmount,uint256 minUsdtOut,uint256 nonce,uint256 expiry)"
    );
    bytes32 public immutable DOMAIN_SEPARATOR;

    // Prevent nonce reuse for ROI claims
    mapping(address => mapping(uint256 => bool)) public isNonceExecuted;

    // Reentrancy guard
    uint256 private _status;
    uint256 private constant _NOT_ENTERED = 1;
    uint256 private constant _ENTERED = 2;

    // Track total volume
    uint256 public totalInvestedUsdt;
    uint256 public totalCaiPurchased;
    uint256 public totalRoiUsdtPaid;

    // Events
    event Invested(
        address indexed user,
        uint256 totalAmountUsdt,
        uint256 indexed packageId,
        uint256 treasuryUsdt,
        uint256 liquidityUsdt,
        uint256 caiPurchased,
        uint256 timestamp
    );

    event PortfolioSold(
        address indexed user,
        uint256 caiAmountSold,
        uint256 usdtDelivered,
        uint256 indexed nonce,
        uint256 timestamp
    );

    event TreasuryWalletUpdated(address indexed previousWallet, address indexed newWallet);
    event BackendSignerUpdated(address indexed previousSigner, address indexed newSigner);

    error ZeroAddress();
    error InvalidAmount();
    error InvalidSignature();
    error SignatureExpired();
    error NonceAlreadyUsed();
    error TransferFailed();
    error ReentrancyGuardReentrantCall();

    modifier nonReentrant() {
        if (_status == _ENTERED) revert ReentrancyGuardReentrantCall();
        _status = _ENTERED;
        _;
        _status = _NOT_ENTERED;
    }

    constructor(
        address _usdt,
        address _caiToken,
        address _pancakeRouter,
        address _treasuryWallet,
        address _backendSigner,
        address _initialOwner
    ) Ownable(_initialOwner) {
        if (
            _usdt == address(0) ||
            _caiToken == address(0) ||
            _pancakeRouter == address(0) ||
            _treasuryWallet == address(0) ||
            _backendSigner == address(0) ||
            _initialOwner == address(0)
        ) {
            revert ZeroAddress();
        }

        usdt = IERC20(_usdt);
        caiToken = IERC20(_caiToken);
        pancakeRouter = IPancakeRouterV2(_pancakeRouter);
        treasuryWallet = _treasuryWallet;
        backendSigner = _backendSigner;
        _status = _NOT_ENTERED;

        // Compute EIP-712 Domain Separator
        DOMAIN_SEPARATOR = keccak256(
            abi.encode(
                keccak256("EIP712Domain(string name,string version,uint256 chainId,address verifyingContract)"),
                keccak256(bytes("CyeraMiningEngine")),
                keccak256(bytes("1")),
                block.chainid,
                address(this)
            )
        );

        // Pre-approve PancakeSwap router for USDT and CAI transfers
        IERC20(_usdt).approve(_pancakeRouter, type(uint256).max);
        IERC20(_caiToken).approve(_pancakeRouter, type(uint256).max);
    }

    /**
     * @notice Process user package investment (70% Treasury + 30% PancakeSwap Auto-Buy).
     * @param amountUsdt Total investment amount in USDT (18 decimals).
     * @param packageId Internal package / staking tier identifier.
     */
    function invest(uint256 amountUsdt, uint256 packageId) external nonReentrant {
        if (amountUsdt == 0) revert InvalidAmount();

        // 1. Pull USDT from user to this contract
        bool success = usdt.transferFrom(msg.sender, address(this), amountUsdt);
        if (!success) revert TransferFailed();

        // 2. Calculate 70% and 30% split
        uint256 treasuryAmount = (amountUsdt * 70) / 100;
        uint256 liquidityAmount = amountUsdt - treasuryAmount;

        // 3. Send 70% USDT directly to Owner Treasury Wallet
        success = usdt.transfer(treasuryWallet, treasuryAmount);
        if (!success) revert TransferFailed();

        // 4. Auto-Buy CAI on PancakeSwap with remaining 30% USDT (Green Buy on DEX chart)
        address[] memory path = new address[](2);
        path[0] = address(usdt);
        path[1] = address(caiToken);

        uint256 caiBefore = caiToken.balanceOf(address(this));

        pancakeRouter.swapExactTokensForTokensSupportingFeeOnTransferTokens(
            liquidityAmount,
            0, // Accept market execution price
            path,
            address(this), // Engine holds purchased CAI
            block.timestamp + 300
        );

        uint256 caiPurchased = caiToken.balanceOf(address(this)) - caiBefore;

        totalInvestedUsdt += amountUsdt;
        totalCaiPurchased += caiPurchased;

        emit Invested(
            msg.sender,
            amountUsdt,
            packageId,
            treasuryAmount,
            liquidityAmount,
            caiPurchased,
            block.timestamp
        );
    }

    /**
     * @notice Claim ROI / Working Income by selling CAI on PancakeSwap for USDT (Delivered to User).
     * @param caiAmount Amount of CAI to swap and sell on PancakeSwap.
     * @param minUsdtOut Minimum acceptable USDT to prevent front-running.
     * @param nonce Unique transaction nonce generated by backend.
     * @param expiry Expiration timestamp for the cryptographic signature.
     * @param signature Cryptographic signature from authorized backendSigner.
     */
    function sellPortfolio(
        uint256 caiAmount,
        uint256 minUsdtOut,
        uint256 nonce,
        uint256 expiry,
        bytes calldata signature
    ) external nonReentrant {
        if (caiAmount == 0) revert InvalidAmount();
        if (block.timestamp > expiry) revert SignatureExpired();
        if (isNonceExecuted[msg.sender][nonce]) revert NonceAlreadyUsed();

        // 1. Verify EIP-712 Cryptographic Signature via ecrecover
        bytes32 structHash = keccak256(
            abi.encode(
                CLAIM_TYPEHASH,
                msg.sender,
                caiAmount,
                minUsdtOut,
                nonce,
                expiry
            )
        );

        bytes32 digest = keccak256(
            abi.encodePacked("\x19\x01", DOMAIN_SEPARATOR, structHash)
        );

        address recoveredSigner = _recoverSigner(digest, signature);
        if (recoveredSigner == address(0) || recoveredSigner != backendSigner) {
            revert InvalidSignature();
        }

        // 2. Mark nonce as consumed
        isNonceExecuted[msg.sender][nonce] = true;

        // 3. Swap CAI for USDT on PancakeSwap (Red Sell on DEX chart) & deliver USDT to user
        address[] memory path = new address[](2);
        path[0] = address(caiToken);
        path[1] = address(usdt);

        uint256 userUsdtBefore = usdt.balanceOf(msg.sender);

        pancakeRouter.swapExactTokensForTokensSupportingFeeOnTransferTokens(
            caiAmount,
            minUsdtOut,
            path,
            msg.sender, // Deliver USDT directly to user
            block.timestamp + 300
        );

        uint256 usdtDelivered = usdt.balanceOf(msg.sender) - userUsdtBefore;
        totalRoiUsdtPaid += usdtDelivered;

        emit PortfolioSold(
            msg.sender,
            caiAmount,
            usdtDelivered,
            nonce,
            block.timestamp
        );
    }

    /**
     * @dev Internal helper to recover signer from signature using standard ecrecover.
     */
    function _recoverSigner(bytes32 hash, bytes memory sig) internal pure returns (address) {
        if (sig.length != 65) return address(0);

        bytes32 r;
        bytes32 s;
        uint8 v;

        assembly {
            r := mload(add(sig, 32))
            s := mload(add(sig, 64))
            v := byte(0, mload(add(sig, 96)))
        }

        if (v < 27) {
            v += 27;
        }

        if (v != 27 && v != 28) return address(0);

        return ecrecover(hash, v, r, s);
    }

    /**
     * @notice Admin update for Treasury Wallet.
     */
    function setTreasuryWallet(address _newTreasury) external onlyOwner {
        if (_newTreasury == address(0)) revert ZeroAddress();
        address old = treasuryWallet;
        treasuryWallet = _newTreasury;
        emit TreasuryWalletUpdated(old, _newTreasury);
    }

    /**
     * @notice Admin update for Backend Signer.
     */
    function setBackendSigner(address _newSigner) external onlyOwner {
        if (_newSigner == address(0)) revert ZeroAddress();
        address old = backendSigner;
        backendSigner = _newSigner;
        emit BackendSignerUpdated(old, _newSigner);
    }

    /**
     * @notice Emergency recovery for non-protocol tokens accidentally sent to contract.
     */
    function rescueTokens(address token, address to, uint256 amount) external onlyOwner {
        if (to == address(0)) revert ZeroAddress();
        IERC20(token).transfer(to, amount);
    }
}
