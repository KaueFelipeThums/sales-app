<?php

namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreateProductsTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS products (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                users_id BIGINT NOT NULL,
                name VARCHAR(255) NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(14,2) NOT NULL,
                is_active TINYINT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL,
                INDEX fk_products_users1_idx (users_id ASC),
                CONSTRAINT fk_products_users1
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
        $db->query("DROP TABLE IF EXISTS products");
    }
}
