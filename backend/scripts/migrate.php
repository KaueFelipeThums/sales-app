<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use SalesAppApi\Infrastructure\Database\Database;
use SalesAppApi\Infrastructure\Database\MigrationRunner\MigrationRunner;

try {
    $envPath = __DIR__ . "/../";

    if (file_exists($envPath . ".env")) {
        $dotenv = Dotenv::createImmutable($envPath);
        $dotenv->load();
    }

    $db = new Database();
    $runner = new MigrationRunner($db, __DIR__ . '/../src/Infrastructure/Database/Migrations');

    $action = $argv[1] ?? null;

    if ($action === 'up') {
        $runner->up();
    } elseif ($action === 'down') {
        $runner->down();
    } else {
        echo "Usage: php migrate.php [up|down]\n";
    }

} catch (Throwable $e) {
    echo "Erro ao executar migrations: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}