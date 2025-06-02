<?php
// /public_html/queue-processor.php

// Security: Only allow CLI and localhost access
if (php_sapi_name() !== 'cli' && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    die('Access denied');
}

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// CORRECTED KERNEL CLASS
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$maxTime = 55; // Keep below Hostinger's 60s timeout
$endTime = time() + $maxTime;

while (time() < $endTime) {
    // Process one job
    $kernel->call('queue:work', [
        '--once' => true,
        '--queue' => 'default',
        '--tries' => 3,
        '--timeout' => 30,
    ]);

    // Exit early if queue is empty
    if (app('queue')->size('default') === 0) break;

    sleep(1); // Reduce CPU load
}

// Optional logging
$duration = time() - ($endTime - $maxTime);
file_put_contents(__DIR__.'/queue-processor.log', "Processed for {$duration}s\n", FILE_APPEND);
