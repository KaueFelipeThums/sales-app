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

    public function getAllSaleProductsBySalesId(int $salesId): array
    {
        $query = $this->database->query(
            "SELECT 
                -- Sale Product
                sales_products.id AS sales_products_id,
                sales_products.sales_id AS sales_products_sales_id,
                sales_products.products_id AS sales_products_products_id,
                sales_products.quantity AS sales_products_quantity,
                sales_products.base_value AS sales_products_base_value,

                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at
            FROM 
                sales_products
            LEFT JOIN
                products ON sales_products.products_id = products.id 
            WHERE 
                sales_products.sales_id = :sales_id",
            ["sales_id" => $salesId]
        );

        $arrayProducts = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayProducts[] = new SaleProduct(
                $r['sales_products_id'],
                $r['sales_products_sales_id'],
                $r['sales_products_products_id'],
                $r['sales_products_quantity'],
                $r['sales_products_base_value'],
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
                sales_products.id AS sales_products_id,
                sales_products.sales_id AS sales_products_sales_id,
                sales_products.products_id AS sales_products_products_id,
                sales_products.quantity AS sales_products_quantity,
                sales_products.base_value AS sales_products_base_value,

                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at
            FROM 
                sales_products
            LEFT JOIN
                products ON sales_products.products_id = products.id 
            WHERE 
                sales_products.id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new SaleProduct(
            $response['sales_products_id'],
            $response['sales_products_sales_id'],
            $response['sales_products_products_id'],
            $response['sales_products_quantity'],
            $response['sales_products_base_value'],
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
                sales_products 
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
                'sales_id' => $saleProduct->getSalesId(),
                'products_id' => $saleProduct->getProductsId(),
                'quantity' => $saleProduct->getQuantity(),
                'base_value' => $saleProduct->getBaseValue()
            ]
        );

        return $this->database->lastInsertId();
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM sales_products WHERE id = :id",
            ['id' => $id]
        );
    }

    public function deleteBySalesId(int $salesId): void
    {
        $this->database->query(
            "DELETE FROM sales_products WHERE sales_id = :salesId",
            ['salesId' => $salesId]
        );
    }
}