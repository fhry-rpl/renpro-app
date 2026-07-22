<?php

require __DIR__ . '/../vendor/autoload.php';

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

        if (!$secret) {
            header('HTTP/1.1 404 Not Found');
            echo "ERROR: Migration secret not configured" . PHP_EOL;
            exit;
        }

        if ($token !== $secret) {
            header('HTTP/1.1 403 Forbidden');
            echo "ERROR: Invalid migration token" . PHP_EOL;
            exit;
        }

        try {
            $dbUrl = getenv('DB_URL');
            if (!$dbUrl) {
                header('HTTP/1.1 500 Internal Server Error');
                echo "ERROR: DB_URL environment variable is not set. Set it in Vercel Dashboard." . PHP_EOL;
                exit;
            }

            // Add endpoint ID to the connection string for Neon SNI support
            $url = parse_url($dbUrl);
            $endpointId = substr($url['host'], 0, strpos($url['host'], '.'));

            if (strpos($dbUrl, 'options=') === false) {
                $separator = strpos($dbUrl, '?') === false ? '?' : '&';
                $dbUrl .= $separator . "options=endpoint%3D{$endpointId}";
            }

            // Connect directly using pg_connect for migration
            $connString = str_replace('postgresql://', '', $dbUrl);
            $conn = pg_connect($connString, PGSQL_CONNECT_FORCE_NEW);
            if (!$conn) {
                throw new \Exception("Failed to connect: " . pg_last_error());
            }
            echo "Database connected!" . PHP_EOL;
            pg_close($conn);

            $app = require __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();

            $url = parse_url($dbUrl);
            $host = str_replace('-pooler', '', $url['host']);
            $port = $url['port'] ?? 5432;
            $dbname = ltrim($url['path'] ?? '/neondb', '/');
            $username = $url['user'] ?? '';
            $password = $url['pass'] ?? '';

            config([
                'database.default' => 'pgsql',
                'database.connections.pgsql' => [
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
                    'options' => "endpoint={$endpointId}",
                ],
            ]);

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