<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\Product;
use SalesAppApi\Domain\ProductRepositoryInterface;
use SalesAppApi\Domain\User;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function getAllProducts(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                products 
            LEFT JOIN
                users ON products.users_id = users.id
            WHERE 
                1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (products.name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY products.name, products.id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayProducts = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayProducts[] = new Product(
                $r['products_id'],
                $r['products_users_id'],
                $r['products_name'],
                $r['products_quantity'],
                $r['products_price'],
                $r['products_is_active'],
                new DateTime($r['products_created_at']),
                !empty($r['products_updated_at']) ? new DateTime($r['products_updated_at']) : null,
                new User(
                    $r['users_id'],
                    $r['users_name'],
                    $r['users_email'],
                    null,
                    $r['users_is_active'],
                    new DateTime($r['users_created_at']),
                    !empty($r['users_updated_at']) ? new DateTime($r['users_updated_at']) : null
                )
              
            );
        }
        return $arrayProducts;
    }

    public function getAllActiveProducts(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                products 
            LEFT JOIN
                users ON products.users_id = users.id
            WHERE 
                products.is_active = 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (products.name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY products.name, products.id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayProducts = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayProducts[] = new Product(
                $r['products_id'],
                $r['products_users_id'],
                $r['products_name'],
                $r['products_quantity'],
                $r['products_price'],
                $r['products_is_active'],
                new DateTime($r['products_created_at']),
                !empty($r['products_updated_at']) ? new DateTime($r['products_updated_at']) : null,
                new User(
                    $r['users_id'],
                    $r['users_name'],
                    $r['users_email'],
                    null,
                    $r['users_is_active'],
                    new DateTime($r['users_created_at']),
                    !empty($r['users_updated_at']) ? new DateTime($r['users_updated_at']) : null
                )
              
            );
        }
        return $arrayProducts;
    }

    public function getProductById(int $id): ?Product
    {
        $query = $this->database->query(
            "SELECT 
                -- Product
                products.id AS products_id,
                products.users_id AS products_users_id,
                products.name AS products_name,
                products.quantity AS products_quantity,
                products.price AS products_price,
                products.is_active AS products_is_active,
                products.created_at AS products_created_at,
                products.updated_at AS products_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                products 
            LEFT JOIN
                users ON products.users_id = users.id
            WHERE 
                products.id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new Product(
            $response['products_id'],
            $response['products_users_id'],
            $response['products_name'],
            $response['products_quantity'],
            $response['products_price'],
            $response['products_is_active'],
            new DateTime($response['products_created_at']),
            !empty($r['products_updated_at']) ? new DateTime($response['products_updated_at']) : null,
            new User(
                $response['users_id'],
                $response['users_name'],
                $response['users_email'],
                null,
                $response['users_is_active'],
                new DateTime($response['users_created_at']),
                !empty($response['users_updated_at']) ? new DateTime($response['users_updated_at']) : null
            )
            
        );
    }

    public function create(Product $product): int
    {
        $this->database->query(
            "INSERT INTO
                products 
                (
                    users_id,
                    name,
                    quantity,
                    price,
                    is_active,
                    created_at,
                    updated_at
                )
            VALUES 
                (
                    :users_id,
                    :name,
                    :quantity,
                    :price,
                    :is_active,
                    :created_at,
                    :updated_at
                )",
            [
                'users_id' => $product->getUsersId(),
                'name' => $product->getName(),
                'quantity' => $product->getQuantity(),
                'price' => $product->getPrice(),
                'is_active' => $product->getIsActive(),
                'created_at' => $product->getCreatedAt()->getDateTime(),
                'updated_at' => null
            ]
        );

        return $this->database->lastInsertId();
    }

    public function update(Product $product): void
    {
        $this->database->query(
            "UPDATE products SET 
                users_id=:users_id,
                name=:name,
                quantity=:quantity,
                price=:price,
                is_active=:is_active,
                updated_at=:updated_at
            WHERE 
                id = :id",
            [
                'id' => $product->getId(),
                'users_id' => $product->getUsersId(),
                'name' => $product->getName(),
                'quantity' => $product->getQuantity(),
                'price' => $product->getPrice(),
                'is_active' => $product->getIsActive(),
                'updated_at' => $product->getUpdatedAt()->getDateTime()
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM products WHERE id = :id",
            ['id' => $id]
        );
    }
}