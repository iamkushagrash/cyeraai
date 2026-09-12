import { ethers } from 'ethers';

const key1 = '38765232db1a84b6571252884f7168205f3207242cd31f99ab4e76ad76f5a67b';
const w1 = new ethers.Wallet(key1);
console.log('Wallet from current .env SIGNER_PRIVATE_KEY:', w1.address);

const key2 = '7672820670408540bfcd0c7d34794935e4a3054455fa5c7f9cccdfdf4aca45c3';
const w2 = new ethers.Wallet(key2);
console.log('Wallet from old SIGNER_PRIVATE_KEY:', w2.address);
