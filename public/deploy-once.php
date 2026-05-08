<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$expectedToken = env('DEPLOY_TOKEN');
$providedToken = $_GET['token'] ?? '';

header('Content-Type: text/plain; charset=UTF-8');

if (! $expectedToken || ! hash_equals((string) $expectedToken, (string) $providedToken)) {
    http_response_code(403);
    echo "Token non valido.\n";
    exit;
}

if (app()->environment('local')) {
    http_response_code(403);
    echo "Deploy disabilitato in ambiente local.\n";
    exit;
}

$commands = [
    ['key' => 'clear', 'command' => 'optimize:clear', 'params' => []],
    ['key' => 'migrate', 'command' => 'migrate', 'params' => ['--force' => true]],
    ['key' => 'seed', 'command' => 'db:seed', 'params' => ['--force' => true]],
    ['key' => 'storage', 'command' => 'storage:link', 'params' => []],
    ['key' => 'cache', 'command' => 'optimize', 'params' => []],
];

echo "Gruppo Kosmos Client Hub - deploy\n\n";

foreach ($commands as $item) {
    $enabled = ($_GET[$item['key']] ?? '1') !== '0';

    if (! $enabled) {
        echo "[skip] {$item['command']}\n";
        continue;
    }

    try {
        Artisan::call($item['command'], $item['params']);
        echo "[ok] {$item['command']}\n";
        $output = trim(Artisan::output());
        if ($output !== '') {
            echo $output."\n";
        }
    } catch (Throwable $exception) {
        http_response_code(500);
        echo "[errore] {$item['command']}\n";
        echo $exception->getMessage()."\n";
        exit;
    }
}

echo "\nCompletato. Cancella subito public/deploy-once.php e rimuovi DEPLOY_TOKEN dal file .env.\n";
