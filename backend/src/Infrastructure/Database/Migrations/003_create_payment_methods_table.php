<?php

namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreatePaymentMethodsTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS payment_methods (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                users_id BIGINT NOT NULL,
                name VARCHAR(255) NOT NULL,
                installments INT NOT NULL,
                is_active TINYINT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL,
                INDEX fk_payment_methods_users1_idx (users_id ASC),
                CONSTRAINT fk_payment_methods_users1
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
        $db->query("DROP TABLE IF EXISTS payment_methods");
    }
}
