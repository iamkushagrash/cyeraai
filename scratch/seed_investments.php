<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\UserDetails;
use App\StackingDeposite;
use App\StackingDetail;
use App\ProfileStore;
use App\BonusReward;
use App\WalletTransfer;
use App\Http\Controllers\StackingDetailController;
use App\Http\Controllers\RankIncomeController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

echo "====================================================\n";
echo " SEEDING RANDOM $200 - $2000 INVESTMENTS FOR TESTING\n";
echo "====================================================\n\n";

$price = ProfileStore::where('id', 1)->first() ?? (object)['price' => 1];
$plan = StackingDetail::where('status', 1)->first();

if (!$plan) {
    echo "ERROR: No active staking plan found in stacking_details!\n";
    exit(1);
}

// Pre-defined varied investment amounts for realistic testing
$testInvestments = [
    1 => 1000, // User 1: $1000
    2 => 1500, // User 2: $1500
    3 => 800,  // User 3: $800
    4 => 1200, // User 4: $1200
    5 => 500,  // User 5: $500
    6 => 2000, // User 6: $2000
    7 => 600,  // User 7: $600
];

$cappingFunction = new StackingDetailController();

$users = UserDetails::orderBy('id', 'asc')->get();

foreach ($users as $user) {
    $amount = isset($testInvestments[$user->id]) 
        ? $testInvestments[$user->id] 
        : (rand(4, 40) * 50); // Random multiple of $50 between $200 and $2000

    echo "Processing User ID: {$user->id} (UserID: {$user->userid}) | Sponsor: {$user->sponsorid} | Investment: \${$amount}...\n";

    DB::beginTransaction();
    try {
        // 1. Create WalletTransfer record
        $insWalletEntry = WalletTransfer::create([
            'userid'        => $user->id,
            'txnid'         => 0,
            'fromWallet'    => 'wallet',
            'toWallet'      => 'basic',
            'amount'        => $amount,
            'fromUser'      => $user->id,
            'created_at'    => date('Y-m-d H:i:s'),
            'release_date'  => date('Y-m-d'),
        ]);

        // Default 2x capping multiplier
        $multiplier = 2;
        $capAmount = $amount * $multiplier;

        // 2. Create StackingDeposite record
        $deposit = StackingDeposite::create([
            'userid'        => $user->id,
            'txnid'         => $insWalletEntry->id,
            'amount'        => $amount / ($price->price ?: 1),
            'usdt'          => $amount,
            'capamount'     => Crypt::encrypt($capAmount),
            'planid'        => $plan->id,
            'status'        => 1,
            'roidouble'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'istatus'       => $multiplier,
            'staketype'     => 1,
            'batchstatus'   => 0, // Set to 0 so businessUpdate will process it
        ]);

        // 3. Update User details
        $userUpdate = UserDetails::where('id', $user->id);
        $userUpdate->increment('userstate');
        $userUpdate->increment('current_self_investment', $amount);
        $userUpdate->increment('total_self_investment', $amount);
        $userUpdate->increment('current_investment', $amount);
        $userUpdate->increment('total_investment', $amount);
        $userUpdate->update([
            'userstatus' => 1,
            'capping'    => 0,
            'roi_status' => 1,
        ]);

        // 4. 5% Direct Referral Commission to Sponsor
        $guiderDetail = UserDetails::where('userid', $user->sponsorid)->first();
        if ($guiderDetail && ($guiderDetail->userstatus == 1 && $guiderDetail->userstate > 0)) {
            $directAmt = ($amount * 5) / 100; // 5% Direct Commission
            $finalDirect = $cappingFunction->cappingCalculation($guiderDetail->id, $directAmt);

            if ($finalDirect > 0) {
                BonusReward::create([
                    'userid'         => $guiderDetail->id,
                    'fromuser'       => $user->id,
                    'amount'         => $finalDirect / ($price->price ?: 1),
                    'remaining'      => $finalDirect / ($price->price ?: 1),
                    'amt_usdt'       => $finalDirect,
                    'remaining_usdt' => $finalDirect,
                    'txnid'          => $deposit->id,
                    'description'    => 'referral',
                    'status'         => 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
                echo "  -> Credited 5% Direct Bonus of \${$finalDirect} to Sponsor UserID: {$guiderDetail->userid}\n";
            }
        }

        DB::commit();

        // 5. Run real-time business update & level income distribution
        $cappingFunction->businessUpdate();

    } catch (\Exception $e) {
        DB::rollBack();
        echo "  ERROR processing User ID {$user->id}: " . $e->getMessage() . "\n";
    }
}

// 6. Sync Rank Qualifications for all users
echo "\n--- SYNCING RANK QUALIFICATIONS ---\n";
foreach (UserDetails::all() as $u) {
    RankIncomeController::checkAndSyncUserRank($u->id);
}

echo "\n====================================================\n";
echo " SUMMARY REPORT POST INVESTMENT SEEDING\n";
echo "====================================================\n";

foreach (UserDetails::orderBy('id', 'asc')->get() as $u) {
    $directInc = BonusReward::where('userid', $u->id)->sum('amt_usdt');
    $levelInc = \App\LevelIncome::where('userid', $u->id)->sum('amt_usdt');
    $rankInfo = $u->getRankQualification();
    $legs = $u->getPowerAndOtherLegTurnover();

    echo "User [ID: {$u->id} | UserID: {$u->userid} | Sponsor: {$u->sponsorid}]\n";
    echo "  - Self Invested: \${$u->total_self_investment}\n";
    echo "  - Direct Business: \${$u->total_direct_investment} (Active Directs: {$u->active_direct})\n";
    echo "  - Team Business: \${$u->total_level_investment} (Active Downline: {$u->active_downline})\n";
    echo "  - Power Leg: \${$legs['power_leg']} | Other Legs: \${$legs['other_legs']}\n";
    echo "  - Direct Bonus Earned: \${$directInc}\n";
    echo "  - Level Income Earned: \${$levelInc}\n";
    echo "  - Rank: {$u->rank_name} (Qualified: {$rankInfo['current_rank']})\n";
    echo "  - Capping Multiplier: {$u->getCappingTier()['multiplier']}X\n";
    echo "----------------------------------------------------\n";
}

echo "\nInvestment seeding completed successfully!\n";
