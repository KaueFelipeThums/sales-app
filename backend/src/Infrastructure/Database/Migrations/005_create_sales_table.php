<?php

namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreateSalesTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS sales (
                id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                payment_methods_id BIGINT NOT NULL,
                users_id BIGINT NOT NULL,
                customers_id BIGINT NOT NULL,
                total_value DECIMAL(14,2) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NULL,
                INDEX fk_sales_payment_methods_idx (payment_methods_id ASC),
                INDEX fk_sales_customers1_idx (customers_id ASC),
                INDEX fk_sales_users1_idx (users_id ASC),
                CONSTRAINT fk_sales_payment_methods
                    FOREIGN KEY (payment_methods_id)
                    REFERENCES payment_methods (id)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                CONSTRAINT fk_sales_customers1
                    FOREIGN KEY (customers_id)
                    REFERENCES customers (id)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                CONSTRAINT fk_sales_users1
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
        $db->query("DROP TABLE IF EXISTS sales");
    }
}
