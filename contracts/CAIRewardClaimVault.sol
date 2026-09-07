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
 * @dev Standard OpenZeppelin-compatible ECDSA signature recovery library.
 */
library ECDSA {
    enum RecoverError {
        NoError,
        InvalidSignature,
        InvalidSignatureLength,
        InvalidSignatureS
    }

    function tryRecover(bytes32 hash, bytes memory signature) internal pure returns (address, RecoverError) {
        if (signature.length == 65) {
            bytes32 r;
            bytes32 s;
            uint8 v;
            assembly {
                r := mload(add(signature, 0x20))
                s := mload(add(signature, 0x40))
                v := byte(0, mload(add(signature, 0x60)))
            }
            return tryRecover(hash, v, r, s);
        } else {
            return (address(0), RecoverError.InvalidSignatureLength);
        }
    }

    function recover(bytes32 hash, bytes memory signature) internal pure returns (address) {
        (address recovered, RecoverError error) = tryRecover(hash, signature);
        require(error == RecoverError.NoError, "ECDSA: invalid signature");
        return recovered;
    }

    function tryRecover(bytes32 hash, uint8 v, bytes32 r, bytes32 s) internal pure returns (address, RecoverError) {
        if (uint256(s) > 0x7FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF5D57617F83A266E1841014A56330B173) {
            return (address(0), RecoverError.InvalidSignatureS);
        }

        address signer = ecrecover(hash, v, r, s);
        if (signer == address(0)) {
            return (address(0), RecoverError.InvalidSignature);
        }

        return (signer, RecoverError.NoError);
    }
}

/**
 * @title CAIRewardClaimVault
 * @notice Distributes CAI rewards for ROI claims via cryptographic EIP-712 / OpenZeppelin ECDSA signatures.
 * @dev Employs OpenZeppelin SafeERC20. backendSigner is kept completely separate from Multisig Owner.
 */
contract CAIRewardClaimVault is Context, Ownable2Step, ReentrancyGuard, Pausable {
    using SafeERC20 for IERC20;
    using ECDSA for bytes32;

    IERC20 public immutable caiToken;
    address public backendSigner; // Dedicated hot signing key

    // EIP-712 Domain Separator Typehashes
    bytes32 private constant EIP712_DOMAIN_TYPEHASH = keccak256(
        "EIP712Domain(string name,string version,uint256 chainId,address verifyingContract)"
    );
    bytes32 private constant CLAIM_TYPEHASH = keccak256(
        "ClaimReward(address user,uint256 caiAmount,uint256 nonce,uint256 expiry)"
    );

    bytes32 public immutable DOMAIN_SEPARATOR;

    mapping(uint256 => bool) public usedNonces;

    // Events
    event RewardClaimed(
        address indexed user,
        uint256 caiAmount,
        uint256 indexed nonce,
        uint256 timestamp
    );
    event BackendSignerUpdated(address indexed oldSigner, address indexed newSigner);

    constructor(
        address _caiToken,
        address _backendSigner,
        address _initialOwner
    ) Ownable2Step(_initialOwner) {
        require(_caiToken != address(0), "ClaimVault: CAI token cannot be zero address");
        require(_backendSigner != address(0), "ClaimVault: Signer cannot be zero address");

        caiToken = IERC20(_caiToken);
        backendSigner = _backendSigner;

        DOMAIN_SEPARATOR = keccak256(
            abi.encode(
                EIP712_DOMAIN_TYPEHASH,
                keccak256(bytes("CAIRewardClaimVault")),
                keccak256(bytes("1")),
                block.chainid,
                address(this)
            )
        );
    }

    /**
     * @notice Claim CAI token reward with cryptographic EIP-712 backend signature.
     * @param caiAmount Number of CAI tokens to claim (with 18 decimals).
     * @param nonce Unique claim identifier / nonce generated by backend to prevent replays.
     * @param expiry Unix timestamp after which signature is void.
     * @param signature Cryptographic signature by backendSigner.
     */
    function claimReward(
        uint256 caiAmount,
        uint256 nonce,
        uint256 expiry,
        bytes calldata signature
    ) external nonReentrant whenNotPaused {
        require(caiAmount > 0, "ClaimVault: Amount must be greater than zero");
        require(block.timestamp <= expiry, "ClaimVault: Claim signature has expired");
        require(!usedNonces[nonce], "ClaimVault: Nonce has already been claimed");

        // EIP-712 Typed Struct Hash
        bytes32 structHash = keccak256(
            abi.encode(
                CLAIM_TYPEHASH,
                _msgSender(),
                caiAmount,
                nonce,
                expiry
            )
        );

        bytes32 digest = keccak256(
            abi.encodePacked("\x19\x01", DOMAIN_SEPARATOR, structHash)
        );

        address recovered = digest.recover(signature);
        require(recovered == backendSigner, "ClaimVault: Invalid cryptographic signature");

        usedNonces[nonce] = true;

        require(
            caiToken.balanceOf(address(this)) >= caiAmount,
            "ClaimVault: Insufficient CAI reward liquidity in vault"
        );

        caiToken.safeTransfer(_msgSender(), caiAmount);

        emit RewardClaimed(_msgSender(), caiAmount, nonce, block.timestamp);
    }

    // --- Admin Functions (Controlled by Admin Owner) ---

    function setBackendSigner(address _newSigner) external onlyOwner {
        require(_newSigner != address(0), "ClaimVault: Signer cannot be zero address");
        emit BackendSignerUpdated(backendSigner, _newSigner);
        backendSigner = _newSigner;
    }

    /**
     * @notice Emergency recovery for accidentally over-funded CAI tokens (controlled by Admin Owner).
     */
    function emergencyWithdrawCAI(address to, uint256 amount) external onlyOwner nonReentrant {
        require(to != address(0), "ClaimVault: Destination cannot be zero address");
        caiToken.safeTransfer(to, amount);
    }

    function pause() external onlyOwner {
        _pause();
    }

    function unpause() external onlyOwner {
        _unpause();
    }
}
