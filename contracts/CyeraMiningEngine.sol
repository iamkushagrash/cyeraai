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

interface IRevenueSplitter {
    function distribute() external returns (uint256, uint256, uint256);
}

/**
 * @title CyeraMiningEngine (Protocol V2)
 * @notice Central Investment & ROI Liquidity Execution Engine for Cyera AI.
 * 
 * EXECUTION FLOW
 * --------------
 * 1. User Investment (invest):
 *    - 30% USDT -> Automatically swapped on PancakeSwap for CAI (Green Buy on DEX chart 🟢).
 *                  Bought CAI tokens are stored in this engine contract to fund future ROI claims.
 *    - 38% USDT -> Transferred directly to USDTWithdrawalVault (Guarantees working/level payout liquidity).
 *    - 32% USDT -> Transferred to RevenueSplitter (splits: 20% Trading + 10% [5 Partners @ 2%] + 2% Reserve).
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

    address public withdrawalVault;
    address public revenueSplitter;
    address public backendSigner;

    // Split percentages (Total = 100%)
    uint256 public constant BUY_PERCENT     = 30; // 30% PancakeSwap Auto-Buy
    uint256 public constant VAULT_PERCENT   = 38; // 38% USDT Withdrawal Vault
    uint256 public constant SPLIT_PERCENT   = 32; // 32% Revenue Splitter

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
    uint256 public totalVaultFundedUsdt;
    uint256 public totalSplitterFundedUsdt;

    // Events
    event Invested(
        address indexed user,
        uint256 totalAmountUsdt,
        uint256 indexed packageId,
        uint256 vaultUsdt,
        uint256 splitterUsdt,
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

    event WithdrawalVaultUpdated(address indexed previousVault, address indexed newVault);
    event RevenueSplitterUpdated(address indexed previousSplitter, address indexed newSplitter);
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
        address _withdrawalVault,
        address _revenueSplitter,
        address _backendSigner,
        address _initialOwner
    ) Ownable(_initialOwner) {
        if (
            _usdt == address(0) ||
            _caiToken == address(0) ||
            _pancakeRouter == address(0) ||
            _withdrawalVault == address(0) ||
            _revenueSplitter == address(0) ||
            _backendSigner == address(0) ||
            _initialOwner == address(0)
        ) {
            revert ZeroAddress();
        }

        usdt = IERC20(_usdt);
        caiToken = IERC20(_caiToken);
        pancakeRouter = IPancakeRouterV2(_pancakeRouter);
        withdrawalVault = _withdrawalVault;
        revenueSplitter = _revenueSplitter;
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
     * @notice Process user package investment:
     *         30% Auto-Buy CAI on PancakeSwap (Held in Engine)
     *         38% USDT to Withdrawal Vault
     *         32% USDT to Revenue Splitter (Trading + Partners + Reserve)
     * @param amountUsdt Total investment amount in USDT (18 decimals).
     * @param packageId Internal package / staking tier identifier.
     */
    function invest(uint256 amountUsdt, uint256 packageId) external nonReentrant {
        if (amountUsdt == 0) revert InvalidAmount();

        // 1. Pull USDT from user to this contract
        bool success = usdt.transferFrom(msg.sender, address(this), amountUsdt);
        if (!success) revert TransferFailed();

        // 2. Exact 30% / 38% / 32% zero-loss calculations
        uint256 buyAmount = (amountUsdt * BUY_PERCENT) / 100;
        uint256 vaultAmount = (amountUsdt * VAULT_PERCENT) / 100;
        uint256 splitterAmount = amountUsdt - buyAmount - vaultAmount; // Exact 32% remainder

        // 3. Fund USDT Withdrawal Vault (38%)
        success = usdt.transfer(withdrawalVault, vaultAmount);
        if (!success) revert TransferFailed();
        totalVaultFundedUsdt += vaultAmount;

        // 4. Fund Revenue Splitter (32%) and trigger instant distribution
        success = usdt.transfer(revenueSplitter, splitterAmount);
        if (!success) revert TransferFailed();
        totalSplitterFundedUsdt += splitterAmount;

        // Attempt instant on-chain distribution in the same transaction
        try IRevenueSplitter(revenueSplitter).distribute() {} catch {}

        // 5. Auto-Buy CAI on PancakeSwap with remaining 30% USDT (Green Buy on DEX chart)
        address[] memory path = new address[](2);
        path[0] = address(usdt);
        path[1] = address(caiToken);

        uint256 caiBefore = caiToken.balanceOf(address(this));

        pancakeRouter.swapExactTokensForTokensSupportingFeeOnTransferTokens(
            buyAmount,
            0, // Accept market execution price
            path,
            address(this), // Engine holds purchased CAI to back future ROI claims
            block.timestamp + 300
        );

        uint256 caiPurchased = caiToken.balanceOf(address(this)) - caiBefore;

        totalInvestedUsdt += amountUsdt;
        totalCaiPurchased += caiPurchased;

        emit Invested(
            msg.sender,
            amountUsdt,
            packageId,
            vaultAmount,
            splitterAmount,
            buyAmount,
            caiPurchased,
            block.timestamp
        );
    }

    /**
     * @notice Claim ROI / Working Income by selling CAI on PancakeSwap for USDT (Delivered to User).
     * @param caiAmount Amount of CAI to swap and sell on PancakeSwap.
     * @param minUsdtOut Minimum acceptable USDT to prevent front-running (pass 0 for market output).
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
     * @notice Admin update for USDT Withdrawal Vault.
     */
    function setWithdrawalVault(address _newVault) external onlyOwner {
        if (_newVault == address(0)) revert ZeroAddress();
        address old = withdrawalVault;
        withdrawalVault = _newVault;
        emit WithdrawalVaultUpdated(old, _newVault);
    }

    /**
     * @notice Admin update for Revenue Splitter.
     */
    function setRevenueSplitter(address _newSplitter) external onlyOwner {
        if (_newSplitter == address(0)) revert ZeroAddress();
        address old = revenueSplitter;
        revenueSplitter = _newSplitter;
        emit RevenueSplitterUpdated(old, _newSplitter);
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
