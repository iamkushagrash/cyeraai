# 🚀 CYERA (CAI) — COMPLETE HYBRID SYSTEM ARCHITECTURE & CODEBASE INTEGRATION SPECIFICATION

> **Document Version:** 2.1 (Direct Codebase & Kernel Alignment Edition)  
> **Target Framework:** Laravel 5.8 (Existing Codebase) + BNB Smart Chain (Solidity 0.8.20+)  
> **Token:** Cyera Token (**CAI**) | Total Supply: **300,000 CAI** (Fixed, Non-Mintable)

---

## 1. Executive Summary & Tokenomics

CYERA is a **Decentralized-Centralized Hybrid FinTech & MLM Ecosystem** built on top of the existing Laravel 5.8 application in `d:\Cyera AI\`, interfaced with secure Solidity smart contracts on **BNB Smart Chain (BSC)**.

| Parameter | Specification | Notes / Enforcement |
| :--- | :--- | :--- |
| **Token Name** | Cyera | Native utility & reward asset |
| **Token Symbol** | **CAI** | BEP-20 Token Standard |
| **Total Supply** | **300,000 CAI** | **100% Fixed**, Non-Mintable, minted at deployment to Treasury |
| **Blockchain** | BNB Smart Chain (BSC) | Fast finality, low gas fees |
| **DEX Trading Pair** | CAI / USDT on PancakeSwap | Liquidity locked in Multisig / Liquidity Vault |
| **PancakeSwap Buy Rule** | **Whitelisted Wallets Only** | Transfer hook validates `isWhitelisted[to] == true` on pair buys |
| **PancakeSwap Sell Rule** | **Open / Unrestricted** | Any CAI holder can sell to liquidity pair at any time |
| **Investment Limits** | **$50 to $2,000 USDT** | Stored in `stacking_deposites` & validated on-chain |
| **Fund Split on Deposit** | **70% Vault / 30% Liquidity** | Executed atomically by `CyeraInvestmentSplitter.sol` |

---

## 2. End-to-End Hybrid System Architecture Diagram

```
                                      ┌────────────────────────────────────────────────────────┐
                                      │             CYERA HYBRID PLATFORM ARCHITECTURE         │
                                      └───────────────────────────┬────────────────────────────┘
                                                                  │
                    ┌─────────────────────────────────────────────┴─────────────────────────────────────────────┐
                    ▼                                                                                           ▼
    ┌──────────────────────────────────────────────┐                                            ┌──────────────────────────────────────────────┐
    │       LARAVEL 5.8 EXISTING CODEBASE LAYER    │                                            │         ON-CHAIN BSC SMART CONTRACT SUITE    │
    ├──────────────────────────────────────────────┤                                            ├──────────────────────────────────────────────┤
    │ 1. User & Hierarchy Management (`users`,     │                                            │ 1. `CAIToken.sol` (BEP-20)                   │
    │    `user_details`, `AssetDetailController`)  │◄──────────── Web3 Transaction ─────────────│    • Fixed 300,000 Supply                    │
    │ 2. Investment Staking Engine                 │              Verification & Event          │    • PancakeSwap Whitelist Hook on Buy       │
    │    (`stacking_deposites`, `wallet_transfers`,│              Syncing                       │    • Open Sell for all CAI holders           │
    │    `WalletTransferController@stakeMWT`)      │                                            │ 2. `CyeraInvestmentSplitter.sol`             │
    │ 3. Daily ROI Accrual (0.5% + Booster) via    │                                            │    • $50 - $2,000 USDT Deposit Gate          │
    │    `Kernel.php` -> `CpsIncomeController`     │                                            │    • 70% to Treasury Claim Vault             │
    │ 4. Working Incomes (Direct, 15-Level, Pools) │                                            │    • 30% to Liquidity Treasury Wallet        │
    │    (`bonus_rewards`, `level_incomes`,        │                                            │ 3. `TreasuryClaimVault.sol`                  │
    │    `club_incomes`, `leadership_incomes`)     │                                            │    • 70% USDT Custody Vault                  │
    │ 5. Dynamic Capping (2X, 3X, 5X, 10X) inside  │                                            │ 4. `CAIRewardClaimVault.sol`                 │
    │    `StackingDetailController@businessUpdate` │───────────── Cryptographic ECDSA ─────────►│    • User ROI Claim verification             │
    │ 6. Cryptographic ECDSA Claim Generator       │              EIP-712 Signature             │    • Nonce + Expiry Replay Protection        │
    │    (`Web3/ClaimSignatureController.php`)     │                                            │ 5. `USDTWithdrawalVault.sol`                 │
    │ 7. Withdrawal Processor (`withdraw_infos`,   │◄──────────── Admin Approved Release ───────│    • Working Income USDT Payouts             │
    │    `transaction_details`, `WithdrawInfo`)    │                                            │                                              │
    └──────────────────────────────────────────────┘                                            └──────────────────────────────────────────────┘
```

---

## 3. Existing Codebase Audit & Direct Flow Mapping

The system directly utilizes existing tables, models, and controllers without unnecessary duplicate abstractions:

```
Existing Database & Controllers Mapping:
┌─────────────────────────┬───────────────────────────────┬──────────────────────────────────────────────────────────────────┐
│ Feature Domain          │ Existing Database Tables      │ Existing Core Controllers & Models                               │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ User Tree & Hierarchy   │ `users`, `user_details`,      │ `App\User`, `App\UserDetails`, `Auth\RegisterController`,        │
│                         │ `asset_details`,              │ `UserDetailsController`, `AssetDetailChangesController`          │
│                         │ `asset_detail_changes`        │                                                                  │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ Investment / Staking    │ `stacking_deposites`,         │ `App\StackingDeposite`, `App\StackingDetail`,                    │
│                         │ `stacking_details`,           │ `WalletTransferController@stakeMWT`,                             │
│                         │ `wallet_transfers`,           │ `StackingDepositeController`, `AccountDepositController`         │
│                         │ `account_deposits`            │                                                                  │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ Daily ROI Accrual (CPS) │ `cps_incomes`,                │ `App\CpsIncome`, `CpsIncomeController@cpsGeneration`,            │
│ (0.5% Base + Booster)   │ `profile_stores`              │ Triggered directly via `App\Console\Kernel.php`                  │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ Direct Referral Bonus   │ `bonus_rewards`               │ `App\BonusReward`, `BonusRewardController`,                      │
│                         │                               │ `WalletTransferController@stakeMWT`                              │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ 15-Level Unilevel &     │ `level_incomes`,              │ `App\LevelIncome`, `App\LevelDetails`,                           │
│ Royalty Commission      │ `level_details`               │ `LevelIncomeController@levelDistribution` via `Kernel.php`       │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ Rank, Club & Pools      │ `club_incomes`, `club_details`│ `App\ClubIncome`, `App\ClubDetails`, `ClubIncomeController`,     │
│                         │ `leadership_incomes`,         │ `BonanzaDetailsController`, `UserDetails@clubBusiness`,          │
│                         │ `leadership_infos`,           │ `UserDetails@lifetimeAchievementBusiness`                        │
│                         │ `achievement_incomes`         │                                                                  │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ Dynamic Capping & Tiers │ `stacking_deposites.capamount`│ `StackingDetailController@cappingCalculation`,                   │
│ (2X, 3X, 5X, 10X)       │ `user_details.userstate`      │ `StackingDetailController@cappingUpdate`,                        │
│                         │                               │ `StackingDetailController@businessUpdate`                        │
├─────────────────────────┼───────────────────────────────┼──────────────────────────────────────────────────────────────────┤
│ Withdrawals             │ `transaction_details`,        │ `WithdrawInfoController@withdrawRequest`,                        │
│                         │ `transaction_infos`,          │ `TransactionDetailController@AdminwithdrawUpdateone`             │
│                         │ `withdraw_infos`              │                                                                  │
└─────────────────────────┴───────────────────────────────┴──────────────────────────────────────────────────────────────────┘
```

---

## 4. Deep-Dive Component Specifications

### 4.1. Investment & Deposit Flow ($50 to $2,000 USDT)

1. **User Action (Web3 / DApp):**
   - User connects MetaMask / TrustWallet on frontend.
   - User triggers `deposit(amount)` on `CyeraInvestmentSplitter.sol`.
   - Contract enforces `$50 <= amount <= $2,000`.
   - Contract transfers **70% USDT** to `TreasuryClaimVault` and **30% USDT** to `LiquidityTreasuryWallet`.
   - Emits event: `Invested(address indexed user, uint256 amountUSDT, uint256 timestamp, uint256 depositId)`.

2. **Backend Processing (Laravel 5.8):**
   - User submits transaction hash to `InvestmentListenerController@verifyTxn`.
   - Backend calls existing `WalletTransferController@stakeMWT` logic:
     - Deducts/credits in `account_deposits` and `wallet_transfers`.
     - Inserts record into `stacking_deposites` with `amount`, `usdt`, `planid`, `staketype = 1`, and `capamount = Crypt::encrypt(amount * multiplier)`.
     - Updates `user_details`: increments `current_self_investment`, `total_self_investment`, `current_investment`, `total_investment`, sets `userstatus = 1` and `roi_status = 1`.
     - **Direct Referral Payout:** Distributes 5% direct referral commission into `bonus_rewards` (`description = 'referral'`).
     - **Booster Check:** Triggers `StackingDetailController@boosterCheckForUser`.
     - **Tree Business Update:** Runs `StackingDetailController@businessUpdate` to traverse upline in `user_details` and `leadership_infos`.

---

### 4.2. Daily ROI Accrual & Scheduled Execution via `Kernel.php`

Instead of separate Artisan Command classes, the existing application schedules controller methods directly in `app/Console/Kernel.php`:

```php
// Existing & Aligned Scheduler in app/Console/Kernel.php:
protected function schedule(Schedule $schedule)
{
    // Daily 0.5% Base ROI + Booster computation
    $schedule->call('App\Http\Controllers\CpsIncomeController@cpsGeneration')->daily()->at('10:00')->timezone('Asia/Kolkata');
    
    // 15-Level Unilevel & Royalty commission distribution
    $schedule->call('App\Http\Controllers\LevelIncomeController@levelDistribution')->daily()->at('10:30')->timezone('Asia/Kolkata');
    
    // Daily Club / Pool distribution
    $schedule->call('App\Http\Controllers\ClubIncomeController@clubDistribution')->daily()->at('11:15')->timezone('Asia/Kolkata');
    
    // Lifetime / Rank Milestone rewards
    $schedule->call('App\Http\Controllers\ClubIncomeController@achievementRewardDistribution')->daily()->at('23:00')->timezone('Asia/Kolkata');
    
    // Hourly Business Volume, Booster check & Dynamic Capping Tier Update
    $schedule->call('App\Http\Controllers\StackingDetailController@businessUpdate')->hourly()->name('topupBusinessUpdate')->withoutOverlapping()->timezone('Asia/Kolkata');
}
```

1. **Daily USD Accrual (`CpsIncomeController@cpsGeneration`):**
   - Computes base daily ROI: **0.5% Daily** on active `stacking_deposites`.
   - Multiplies by booster multiplier (`booster == 2` for booster users).
   - Validates against active cap via `StackingDetailController@cappingCalculation($userid, $dailyRoi)`.
   - Inserts earned ROI into `cps_incomes` with `amt_usdt`, `remaining_usdt`, `status = 0`.

2. **CAI Reward Claim Flow (Cryptographic ECDSA Signature):**
   - User requests to claim accumulated USD ROI on the Web3 interface.
   - Backend controller `ClaimSignatureController` verifies available `remaining_usdt` in `cps_incomes`.
   - Converts USD value to CAI token amount based on current token price in `profile_stores`:
     $$\text{CAI Tokens} = \frac{\text{Requested USD ROI}}{\text{Current CAI Price}}$$
   - Generates an **EIP-712 / ECDSA Signature** using backend signer private key:
     $$\text{Message Hash} = \text{keccak256}(\text{userWallet}, \text{caiAmount}, \text{nonce}, \text{expiry}, \text{contractAddress}, \text{chainId})$$
   - Marks corresponding `cps_incomes` as claimed (`status = 1`, `remaining_usdt = 0`).
   - User submits signature to `CAIRewardClaimVault.sol@claimReward(...)` on-chain.
   - Vault verifies signature, checks replay nonce (`usedNonces[nonce] == false`), and transfers CAI tokens directly to user wallet.

---

### 4.3. Working Incomes & Commission Distribution

All working incomes are accrued in USD and payable in USDT:

1. **Direct Referral Income:**
   - Credited instantly upon direct downline activation in `WalletTransferController@stakeMWT`.
   - Logged in `bonus_rewards` table with `description = 'referral'`.
2. **15-Level Unilevel Income:**
   - Distributed via `LevelIncomeController@levelDistribution` (called daily in `Kernel.php`).
   - Uses `level_details` table to compute differential percentages across uplines.
   - Generates records in `level_incomes` (`description = 'l'` for unilevel, `'r'` for royalty).
3. **Automated Pool Incomes:**
   - **Daily Pool, Weekly Pool, Monthly Leadership Pool**.
   - Processed via `ClubIncomeController` and `LeadershipInfo` tracking leg volume across `club_incomes` and `leadership_incomes`.
4. **Rank Progression (V1 to V8):**
   - Evaluated using `UserDetails@clubBusiness` and `UserDetails@lifetimeAchievementBusiness`.
   - Checks Direct counts, Power Leg Volume, and Weaker Leg Volume.

---

### 4.4. Dynamic Multiplier & Capping Engine (Inside `StackingDetailController`)

No separate `CappingService` is needed. The capping and tier logic is integrated directly into **`StackingDetailController.php`** and called during package activation and hourly `businessUpdate`:

| Tier | Self Investment | Directs Requirement | Power Leg Volume | Weaker Leg Volume | Capping Multiplier |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Default** | $50+ (Any package) | 0 Directs | — | — | **2X** |
| **X3 Tier** | $200 | 5 Directs ($100 each) | $5,000 | $5,000 | **3X** |
| **X5 Tier** | $500 | 15 Directs ($100 each) | $25,000 | $25,000 | **5X** |
| **X10 Tier** | $1,000 | 15 Directs ($200 each) | $50,000+ | $50,000+ | **10X** |

- **Tier Check Function (`StackingDetailController@checkUserCappingTier`):** Evaluates user qualification and updates `capamount` in `stacking_deposites` during `businessUpdate()`.
- **Capping Deduction (`StackingDetailController@cappingCalculation`):** Deducts earned income from `Crypt::decrypt(plan->capamount)`. When cap is exhausted (`capamount == 0`), package status is set to `0` and user must reinvest to continue earning.

---

### 4.5. Working Income Withdrawals (USDT)

1. **User Request:**
   - User navigates to `/User/WithdrawRequest` (`WithdrawInfoController@withdrawRequest`).
   - Enforces $10 minimum and multiple of $10.
   - Two-Factor verification: Sends OTP to registered email via Mailgun (`withdrawOtpMail`).
   - Upon OTP verification, creates pending withdrawal in `transaction_details` (`txntype = 1`, `txndesc = 'Withdrawal'`, `paymentstatus = 1`) and `transaction_infos`.
   - Deducts pending withdrawal balance from `cps_incomes`, `level_incomes`, `bonus_rewards`, `club_incomes`.
2. **Admin Processing:**
   - Admin reviews pending payouts in `TransactionDetailController@userWithdrawreq`.
   - On approval (`AdminwithdrawUpdateone` / `AdminwithdrawUpdateGateway`), triggers payout from `USDTWithdrawalVault.sol` or admin gateway and marks `paymentstatus = 2` (Confirmed).

---

## 5. On-Chain Smart Contracts Specification

### 1. `contracts/CAIToken.sol`
* **Standard:** BEP-20 with fixed 300,000 supply minted at construction.
* **Whitelist Hook:**
  ```solidity
  function _update(address from, address to, uint256 amount) internal override {
      if (from == pancakePair && !isWhitelisted[to]) {
          revert("CAI: Buy restricted to whitelisted wallets");
      }
      super._update(from, to, amount);
  }
  ```
* **Admin Functions:** `setWhitelist(address account, bool status)`, `setBatchWhitelist(address[] accounts, bool status)`, `setPancakePair(address pair)`.
* **Security:** 2-Step Ownable (`transferOwnership` + `acceptOwnership`).

### 2. `contracts/CyeraInvestmentSplitter.sol`
* **Deposit Limits:** Enforces `amount >= 50 * 1e18 && amount <= 2000 * 1e18`.
* **Split Ratio:** Transfers 70% to `TreasuryClaimVault` and 30% to `LiquidityTreasuryWallet`.
* **ReentrancyGuard & Pausable**.

### 3. `contracts/TreasuryClaimVault.sol`
* **Custody:** Safely locks 70% USDT.
* **Authorized Claims:** Only multisig/governance admin can execute claims or fund movements.

### 4. `contracts/CAIRewardClaimVault.sol`
* **ECDSA Signature Verification:**
  ```solidity
  bytes32 hash = keccak256(abi.encodePacked(
      msg.sender,
      caiAmount,
      nonce,
      expiry,
      address(this),
      block.chainid
  ));
  bytes32 ethSignedHash = keccak256(abi.encodePacked("\x19Ethereum Signed Message:\n32", hash));
  require(recoverSigner(ethSignedHash, signature) == backendSigner, "Invalid Signature");
  require(block.timestamp <= expiry, "Signature Expired");
  require(!usedNonces[nonce], "Nonce Already Used");
  usedNonces[nonce] = true;
  ```

### 5. `contracts/USDTWithdrawalVault.sol`
* **USDT Reservoir:** Holds funds for working income withdrawals.
* **Approval Execution:** Admin / backend authorized release to verified user destination address.

---

## 6. Complete Clean Project Directory Structure

```
d:\Cyera AI\
├── contracts/                               # Smart Contracts Suite (Hardhat / Foundry)
│   ├── CAIToken.sol                         # Fixed 300k BEP-20 + Buy Whitelist Hook
│   ├── CyeraInvestmentSplitter.sol          # 70% Vault / 30% Liquidity USDT Splitter
│   ├── TreasuryClaimVault.sol               # 70% USDT Treasury Vault
│   ├── CAIRewardClaimVault.sol              # Cryptographic ECDSA ROI Claim Vault
│   └── USDTWithdrawalVault.sol              # Working Income USDT Withdrawal Vault
│
├── app/
│   ├── Console/
│   │   └── Kernel.php                       # Existing Schedule Runner ($schedule->call('Controller@method'))
│   │
│   ├── Http/Controllers/                   # Existing Controllers (Fully Reused)
│   │   ├── HomeController.php               # User & Admin Dashboard metrics
│   │   ├── WalletTransferController.php     # stakeMWT, package activation, direct bonus
│   │   ├── StackingDetailController.php     # cappingCalculation, cappingUpdate, businessUpdate, boosterCheck, tierCheck
│   │   ├── CpsIncomeController.php          # cpsGeneration (0.5% Daily ROI) called via Kernel.php
│   │   ├── LevelIncomeController.php        # levelDistribution (15-Level Unilevel) called via Kernel.php
│   │   ├── BonusRewardController.php        # Direct & Reward reporting
│   │   ├── ClubIncomeController.php         # Pool & Club incomes called via Kernel.php
│   │   ├── WithdrawInfoController.php       # User USDT Withdrawal Requests
│   │   ├── TransactionDetailController.php  # Admin Withdrawal Approvals & logs
│   │   ├── UserDetailsController.php        # User profile, hierarchy, team search
│   │   └── Web3/                            # New Web3 Bridge Controllers
│   │       ├── InvestmentListenerController.php # On-chain deposit verification
│   │       └── ClaimSignatureController.php     # Generates ECDSA signature for CAI claims
│   │
│   ├── User.php                             # User Model
│   ├── UserDetails.php                      # User Details, sponsor tree, relationships
│   ├── StackingDeposite.php                 # Investment packages & encrypted capamount
│   ├── StackingDetail.php                   # Plan parameters & capping multipliers
│   ├── CpsIncome.php                        # Daily ROI entries
│   ├── LevelIncome.php                      # 15-Level commissions
│   ├── BonusReward.php                      # Direct referral bonuses
│   ├── ClubIncome.php                       # Pool bonuses
│   ├── TransactionDetail.php                # Withdrawal and deposit ledger
│   ├── TransactionInfo.php                  # On-chain hashes and wallet addresses
│   └── AccountDeposit.php                   # User wallet balances
│
├── database/migrations/                     # Existing & Supplemental Migrations
├── resources/views/                         # Blade templates (user & admin dashboards)
└── routes/
    ├── web.php                              # Existing web routes
    └── api.php                              # Web3 endpoints for DApp & frontend
```

---

## 7. Execution Summary

1. **Zero Unnecessary Classes:** No separate console command classes or redundant service layers; everything leverages existing `$schedule->call(...)` in `Kernel.php`.
2. **Native Capping Extension:** Dynamic 2X, 3X, 5X, 10X capping functions are placed directly in `StackingDetailController.php` and evaluated during `businessUpdate` and `stakeMWT`.
3. **Decentralized-Centralized Link:** Only 2 bridge controllers (`InvestmentListenerController` and `ClaimSignatureController`) connect the Laravel database with on-chain Smart Contracts.
