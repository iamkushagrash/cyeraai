// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import {ERC20} from "@openzeppelin/contracts/token/ERC20/ERC20.sol";
import {ERC20Burnable} from "@openzeppelin/contracts/token/ERC20/extensions/ERC20Burnable.sol";
import {Ownable} from "@openzeppelin/contracts/access/Ownable.sol";

interface IERC20Rescue {
    function balanceOf(address account) external view returns (uint256);
    function transfer(address to, uint256 amount) external returns (bool);
}

interface IPancakeFactoryV2 {
    function getPair(address tokenA, address tokenB)
        external
        view
        returns (address pair);

    function createPair(address tokenA, address tokenB)
        external
        returns (address pair);
}

interface IPancakeRouterV2 {
    function factory() external view returns (address);
}

/**
 * @title CAIToken (Cyera)
 * @notice Fixed-supply CAI token with restricted PancakeSwap buying.
 *
 * TOKEN RULES AFTER FINAL CONFIGURATION
 * -------------------------------------
 * User            -> User              Allowed
 * User            -> Pancake Pair      Allowed (sell / liquidity add)
 * Pancake Pair    -> User              Blocked (buy)
 * Pancake Pair    -> Mining Contract   Allowed (30% investment auto-buy)
 * Mining Contract -> Pancake Pair      Allowed (ROI auto-sell payout)
 * Mining Contract -> User              Allowed
 * User            -> Mining Contract   Allowed
 * Burn                                 Allowed
 *
 * CONFIGURATION
 * -------------
 * 1. The complete fixed supply (300,000 CAI) is minted to the deployer.
 * 2. The PancakeSwap V2 pair is fetched or created in the constructor.
 * 3. The Mining Contract is configured exactly once.
 * 4. Ownership is automatically renounced after configuration.
 */
contract CAIToken is ERC20, ERC20Burnable, Ownable {
    uint8 private constant TOKEN_DECIMALS = 18;

    uint256 public constant MAX_SUPPLY = 300_000 * 10 ** TOKEN_DECIMALS;

    address public immutable usdt;
    IPancakeRouterV2 public immutable pancakeRouter;
    address public immutable pancakePair;

    address public miningContract;
    bool public configurationLocked;

    error ZeroAddress();
    error InvalidContract(address account);
    error InvalidFactory(address factory);
    error InvalidPair(address pair);
    error InvalidMiningContract(address mining);
    error ConfigurationAlreadyLocked();
    error TransferNotAllowed(address from, address to);
    error PancakeBuyBlocked(address recipient);
    error MiningContractNotConfigured();
    error NoUSDTToRecover();
    error USDTTransferFailed();

    event MiningContractConfigured(
        address indexed miningContract,
        address indexed configuredBy
    );

    event FinalConfigurationLocked(
        address indexed miningContract,
        address indexed pancakePair
    );

    event USDTRecoveredToMining(
        address indexed caller,
        address indexed miningContract,
        uint256 amount
    );

    /**
     * @param usdtAddress USDT token address on BSC.
     * @param routerAddress PancakeSwap V2 Router address on BSC.
     */
    constructor(
        address usdtAddress,
        address routerAddress
    )
        ERC20("Cyera", "CAI")
        Ownable(msg.sender)
    {
        if (
            usdtAddress == address(0) ||
            routerAddress == address(0)
        ) {
            revert ZeroAddress();
        }

        if (usdtAddress.code.length == 0) {
            revert InvalidContract(usdtAddress);
        }

        if (routerAddress.code.length == 0) {
            revert InvalidContract(routerAddress);
        }

        usdt = usdtAddress;
        pancakeRouter = IPancakeRouterV2(routerAddress);

        address factoryAddress = IPancakeRouterV2(routerAddress).factory();

        if (
            factoryAddress == address(0) ||
            factoryAddress.code.length == 0
        ) {
            revert InvalidFactory(factoryAddress);
        }

        IPancakeFactoryV2 factory = IPancakeFactoryV2(factoryAddress);

        address pair = factory.getPair(address(this), usdtAddress);

        if (pair == address(0)) {
            pair = factory.createPair(
                address(this),
                usdtAddress
            );
        }

        if (
            pair == address(0) ||
            pair.code.length == 0
        ) {
            revert InvalidPair(pair);
        }

        pancakePair = pair;

        // The complete supply is minted once to the deployer.
        // No external or public mint function exists.
        _mint(msg.sender, MAX_SUPPLY);
    }

    /**
     * @notice Returns 18 token decimals.
     */
    function decimals()
        public
        pure
        override
        returns (uint8)
    {
        return TOKEN_DECIMALS;
    }

    /**
     * @notice Permanently configures the Mining Contract.
     * @dev This function can execute only once. Ownership is
     *      automatically renounced after successful configuration.
     *
     * The Mining Contract must already be deployed.
     */
    function setMiningContractAndRenounceOwnership(
        address miningAddress
    )
        external
        onlyOwner
    {
        if (configurationLocked) {
            revert ConfigurationAlreadyLocked();
        }

        if (miningAddress == address(0)) {
            revert ZeroAddress();
        }

        if (
            miningAddress.code.length == 0 ||
            miningAddress == address(this) ||
            miningAddress == usdt ||
            miningAddress == address(pancakeRouter) ||
            miningAddress == pancakePair
        ) {
            revert InvalidMiningContract(miningAddress);
        }

        miningContract = miningAddress;
        configurationLocked = true;

        emit MiningContractConfigured(
            miningAddress,
            msg.sender
        );

        emit FinalConfigurationLocked(
            miningAddress,
            pancakePair
        );

        // No administrator remains after final configuration.
        renounceOwnership();
    }

    /**
     * @dev Controls every mint, burn and token transfer.
     *
     * Before final configuration:
     * - Owner -> any address is allowed.
     * - Any address -> owner is allowed.
     * - Transfers not involving the owner are blocked.
     *
     * After final configuration:
     * - Normal wallet transfers are allowed.
     * - Selling to the official Pancake pair is allowed.
     * - The official pair can send CAI only to the Mining Contract.
     */
    function _update(
        address from,
        address to,
        uint256 amount
    )
        internal
        override
    {
        // Constructor mint.
        if (from == address(0)) {
            super._update(from, to, amount);
            return;
        }

        // Burns through burn() or burnFrom() are allowed.
        if (to == address(0)) {
            super._update(from, to, amount);
            return;
        }

        // Before final locking, the current owner must participate
        // in every normal transfer. This permits initial token
        // distribution and liquidity preparation.
        if (!configurationLocked) {
            address currentOwner = owner();

            if (
                from != currentOwner &&
                to != currentOwner
            ) {
                revert TransferNotAllowed(from, to);
            }

            super._update(from, to, amount);
            return;
        }

        // Block purchases from the official PancakeSwap pair.
        // The pair may transfer CAI only to the Mining Contract.
        if (
            from == pancakePair &&
            to != miningContract
        ) {
            revert PancakeBuyBlocked(to);
        }

        // All other normal transfers are allowed.
        super._update(from, to, amount);
    }

    /**
     * @notice Emergency recovery of USDT to Mining Contract.
     */
    function recoverUSDT() external {
        address mining = miningContract;

        if (!configurationLocked || mining == address(0)) {
            revert MiningContractNotConfigured();
        }

        uint256 amount = IERC20Rescue(usdt).balanceOf(address(this));

        if (amount == 0) {
            revert NoUSDTToRecover();
        }

        bool success = IERC20Rescue(usdt).transfer(mining, amount);

        if (!success) {
            revert USDTTransferFailed();
        }

        emit USDTRecoveredToMining(
            msg.sender,
            mining,
            amount
        );
    }
}
