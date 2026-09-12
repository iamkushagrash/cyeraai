import { ethers } from 'ethers';

// Arguments: [recipient, amountWei, withdrawalId, expiry, verifyingContract, chainId, privateKey]
const args = process.argv.slice(2);

if (args.length < 7) {
  console.error(JSON.stringify({ error: 'Missing required arguments' }));
  process.exit(1);
}

const [recipient, amountWei, withdrawalId, expiry, verifyingContract, chainId, privateKey] = args;

async function sign() {
  const checksumRecipient = ethers.getAddress(recipient.toLowerCase());
  const checksumContract = ethers.getAddress(verifyingContract.toLowerCase());
  const wallet = new ethers.Wallet(privateKey);

  const domain = {
    name: 'USDTWithdrawalVault',
    version: '1',
    chainId: parseInt(chainId),
    verifyingContract: checksumContract
  };

  const types = {
    Withdrawal: [
      { name: 'recipient', type: 'address' },
      { name: 'amount', type: 'uint256' },
      { name: 'withdrawalId', type: 'uint256' },
      { name: 'expiry', type: 'uint256' }
    ]
  };

  const value = {
    recipient: checksumRecipient,
    amount: BigInt(amountWei),
    withdrawalId: BigInt(withdrawalId),
    expiry: BigInt(expiry)
  };

  const signature = await wallet.signTypedData(domain, types, value);

  console.log(JSON.stringify({
    success: true,
    signerAddress: wallet.address,
    signature: signature,
    recipient: recipient,
    amount: amountWei,
    withdrawalId: withdrawalId,
    expiry: expiry
  }));
}

sign().catch((err) => {
  console.error(JSON.stringify({ error: err.message }));
  process.exit(1);
});
