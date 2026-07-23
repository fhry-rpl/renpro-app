<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Vercel serverless filesystem is read-only except /tmp
// Redirect bootstrap/cache to /tmp so Laravel can write cache files
if (getenv('VERCEL')) {
    $tmpCachePath = '/tmp/bootstrap/cache';
    if (!is_dir($tmpCachePath)) {
        mkdir($tmpCachePath, 0777, true);
    }
    // Copy any existing cache files from the real path
    $realCachePath = __DIR__ . '/cache';
    if (is_dir($realCachePath)) {
        foreach (glob($realCachePath . '/*.php') as $file) {
            $dest = $tmpCachePath . '/' . basename($file);
            if (!file_exists($dest)) {
                @copy($file, $dest);
            }
        }
    }
    $app->useBootstrapPath($tmpCachePath);
}

return $app;
