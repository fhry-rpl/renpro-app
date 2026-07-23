<?php

require __DIR__ . '/../vendor/autoload.php';

/**
 * Log helper — writes to stderr so it appears in Vercel Dashboard logs
 */
function vercel_log(string $message): void {
    $stderr = fopen('php://stderr', 'w');
    if ($stderr) {
        fwrite($stderr, "[vercel] " . $message . "\n");
        fclose($stderr);
    }
}

// Load .env.vercel as fallback if .env doesn't exist (e.g. on Vercel serverless)
// Vercel Dashboard env vars take precedence (safeLoad won't overwrite existing vars)
if (!file_exists(__DIR__ . '/../.env')) {
    $vercelEnv = __DIR__ . '/../.env.vercel';
    if (file_exists($vercelEnv)) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.vercel');
        $dotenv->safeLoad();
    }
}

$uri = $_SERVER['REQUEST_URI'] ?? '/';

// ── Health Check ─────────────────────────────────────────────────────
if (str_starts_with($uri, '/__ping')) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'timestamp' => time()]);
    exit;
}

// ── Migration Endpoint ───────────────────────────────────────────────
if (str_starts_with($uri, '/__migrate/')) {
    header('Content-Type: text/plain');
    $token = substr($uri, strlen('/__migrate/'));
    $token = strtok($token, '?');
    $token = trim($token, '/ ');
    $secret = trim(getenv('MIGRATE_SECRET') ?: '');

    if (!$secret) {
        vercel_log('MIGRATE_SECRET not configured');
        echo "ERROR: MIGRATE_SECRET not configured\n";
        echo "HINT: Set MIGRATE_SECRET di Vercel Dashboard -> Settings -> Environment Variables\n";
        exit;
    }

    if ($token !== $secret) {
        vercel_log("Invalid migrate token: received=[{$token}] length=" . strlen($token));
        http_response_code(403);
        echo "ERROR: Invalid token\n";
        exit;
    }

    try {
        $start = microtime(true);
        vercel_log('Migration started');

        $app = require __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        $exitCode = \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ]);
        $duration = round(microtime(true) - $start, 2);
        echo "migrate:fresh exit code: {$exitCode}\n";
        echo \Illuminate\Support\Facades\Artisan::output();
        echo "\nMigration completed in {$duration}s.\n";

        vercel_log("Migration finished in {$duration}s (exit code: {$exitCode})");
    } catch (\Throwable $e) {
        $duration = round(microtime(true) - $start, 2);
        vercel_log("Migration FAILED after {$duration}s: " . $e->getMessage());
        echo 'Error: ' . $e->getMessage() . "\n";
        echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
    }
    exit;
}

// ── Maintenance Check ────────────────────────────────────────────────
// Cek apakah database sudah siap sebelum routing ke Laravel
// Jika tabel belum ada, tampilkan halaman maintenance (bukan error 500)
$dbCheckNeeded = !str_starts_with($uri, '/build/')
    && !str_starts_with($uri, '/favicon')
    && !str_starts_with($uri, '/robots.txt');

if ($dbCheckNeeded && getenv('DB_URL') && !str_starts_with($uri, '/__')) {
    try {
        $dsn = 'pgsql:host=' . (getenv('DB_HOST') ?: '127.0.0.1')
            . ';port=' . (getenv('DB_PORT') ?: '5432')
            . ';dbname=' . (getenv('DB_DATABASE') ?: 'laravel')
            . ';sslmode=' . (getenv('DB_SSLMODE') ?: 'prefer');

        $pdo = new PDO($dsn, getenv('DB_USERNAME') ?: 'postgres', getenv('DB_PASSWORD') ?: '', [
            PDO::ATTR_TIMEOUT => 3,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        // Cek apakah tabel migrations ada (indikator sudah pernah migrate)
        $tables = $pdo->query("SELECT EXISTS (
            SELECT FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'migrations'
        )")->fetchColumn();

        if (!$tables) {
            vercel_log('Database not migrated — showing maintenance page');
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(503);
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Sedang dalam perbaikan</title>';
            echo '<style>body{font-family:sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#f5f5f5;color:#333}.card{text-align:center;padding:40px;background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.1)}h1{color:#e53e3e}p{color:#666}</style></head>';
            echo '<body><div class="card"><h1>⏳ Sedang dalam perbaikan</h1>';
            echo '<p>Database sedang diatur. Silakan kembali dalam beberapa saat.</p>';
            echo '<p><small>Akses <code>/__migrate/{MIGRATE_SECRET}</code> untuk menjalankan migrasi.</small></p>';
            echo '</div></body></html>';
            exit;
        }
    } catch (\PDOException $e) {
        vercel_log('Database connection failed in maintenance check: ' . $e->getMessage());
        // Jika gagal konek, tetap lanjut ke Laravel — biarkan Laravel handle error-nya
    }
}

// ── Error Handler: catch semua error agar muncul di Vercel logs ──────
set_error_handler(function ($severity, $message, $file, $line) {
    vercel_log("PHP Error [{$severity}]: {$message} in {$file}:{$line}");
});

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    vercel_log('Uncaught Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    if (getenv('APP_DEBUG') === 'true') {
        header('Content-Type: text/plain');
        echo "Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "Stack:\n" . $e->getTraceAsString() . "\n";
    } else {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Terjadi Kesalahan</title>';
        echo '<style>body{font-family:sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;background:#f5f5f5;color:#333}.card{text-align:center;padding:40px;background:white;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.1)}h1{color:#e53e3e}p{color:#666}</style></head>';
        echo '<body><div class="card"><h1>Terjadi Kesalahan</h1><p>Silakan coba lagi nanti.</p></div></body></html>';
    }
    exit;
}
