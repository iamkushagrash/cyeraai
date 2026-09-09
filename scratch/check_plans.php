<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\StackingDetail;
use App\ProfileStore;

echo "=== PLANS ===\n";
print_r(StackingDetail::all()->toArray());

echo "=== PROFILE STORE ===\n";
print_r(ProfileStore::first()->toArray());
