<?php

namespace SalesAppApi\Infrastructure\Database\Migrations;

use SalesAppApi\Domain\MigrationInterface;
use SalesAppApi\Infrastructure\Database\Database;

class CreateSalesProductsTable implements MigrationInterface
{
    public function up(Database $db): void
    {
        $db->query(
            "CREATE TABLE IF NOT EXISTS sales_products (
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                sales_id BIGINT NOT NULL,
                products_id BIGINT NOT NULL,
                quantity INT NULL,
                base_value DECIMAL(14,2) NULL,
                INDEX fk_sales_products_sales1_idx (sales_id ASC),
                INDEX fk_sales_products_products1_idx (products_id ASC),
                CONSTRAINT fk_sales_products_sales1
                    FOREIGN KEY (sales_id)
                    REFERENCES sales (id)
                    ON DELETE NO ACTION
                    ON UPDATE NO ACTION,
                CONSTRAINT fk_sales_products_products1
                    FOREIGN KEY (products_id)
                    REFERENCES products (id)
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
        $db->query("DROP TABLE IF EXISTS sales_products");
    }
}
