<?php

if (class_exists(\Dotenv\Dotenv::class) && file_exists(__DIR__ . '/../.env.vercel')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.vercel');
    $dotenv->load();
}

// Handle /__ endpoints directly without Laravel
if (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/__')) {
    header('Content-Type: text/plain');

    if (str_starts_with($_SERVER['REQUEST_URI'], '/__env')) {
        echo "APP_KEY: " . (getenv('APP_KEY') ?: 'NOT SET') . PHP_EOL;
        echo "DB_URL: " . (getenv('DB_URL') ?: 'NOT SET') . PHP_EOL;
        echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . PHP_EOL;
        echo "MIGRATE_SECRET: " . (getenv('MIGRATE_SECRET') ?: 'NOT SET') . PHP_EOL;
        echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . PHP_EOL;
        exit;
    }

    if (str_starts_with($_SERVER['REQUEST_URI'], '/__ping')) {
        echo 'pong';
        exit;
    }

    if (str_starts_with($_SERVER['REQUEST_URI'], '/__migrate/')) {
        $token = substr($_SERVER['REQUEST_URI'], strlen('/__migrate/'));
        $token = strtok($token, '?');
        $secret = getenv('MIGRATE_SECRET');

        if ($token !== $secret) {
            echo "ERROR: Invalid token" . PHP_EOL;
            exit;
        }

        try {
            require __DIR__ . '/../vendor/autoload.php';
            $app = require __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();

            $url = parse_url(getenv('DB_URL'));
            $host = str_replace('-pooler', '', $url['host']);
            $port = $url['port'] ?? 5432;
            $dbname = ltrim($url['path'] ?? '/neondb', '/');
            $username = $url['user'] ?? '';
            $password = $url['pass'] ?? '';

            config(['database.connections.pgsql' => [
                'driver' => 'pgsql',
                'host' => $host,
                'port' => $port,
                'database' => $dbname,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8',
                'prefix' => '',
                'search_path' => 'public',
                'sslmode' => 'require',
            ]]);

            echo "Running migrations..." . PHP_EOL;
            \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
                '--force' => true,
            ]);
            echo \Illuminate\Support\Facades\Artisan::output();
            echo PHP_EOL . "Migrations completed!" . PHP_EOL;

            echo "Running seeders..." . PHP_EOL;
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--force' => true,
            ]);
            echo \Illuminate\Support\Facades\Artisan::output();
            echo PHP_EOL . "Seeding completed!" . PHP_EOL;
        } catch (\Throwable $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
            echo 'File: ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
        }
        exit;
    }

    echo 'URI: ' . $_SERVER['REQUEST_URI'] . PHP_EOL;
    exit;
}

require __DIR__ . '/../public/index.php';