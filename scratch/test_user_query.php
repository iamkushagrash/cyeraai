<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\UserDetails;

$u1 = UserDetails::find(1);
echo "U1: ID={$u1->id}, UserID={$u1->userid}, SponsorID={$u1->sponsorid}\n";

$directs = UserDetails::where('sponsorid', $u1->userid)->get();
echo "Directs count for U1: " . $directs->count() . "\n";
foreach ($directs as $d) {
    echo "  - Direct ID: {$d->id}, UserID: {$d->userid}, SponsorID: {$d->sponsorid}, SelfInv: {$d->total_self_investment}, TotalInv: {$d->total_investment}\n";
}
