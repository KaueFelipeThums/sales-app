<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\Product;
use SalesAppApi\Domain\SaleProduct;
use SalesAppApi\Domain\SaleProductRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class SaleProductRepository implements SaleProductRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function getAllSaleProductsBySaleId(int $saleId): array
    {
        $query = $this->database->query(
            "SELECT 
                -- Sale Product
                sale_products.id AS sale_products_id,
                sale_products.sales_id AS sale_products_sales_id,
                sale_products.products_id AS sale_products_products_id,
                sale_products.quantity SAS sale_products_quantity,
                sale_products.base_value AS sale_products_base_value,

                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at,
            FROM 
                sale_products
            LEFT JOIN
                products ON sale_products.products_id = products.id 
            WHERE 
                sale_products.sales_id = :sales_id",
            ["sales_id" => $saleId]
        );

        $arrayProducts = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayProducts[] = new SaleProduct(
                $r['sale_products_id'],
                $r['sale_products_sales_id'],
                $r['sale_products_products_id'],
                $r['sale_products_quantity'],
                $r['sale_products_base_value'],
                new Product(
                    $r['products_id'],
                    $r['products_users_id'],
                    $r['products_name'],
                    $r['products_quantity'],
                    $r['products_price'],
                    $r['products_is_active'],
                    new DateTime($r['products_created_at']),
                    !empty($r['products_updated_at']) ? new DateTime($r['products_updated_at']) : null,
                    null
                )
            );
        }
        return $arrayProducts;
    }

    public function getSaleProductById(int $id): ?SaleProduct
    {
        $query = $this->database->query(
            "SELECT 
                -- Sale Product
                sale_products.id AS sale_products_id,
                sale_products.sales_id AS sale_products_sales_id,
                sale_products.products_id AS sale_products_products_id,
                sale_products.quantity SAS sale_products_quantity,
                sale_products.base_value AS sale_products_base_value,

                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at,
            FROM 
                sale_products
            LEFT JOIN
                products ON sale_products.products_id = products.id 
            WHERE 
                sale_products.id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new SaleProduct(
            $response['sale_products_id'],
            $response['sale_products_sales_id'],
            $response['sale_products_products_id'],
            $response['sale_products_quantity'],
            $response['sale_products_base_value'],
            new Product(
                $response['products_id'],
                $response['products_users_id'],
                $response['products_name'],
                $response['products_quantity'],
                $response['products_price'],
                $response['products_is_active'],
                new DateTime($response['products_created_at']),
                !empty($response['products_updated_at']) ? new DateTime($response['products_updated_at']) : null,
                null
            )
        );
    }

    public function create(SaleProduct $saleProduct): int
    {
        $this->database->query(
            "INSERT INTO
                products 
                (
                    sales_id,
                    products_id,
                    quantity,
                    base_value
                )
            VALUES 
                (
                    :sales_id,
                    :products_id,
                    :quantity,
                    :base_value
                )",
            [
                'sales_id' => $saleProduct->getSaleId(),
                'products_id' => $saleProduct->getProductId(),
                'quantity' => $saleProduct->getQuantity(),
                'base_value' => $saleProduct->getBaseValue()
            ]
        );

        return $this->database->lastInsertId();
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM sale_products WHERE id = :id",
            ['id' => $id]
        );
    }

    public function deleteBySaleId(int $saleId): void
    {
        $this->database->query(
            "DELETE FROM sale_products WHERE sales_id = :saleId",
            ['saleId' => $saleId]
        );
    }
}