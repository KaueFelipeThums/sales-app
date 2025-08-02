<?php

namespace SalesAppApi\Infrastructure\Database\MigrationRunner;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class MigrationRunner
{
    public function __construct(
        private Database $db,
        private string $migrationsPath
    ) {
        $this->ensureMigrationTableExists();
    }

    public function up(): void
    {
        $files = glob($this->migrationsPath . '/*.php');
        usort($files, fn($a, $b) => strcmp($a, $b));

        $applied = $this->getAppliedMigrations();
        $batch = $this->getNextBatchNumber();

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (in_array($name, $applied)) continue;

            require_once $file;

            $class = $this->getClassFromFile($file);
            if (!class_exists($class)) continue;

            $migration = new $class();
            if ($migration instanceof MigrationInterface) {
                $migration->up($this->db);
                $this->recordMigration($name, $batch);
                echo "✅ Migrated: $name\n";
            }
        }
    }

    public function down(): void
    {
        $lastBatch = $this->getLastBatchNumber();
        if ($lastBatch === null) {
            echo "⚠️ No migrations to rollback.\n";
            return;
        }

        $query = $this->db->query(
            "SELECT migration FROM migrations WHERE batch = :batch ORDER BY id DESC",
            ['batch' => $lastBatch]
        );

        $rows = $this->db->fetchAll($query);

        foreach ($rows as $row) {
            $file = $this->migrationsPath . '/' . $row['migration'] . '.php';
            if (!file_exists($file)) continue;

            require_once $file;

            $class = $this->getClassFromFile($file);
            if (!class_exists($class)) continue;

            $migration = new $class();
            if ($migration instanceof MigrationInterface) {
                $migration->down($this->db);
                $this->removeMigrationRecord($row['migration']);
                echo "⛔ Rolled back: {$row['migration']}\n";
            }
        }
    }

    private function getClassFromFile(string $file): string
    {
        $content = file_get_contents($file);
        preg_match('/class\s+(\w+)/', $content, $matches);
        return 'SalesAppApi\\Infrastructure\\Database\\Migrations\\' . $matches[1];
    }

    private function getAppliedMigrations(): array
    {
        $query = $this->db->query(
            "SELECT migration FROM migrations ORDER BY id ASC"
        );

        return array_column(
            $this->db->fetchAll($query),
            'migration'
        );
    }

    private function getNextBatchNumber(): int
    {
        $query = $this->db->query("SELECT MAX(batch) AS max FROM migrations");
        $result = $this->db->fetch($query);
        return ($result['max'] ?? 0) + 1;
    }

    private function getLastBatchNumber(): ?int
    {
        $query = $this->db->query("SELECT MAX(batch) AS max FROM migrations");
        $result = $this->db->fetch($query);
        return $result['max'] ?? null;
    }

    private function recordMigration(string $migration, int $batch): void
    {
        $this->db->query(
            "INSERT INTO migrations (migration, batch, ran_at) VALUES (:migration, :batch, :ran_at)",
            [
                'migration' => $migration,
                'batch' => $batch,
                'ran_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    private function removeMigrationRecord(string $migration): void
    {
        $this->db->query("DELETE FROM migrations WHERE migration = :migration", [
            'migration' => $migration,
        ]);
    }

    private function ensureMigrationTableExists(): void
    {
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                ran_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;"
        );
    }
}
