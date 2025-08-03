<?php

namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreateRefreshTokensTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS refresh_tokens (
                id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
                users_id BIGINT NOT NULL,
                token VARCHAR(255) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at DATETIME NOT NULL
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_general_ci;"
        );
    }

    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS refresh_tokens");
    }
}
