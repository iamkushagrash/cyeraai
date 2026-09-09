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

echo "=== USER LIST IN DATABASE ===\n";
$users = UserDetails::orderBy('id', 'asc')->get();

foreach ($users as $u) {
    echo "ID: {$u->id} | UserID: {$u->userid} | SponsorID: {$u->sponsorid} | Status: {$u->userstatus} | Self Inv: {$u->total_self_investment} | Direct Inv: {$u->total_direct_investment} | Team Inv: {$u->total_level_investment}\n";
}

echo "\nTotal Users: " . $users->count() . "\n";
