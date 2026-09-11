// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import {Ownable} from "@openzeppelin/contracts/access/Ownable.sol";

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
