<?php

namespace App\Helpers;

use Elliptic\EC;
use kornrunner\Keccak;

class Eip712Helper
{
    /**
     * Sign USDT Working Withdrawal for USDTWithdrawalVault contract
     *
     * @param string $recipient
     * @param string $amountWei
     * @param string $withdrawalId
     * @param string $expiry
     * @param string $verifyingContract
     * @param string|int $chainId
     * @param string $privateKey
     * @return array
     */
    public static function signWithdrawal($recipient, $amountWei, $withdrawalId, $expiry, $verifyingContract, $chainId, $privateKey)
    {
        // 1. Domain Separator
        $typeHashDomain = Keccak::hash("EIP712Domain(string name,string version,uint256 chainId,address verifyingContract)", 256);
        $nameHash = Keccak::hash("USDTWithdrawalVault", 256);
        $versionHash = Keccak::hash("1", 256);
        $chainIdHex = self::padUint($chainId);
        $contractHex = self::padAddress($verifyingContract);

        $domainData = hex2bin($typeHashDomain . $nameHash . $versionHash . $chainIdHex . $contractHex);
        $domainSeparator = Keccak::hash($domainData, 256);

        // 2. Struct Hash
        $typeHashWithdrawal = Keccak::hash("Withdrawal(address recipient,uint256 amount,uint256 withdrawalId,uint256 expiry)", 256);
        $recipientHex = self::padAddress($recipient);
        $amountHex = self::padUint($amountWei);
        $withdrawalIdHex = self::padUint($withdrawalId);
        $expiryHex = self::padUint($expiry);

        $structData = hex2bin($typeHashWithdrawal . $recipientHex . $amountHex . $withdrawalIdHex . $expiryHex);
        $structHash = Keccak::hash($structData, 256);

        // 3. Digest: keccak256("\x19\x01" + domainSeparator + structHash)
        $digestData = "\x19\x01" . hex2bin($domainSeparator) . hex2bin($structHash);
        $digest = Keccak::hash($digestData, 256);

        // 4. ECDSA Secp256k1 Sign
        $ec = new EC('secp256k1');
        $privKeyHex = ltrim($privateKey, '0x');
        $key = $ec->keyFromPrivate($privKeyHex, 'hex');
        $sig = $key->sign($digest, ['canonical' => true]);

        $r = str_pad($sig->r->toString('hex'), 64, '0', STR_PAD_LEFT);
        $s = str_pad($sig->s->toString('hex'), 64, '0', STR_PAD_LEFT);
        $v = dechex($sig->recoveryParam + 27);
        $signature = '0x' . $r . $s . str_pad($v, 2, '0', STR_PAD_LEFT);

        return [
            'signature' => $signature,
            'signer'    => '0x' . $key->getPublic(false, 'hex'),
            'digest'    => '0x' . $digest,
        ];
    }

    /**
     * Sign Sell Portfolio Claim for CyeraMiningEngine contract
     *
     * @param string $user
     * @param string $caiAmountWei
     * @param string $minUsdtOutWei
     * @param string $nonce
     * @param string $expiry
     * @param string $verifyingContract
     * @param string|int $chainId
     * @param string $privateKey
     * @return array
     */
    public static function signSellPortfolio($user, $caiAmountWei, $minUsdtOutWei, $nonce, $expiry, $verifyingContract, $chainId, $privateKey)
    {
        // 1. Domain Separator
        $typeHashDomain = Keccak::hash("EIP712Domain(string name,string version,uint256 chainId,address verifyingContract)", 256);
        $nameHash = Keccak::hash("CyeraMiningEngine", 256);
        $versionHash = Keccak::hash("1", 256);
        $chainIdHex = self::padUint($chainId);
        $contractHex = self::padAddress($verifyingContract);

        $domainData = hex2bin($typeHashDomain . $nameHash . $versionHash . $chainIdHex . $contractHex);
        $domainSeparator = Keccak::hash($domainData, 256);

        // 2. Struct Hash
        $typeHashClaim = Keccak::hash("SellPortfolio(address user,uint256 caiAmount,uint256 minUsdtOut,uint256 nonce,uint256 expiry)", 256);
        $userHex = self::padAddress($user);
        $caiHex = self::padUint($caiAmountWei);
        $minUsdtHex = self::padUint($minUsdtOutWei);
        $nonceHex = self::padUint($nonce);
        $expiryHex = self::padUint($expiry);

        $structData = hex2bin($typeHashClaim . $userHex . $caiHex . $minUsdtHex . $nonceHex . $expiryHex);
        $structHash = Keccak::hash($structData, 256);

        // 3. Digest
        $digestData = "\x19\x01" . hex2bin($domainSeparator) . hex2bin($structHash);
        $digest = Keccak::hash($digestData, 256);

        // 4. ECDSA Secp256k1 Sign
        $ec = new EC('secp256k1');
        $privKeyHex = ltrim($privateKey, '0x');
        $key = $ec->keyFromPrivate($privKeyHex, 'hex');
        $sig = $key->sign($digest, ['canonical' => true]);

        $r = str_pad($sig->r->toString('hex'), 64, '0', STR_PAD_LEFT);
        $s = str_pad($sig->s->toString('hex'), 64, '0', STR_PAD_LEFT);
        $v = dechex($sig->recoveryParam + 27);
        $signature = '0x' . $r . $s . str_pad($v, 2, '0', STR_PAD_LEFT);

        return [
            'signature' => $signature,
            'digest'    => '0x' . $digest,
        ];
    }

    /**
     * Pad Ethereum address to 32 bytes (64 hex characters)
     */
    private static function padAddress($addr)
    {
        $clean = strtolower(ltrim(trim($addr), '0x'));
        return str_pad($clean, 64, '0', STR_PAD_LEFT);
    }

    /**
     * Pad unsigned integer / big number to 32 bytes (64 hex characters)
     */
    private static function padUint($val)
    {
        if (is_numeric($val) && function_exists('gmp_init')) {
            $hex = gmp_strval(gmp_init((string)$val, 10), 16);
        } elseif (is_numeric($val) && function_exists('bcadd')) {
            $hex = self::decToHexBc((string)$val);
        } else {
            $hex = dechex((int)$val);
        }
        return str_pad(strtolower($hex), 64, '0', STR_PAD_LEFT);
    }

    /**
     * Convert decimal string to hex using BCMath
     */
    private static function decToHexBc($dec)
    {
        $hex = '';
        while (bccomp($dec, '0') > 0) {
            $rem = bcmod($dec, '16');
            $dec = bcdiv($dec, '16', 0);
            $hex = dechex($rem) . $hex;
        }
        return $hex ?: '0';
    }
}
