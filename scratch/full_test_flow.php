<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use App\UserDetails;
use App\StackingDeposite;
use App\StackingDetail;
use App\AccountDeposit;
use App\TransactionDetail;
use App\TransactionInfo;
use App\WalletTransfer;
use App\BonusReward;
use App\LevelIncome;
use App\CpsIncome;
use App\ProfileStore;
use App\Http\Controllers\StackingDetailController;
use App\Http\Controllers\RankIncomeController;
use App\Http\Controllers\CpsIncomeController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

echo "=====================================================================\n";
echo " COMPLETE REAL-FLOW SIMULATION: ADMIN FUND -> USER STAKE -> INCOMES\n";
echo "=====================================================================\n\n";

$price = ProfileStore::where('id', 1)->first() ?? (object)['price' => 1];
$plan = StackingDetail::where('status', 1)->first() ?? (object)['id' => 1, 'capping' => 2, 'cps' => 0.5];

// 1. CLEAN UP PREVIOUS TEST INCOME & DEPOSIT DATA FOR CLEAN BASELINE
echo "1. Resetting test deposit & income records for a clean test run...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
StackingDeposite::truncate();
WalletTransfer::truncate();
AccountDeposit::truncate();
TransactionDetail::truncate();
TransactionInfo::truncate();
BonusReward::truncate();
LevelIncome::truncate();
CpsIncome::truncate();
\App\RankIncome::truncate();
\App\LeadershipInfo::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// Reset user counters
UserDetails::query()->update([
    'current_self_investment'   => 0,
    'total_self_investment'     => 0,
    'current_direct_investment' => 0,
    'total_direct_investment'   => 0,
    'current_level_investment'  => 0,
    'total_level_investment'    => 0,
    'current_investment'        => 0,
    'total_investment'          => 0,
    'active_direct'             => 0,
    'active_downline'           => 0,
    'booster'                   => 1,
    'userstatus'                => 0,
    'userstate'                 => 0,
    'capping'                   => 0,
    'roi_status'                => 1,
    'rank_name'                 => 'None',
    'rank_level'                => 0,
]);

echo "   -> Clean baseline created successfully.\n\n";

// 2. DEFINE STAKE AMOUNTS FOR SERIAL LEVEL USERS (200$ - 2000$)
$investAmounts = [
    1 => 1000, // Root User 1: $1000
    2 => 1500, // User 2 (L1 of 1): $1500
    3 => 800,  // User 3 (L2 of 1, L1 of 2): $800
    4 => 1200, // User 4 (L3 of 1, L2 of 2, L1 of 3): $1200
    5 => 500,  // User 5: $500
    6 => 2000, // User 6: $2000
    7 => 600,  // User 7: $600
];

$cappingController = new StackingDetailController();

echo "2. Executing Admin Fund Topup & User Staking Flow...\n";

$users = UserDetails::orderBy('id', 'asc')->get();

foreach ($users as $user) {
    $stakeAmount = $investAmounts[$user->id] ?? 500;
    $adminFund = $stakeAmount + 200; // Give extra $200 in wallet balance

    echo "---------------------------------------------------------------------\n";
    echo "USER ID: {$user->id} (UserID: {$user->userid}) | Sponsor: {$user->sponsorid}\n";

    // --- STEP A: ADMIN ADDS FUNDS TO USER WALLET ---
    $insertTxn = TransactionDetail::insertGetId([
        "userid"        => $user->id,
        "txntype"       => 0,
        "amountsftc"    => ($adminFund / $price->price),
        "amountusdt"    => $adminFund,
        "remaining"     => 0,
        "paymentstatus" => 2,
        "txndesc"       => "Wallet Deposite",
        "comments"      => 'wallet',
        "planid"        => 0,
        "currency"      => 'usdt',
        "paidby"        => 1,
        "created_at"    => now(),
        "release_date"  => date('Y-m-d'),
    ]);

    TransactionInfo::create([
        "txnid"            => $insertTxn,
        "payment_addr"     => 'Admin Deposit',
        "transaction_hash" => 'Admin Deposit',
        "contract_addr"    => 'Test Topup Fund',
        "amount"           => $adminFund,
        "txn_status"       => 2,
    ]);

    $accDep = AccountDeposit::firstOrNew(['userid' => $user->id]);
    $currentWalletAmt = !empty($accDep->amount) ? Crypt::decrypt($accDep->amount) : 0;
    $accDep->amount = Crypt::encrypt($currentWalletAmt + $adminFund);
    $accDep->save();

    WalletTransfer::create([
        'userid'       => $user->id,
        'txnid'        => $insertTxn,
        'fromWallet'   => 'deposite',
        'toWallet'     => 'wallet',
        'amount'       => $adminFund,
        'release_date' => date('Y-m-d'),
        'created_at'   => now(),
    ]);

    echo "  [A] Admin credited \${$adminFund} USDT to Wallet (Balance: \${$adminFund})\n";

    // --- STEP B: USER STAKES VIA WALLET ---
    // 1. Outgoing transfer from wallet to basic
    $outTxn = WalletTransfer::create([
        'userid'       => $user->id,
        'txnid'        => 0,
        'fromWallet'   => 'wallet',
        'toWallet'     => 'basic',
        'amount'       => $stakeAmount,
        'fromUser'     => $user->id,
        'created_at'   => now(),
        'release_date' => date('Y-m-d'),
    ]);

    // 2. Reduce AccountDeposit wallet balance
    $newWalletBal = $adminFund - $stakeAmount;
    $accDep->amount = Crypt::encrypt($newWalletBal);
    $accDep->save();

    // 3. Determine Capping Tier Multiplier (Default 2X)
    $capStats = $user->getCappingTier();
    $multiplier = $capStats['multiplier'] ?: 2;
    $capAmount = $stakeAmount * $multiplier;

    // 4. Create StackingDeposite Record
    $deposit = StackingDeposite::create([
        'userid'      => $user->id,
        'txnid'       => $outTxn->id,
        'amount'      => ($stakeAmount / $price->price),
        'usdt'        => $stakeAmount,
        'capamount'   => Crypt::encrypt($capAmount),
        'planid'      => $plan->id,
        'status'      => 1,
        'roidouble'   => 1,
        'created_at'  => date('Y-m-d H:i:s'),
        'istatus'     => $multiplier,
        'staketype'   => 1,
        'batchstatus' => 0,
    ]);

    // 5. Update user state & self investment
    $user->increment('userstate');
    $user->increment('current_self_investment', $stakeAmount);
    $user->increment('total_self_investment', $stakeAmount);
    $user->increment('current_investment', $stakeAmount);
    $user->increment('total_investment', $stakeAmount);
    $user->update([
        'userstatus' => 1,
        'capping'    => 0,
        'roi_status' => 1,
    ]);

    echo "  [B] User Staked \${$stakeAmount} USDT in Plan (Remaining Wallet: \${$newWalletBal} | Cap: \${$capAmount} [{$multiplier}X])\n";

    // 6. Direct Commission (5%) to Sponsor
    $guiderDetail = UserDetails::where('userid', $user->sponsorid)->first();
    if ($guiderDetail && ($guiderDetail->userstatus == 1 && $guiderDetail->userstate > 0)) {
        $directAmt = ($stakeAmount * 5) / 100;
        $finalDirect = $cappingController->cappingCalculation($guiderDetail->id, $directAmt);

        if ($finalDirect > 0) {
            BonusReward::create([
                'userid'         => $guiderDetail->id,
                'fromuser'       => $user->id,
                'amount'         => $finalDirect / $price->price,
                'remaining'      => $finalDirect / $price->price,
                'amt_usdt'       => $finalDirect,
                'remaining_usdt' => $finalDirect,
                'txnid'          => $deposit->id,
                'description'    => 'referral',
                'status'         => 0,
                'created_at'     => now(),
            ]);
            echo "  [C] 5% Direct Referral Commission: \${$finalDirect} USDT credited to Sponsor UserID: {$guiderDetail->userid}\n";
        }
    }

    // 7. Process real-time business update & Level 2-15 unilevel distribution
    $cappingController->businessUpdate();
}

// 3. RUN RANK CHECK CRON / SYNC
echo "\n3. Checking & Syncing Rank Qualifications for all Users...\n";
foreach (UserDetails::all() as $u) {
    RankIncomeController::checkAndSyncUserRank($u->id);
}

// 4. SIMULATE DAILY CPS (ROI) GENERATION
echo "\n4. Simulating Daily Staking ROI (CPS) Generation...\n";
// Set deposit created_at to yesterday so CPS cron qualifies it
StackingDeposite::query()->update(['created_at' => now()->subDay()]);

$cpsController = new CpsIncomeController();
$cpsController->cpsGeneration();

echo "   -> Daily CPS executed.\n\n";

// 5. DETAILED DIAGNOSTIC & VERIFICATION REPORT
echo "=====================================================================\n";
echo " VERIFICATION SUMMARY REPORT (ALL SYSTEMS & INCOMES)\n";
echo "=====================================================================\n";

foreach (UserDetails::orderBy('id', 'asc')->get() as $u) {
    $walletBal = !empty(AccountDeposit::where('userid', $u->id)->first()->amount) 
        ? Crypt::decrypt(AccountDeposit::where('userid', $u->id)->first()->amount) 
        : 0;

    $activeDeposits = StackingDeposite::where('userid', $u->id)->where('status', 1)->get();
    $totDep = $activeDeposits->sum('usdt');
    $remCap = $u->remainingCapping();

    $directInc = BonusReward::where('userid', $u->id)->sum('amt_usdt');
    $levelInc = LevelIncome::where('userid', $u->id)->sum('amt_usdt');
    $cpsInc = CpsIncome::where('userid', $u->id)->sum('amt_usdt');
    $totalIncome = $directInc + $levelInc + $cpsInc;

    $legs = $u->getLegBusiness();
    $rankInfo = $u->getRankQualification();
    $boosterStats = $u->getBoosterStats();
    $cappingStats = $u->getCappingTier();

    echo "USER ID: {$u->id} | UserID: {$u->userid} | Sponsor: {$u->sponsorid}\n";
    echo "  - Wallet Balance: \${$walletBal} USDT\n";
    echo "  - Self Investment: \${$totDep} USDT (Active Package)\n";
    echo "  - Remaining Capping: \${$remCap} USDT\n";
    echo "  - Direct Business: \${$u->total_direct_investment} (Active Directs: {$u->active_direct})\n";
    echo "  - Team Business: \${$u->total_level_investment} (Active Downline: {$u->active_downline})\n";
    echo "  - Power Leg: \${$legs['power']} | Weaker/Other Legs: \${$legs['weaker']}\n";
    echo "  - Booster Tier: Tier {$boosterStats['active_tier']} ({$boosterStats['daily_roi_pct']}% Daily ROI)\n";
    echo "  - Capping Multiplier: {$cappingStats['multiplier']}X\n";
    echo "  - Current Rank: {$u->rank_name} (Level: {$u->rank_level})\n";
    echo "  - INCOMES EARNED:\n";
    echo "      * Direct Referral Bonus: \${$directInc} USDT\n";
    echo "      * Level (Unilevel) Bonus: \${$levelInc} USDT\n";
    echo "      * Daily Staking ROI (CPS): \${$cpsInc} USDT\n";
    echo "      * TOTAL EARNED: \${$totalIncome} USDT\n";
    echo "---------------------------------------------------------------------\n";
}

echo "\nSimulation completed successfully!\n";
