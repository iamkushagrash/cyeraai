<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = \App\UserDetails::first();
    $data = [
        'userDetail' => $user,
        'boosterStats' => $user->getBoosterStats(),
        'cappingStats' => $user->getCappingTier(),
        'legStats' => $user->getLegBusiness(),
        'maxUnlockedLevel' => 1,
    ];
    $html = view('user.dashboard', compact('data'))->render();
    echo "DASHBOARD RENDER SUCCESS: " . strlen($html) . " bytes\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
}
