<?php

require __DIR__ . '/../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (str_starts_with($uri, '/__ping')) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'timestamp' => time()]);
    exit;
}

if (str_starts_with($uri, '/__migrate/')) {
    header('Content-Type: text/plain');
    $token = substr($uri, strlen('/__migrate/'));
    $token = strtok($token, '?');
    $secret = getenv('MIGRATE_SECRET');

    if (!$secret) {
        echo "ERROR: MIGRATE_SECRET not configured\n";
        exit;
    }

    if ($token !== $secret) {
        http_response_code(403);
        echo "ERROR: Invalid token\n";
        exit;
    }

    try {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        $exitCode = \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        echo "migrate exit code: {$exitCode}\n";
        echo \Illuminate\Support\Facades\Artisan::output();

        $exitCode = \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        echo "db:seed exit code: {$exitCode}\n";
        echo \Illuminate\Support\Facades\Artisan::output();

        echo "\nMigration completed successfully.\n";
    } catch (\Throwable $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
        echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
    }
    exit;
}

require __DIR__ . '/../public/index.php';
