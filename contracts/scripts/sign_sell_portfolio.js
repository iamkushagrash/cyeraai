import { ethers } from 'ethers';

// Arguments: [userAddress, caiAmountWei, minUsdtOutWei, nonce, expiry, verifyingContract, chainId, privateKey]
const args = process.argv.slice(2);

if (args.length < 8) {
  console.error(JSON.stringify({ error: 'Missing required arguments' }));
  process.exit(1);
}

const [userAddress, caiAmountWei, minUsdtOutWei, nonce, expiry, verifyingContract, chainId, privateKey] = args;

async function sign() {
  const wallet = new ethers.Wallet(privateKey);

  const domain = {
    name: 'CyeraMiningEngine',
    version: '1',
    chainId: parseInt(chainId),
    verifyingContract: verifyingContract
  };

  const types = {
    SellPortfolio: [
      { name: 'user', type: 'address' },
      { name: 'caiAmount', type: 'uint256' },
      { name: 'minUsdtOut', type: 'uint256' },
      { name: 'nonce', type: 'uint256' },
      { name: 'expiry', type: 'uint256' }
    ]
  };

  const value = {
    user: userAddress,
    caiAmount: BigInt(caiAmountWei),
    minUsdtOut: BigInt(minUsdtOutWei),
    nonce: BigInt(nonce),
    expiry: BigInt(expiry)
  };

  const signature = await wallet.signTypedData(domain, types, value);

  console.log(JSON.stringify({
    success: true,
    signerAddress: wallet.address,
    signature: signature,
    user: userAddress,
    caiAmount: caiAmountWei,
    minUsdtOut: minUsdtOutWei,
    nonce: nonce,
    expiry: expiry
  }));
}

sign().catch((err) => {
  console.error(JSON.stringify({ error: err.message }));
  process.exit(1);
});
