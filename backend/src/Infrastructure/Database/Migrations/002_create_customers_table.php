<?php

namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreateCustomersTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS customers (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                users_id BIGINT NOT NULL,
                name VARCHAR(255) NOT NULL,
                cpf VARCHAR(14) NOT NULL,
                email VARCHAR(100) NULL,
                zip_code VARCHAR(15) NULL,
                street VARCHAR(100) NULL,
                number VARCHAR(10) NULL,
                complement VARCHAR(100) NULL,
                neighborhood VARCHAR(100) NULL,
                city VARCHAR(100) NULL,
                state VARCHAR(2) NULL,
                is_active TINYINT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL,
                INDEX fk_customers_users1_idx (users_id ASC),
                CONSTRAINT fk_customers_users1
                    FOREIGN KEY (users_id)
                    REFERENCES users (id)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_general_ci;"
        );
    }

    public function down(Database $db): void
    {
        $db->query("DROP TABLE IF EXISTS customers");
    }
}
