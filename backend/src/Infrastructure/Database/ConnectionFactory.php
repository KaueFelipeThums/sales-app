<?php

namespace SalesAppApi\Infrastructure\Database;

use PDO;
use Exception;
use PDOException;
use SalesAppApi\Shared\Exceptions\EnvironmentAwareException;

class ConnectionFactory
{
    public static function make(): PDO
    {
        $driver = $_ENV['DB_CONNECTION'] ?: 'mysql';
        $host   = $_ENV['DB_HOST'];
        $port   = $_ENV['DB_PORT'] ?: 3306;
        $db     = $_ENV['DB_DATABASE'];
        $user   = $_ENV['DB_USERNAME'];
        $pass   = $_ENV['DB_PASSWORD'];

        if (empty($host) || empty($db) || empty($user)) {
            throw new Exception('Não foi possível conectar ao banco de dados: configurações ausentes.');
        }

        $dsn = "{$driver}:host={$host};port={$port};dbname={$db};charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            $ex = new EnvironmentAwareException($e->getMessage(), $e->getCode());
            throw new Exception($ex->getMessage());
        }

        return $pdo;
    }
}
