<?php
namespace SalesAppApi\Infrastructure\Database;

use Exception;
use PDO;
use PDOException;
use PDOStatement;
use SalesAppApi\Shared\Exceptions\EnvironmentAwareException;

class Database {
    private PDO $dbInstance;

    public function __construct()
    {
        $this->dbInstance = ConnectionFactory::make();
    }

    public function query(string $sqlString, array $parameters = []): PDOStatement
    {
        try {
            $sth = $this->dbInstance->prepare($sqlString);
            $sth->execute($parameters);
            return $sth;
        } catch (PDOException $e) {
            if($e->getCode() == 23000) {
                throw new Exception("Registro já vinculado", 422);
            }

            $ex = new EnvironmentAwareException("Erro ao executar query: " . $e->getMessage(), 500);
            throw new Exception($ex->getMessage(), 500);
        }
    }

    public function lastInsertId(): int
    {
        return $this->dbInstance->lastInsertId();
    }

    public function fetch(PDOStatement $statement)
    {
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll(PDOStatement $statement): array
    {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function beginTransaction(): void
    {
        $this->dbInstance->beginTransaction();
    }

    public function commit(): void
    {
        $this->dbInstance->commit();
    }

    public function rollBack(): void
    {
        if ($this->dbInstance->inTransaction()) {
            $this->dbInstance->rollBack();
        }
    }
}

