<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\StackingDeposite;

echo "=== EXISTING DEPOSITS ===\n";
print_r(StackingDeposite::all()->toArray());
