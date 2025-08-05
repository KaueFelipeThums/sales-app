<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\Customer;
use SalesAppApi\Domain\PaymentMethod;
use SalesAppApi\Domain\Sale;
use SalesAppApi\Domain\SaleRepositoryInterface;
use SalesAppApi\Domain\User;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class SaleRepository implements SaleRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function getAllSales(
        string $search = '', 
        int $page = 1, 
        int $pageCount = 10, 
        ?int $customersId = null,
    ): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
                -- Sale
                sales.id AS sales_id,
                sales.payment_methods_id AS sales_payment_methods_id,
                sales.users_id AS sales_users_id,
                sales.customers_id AS sales_customers_id,
                sales.total_value AS sales_total_value,
                sales.created_at AS sales_created_at,
                sales.updated_at AS sales_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at,

                -- Payment Method
                payment_methods.id AS payment_methods_id,
                payment_methods.users_id AS payment_methods_users_id,
                payment_methods.name AS payment_methods_name,
                payment_methods.installments AS payment_methods_installments,
                payment_methods.is_active AS payment_methods_is_active,
                payment_methods.created_at AS payment_methods_created_at,
                payment_methods.updated_at AS payment_methods_updated_at,

                -- Customer
                customers.id AS customers_id,
                customers.users_id AS customers_users_id,
                customers.name AS customers_name,
                customers.cpf AS customers_cpf,
                customers.email AS customers_email,
                customers.zip_code AS customers_zip_code,
                customers.street AS customers_street,
                customers.number AS customers_number,
                customers.complement AS customers_complement,
                customers.neighborhood AS customers_neighborhood,
                customers.city AS customers_city,
                customers.state AS customers_state,
                customers.is_active AS customers_is_active,
                customers.created_at AS customers_created_at,
                customers.updated_at AS customers_updated_at
            FROM 
                sales 
            LEFT JOIN
                users ON sales.users_id = users.id
            LEFT JOIN
                payment_methods ON sales.payment_methods_id = payment_methods.id
            LEFT JOIN
                customers ON sales.customers_id = customers.id
            WHERE 
                1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (
                customers.name LIKE :search OR 
                customers.cpf LIKE :search OR 
                payment_methods.name LIKE :search OR
                DATE_FORMAT(sales.created_at, '%d/%m/%Y') LIKE :search OR
                
            )";
            $params['search'] = "%".$search."%";
        }

        if(!empty($customersId)) {
            $sql .= " AND sales.customers_id = :customers_id";
            $params['customers_id'] = $customersId;
        }

        $sql .= " ORDER BY sales.id DESC LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arraySales = [];

        foreach ($this->database->fetchAll($query) as $r) {
            $arraySales[] = new Sale(
                $r['sales_id'],
                $r['sales_payment_methods_id'],
                $r['sales_users_id'],
                $r['sales_customers_id'],
                $r['sales_total_value'],
                new DateTime($r['sales_created_at']),
                !empty($r['sales_updated_at']) ? new DateTime($r['sales_updated_at']) : null,
                new PaymentMethod(
                    $r['payment_methods_id'],
                    $r['payment_methods_users_id'],
                    $r['payment_methods_name'],
                    $r['payment_methods_installments'],
                    $r['payment_methods_is_active'],
                    new DateTime($r['payment_methods_created_at']),
                    !empty($r['payment_methods_updated_at']) ? new DateTime($r['payment_methods_updated_at']) : null,
                    null
                ),
                new Customer(
                    $r['customers_id'],
                    $r['customers_users_id'],
                    $r['customers_name'],
                    $r['customers_cpf'],
                    !empty($r['customers_email']) ? $r['customers_email'] : null,
                    !empty($r['customers_zip_code']) ? $r['customers_zip_code'] : null,
                    !empty($r['customers_street']) ? $r['customers_street'] : null,
                    !empty($r['customers_number']) ? $r['customers_number'] : null,
                    !empty($r['customers_complement']) ? $r['customers_complement'] : null,
                    !empty($r['customers_neighborhood']) ? $r['customers_neighborhood'] : null,
                    !empty($r['customers_city']) ? $r['customers_city'] : null,
                    !empty($r['customers_state']) ? $r['customers_state'] : null,
                    $r['customers_is_active'],
                    new DateTime($r['customers_created_at']),
                    !empty($r['customers_updated_at']) ? new DateTime($r['customers_updated_at']) : null,
                    null
                ),
                [],
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
        return $arraySales;
    }

    public function getSaleById(int $id): ?Sale
    {
        $query = $this->database->query(
            "SELECT 
                -- Sale
                sales.id AS sales_id,
                sales.payment_methods_id AS sales_payment_methods_id,
                sales.users_id AS sales_users_id,
                sales.customers_id AS sales_customers_id,
                sales.total_value AS sales_total_value,
                sales.created_at AS sales_created_at,
                sales.updated_at AS sales_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at,

                -- Payment Method
                payment_methods.id AS payment_methods_id,
                payment_methods.users_id AS payment_methods_users_id,
                payment_methods.name AS payment_methods_name,
                payment_methods.installments AS payment_methods_installments,
                payment_methods.is_active AS payment_methods_is_active,
                payment_methods.created_at AS payment_methods_created_at,
                payment_methods.updated_at AS payment_methods_updated_at,

                -- Customer
                customers.id AS customers_id,
                customers.users_id AS customers_users_id,
                customers.name AS customers_name,
                customers.cpf AS customers_cpf,
                customers.email AS customers_email,
                customers.zip_code AS customers_zip_code,
                customers.street AS customers_street,
                customers.number AS customers_number,
                customers.complement AS customers_complement,
                customers.neighborhood AS customers_neighborhood,
                customers.city AS customers_city,
                customers.state AS customers_state,
                customers.is_active AS customers_is_active,
                customers.created_at AS customers_created_at,
                customers.updated_at AS customers_updated_at
            FROM 
                sales 
            LEFT JOIN
                users ON sales.users_id = users.id
            LEFT JOIN
                payment_methods ON sales.payment_methods_id = payment_methods.id
            LEFT JOIN
                customers ON sales.customers_id = customers.id
            WHERE 
                sales.id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new Sale(
            $response['sales_id'],
            $response['sales_payment_methods_id'],
            $response['sales_users_id'],
            $response['sales_customers_id'],
            $response['sales_total_value'],
            new DateTime($response['sales_created_at']),
            !empty($response['sales_updated_at']) ? new DateTime($response['sales_updated_at']) : null,
            new PaymentMethod(
                $response['payment_methods_id'],
                $response['payment_methods_users_id'],
                $response['payment_methods_name'],
                $response['payment_methods_installments'],
                $response['payment_methods_is_active'],
                new DateTime($response['payment_methods_created_at']),
                !empty($response['payment_methods_updated_at']) ? new DateTime($response['payment_methods_updated_at']) : null,
                null
            ),
            new Customer(
                $response['customers_id'],
                $response['customers_users_id'],
                $response['customers_name'],
                $response['customers_cpf'],
                !empty($response['customers_email']) ? $response['customers_email'] : null,
                !empty($response['customers_zip_code']) ? $response['customers_zip_code'] : null,
                !empty($response['customers_street']) ? $response['customers_street'] : null,
                !empty($response['customers_number']) ? $response['customers_number'] : null,
                !empty($response['customers_complement']) ? $response['customers_complement'] : null,
                !empty($response['customers_neighborhood']) ? $response['customers_neighborhood'] : null,
                !empty($response['customers_city']) ? $response['customers_city'] : null,
                !empty($response['customers_state']) ? $response['customers_state'] : null,
                $response['customers_is_active'],
                new DateTime($response['customers_created_at']),
                !empty($response['customers_updated_at']) ? new DateTime($response['customers_updated_at']) : null,
                null
            ),
            [],
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

    public function create(Sale $sale): int
    {
        $this->database->query(
            "INSERT INTO
                sales 
                (
                    payment_methods_id,
                    users_id,
                    customers_id,
                    total_value,
                    created_at,
                    updated_at
                )
            VALUES 
                (
                    :payment_methods_id,
                    :users_id,
                    :customers_id,
                    :total_value,
                    :created_at,
                    :updated_at
                )",
            [
                'payment_methods_id' => $sale->getPaymentMethodsId(),
                'users_id' => $sale->getUsersId(),
                'customers_id' => $sale->getCustomersId(),
                'total_value' => $sale->getTotalValue(),
                'created_at' => $sale->getCreatedAt()->getDateTime(),
                'updated_at' => null
            ]
        );

        return $this->database->lastInsertId();
    }

    public function update(Sale $sale): void
    {
        $this->database->query(
            "UPDATE sales SET 
                payment_methods_id=:payment_methods_id,
                users_id=:users_id,
                customers_id=:customers_id,
                total_value=:total_value,
                updated_at=:updated_at
            WHERE 
                id = :id",
            [
                'id' => $sale->getId(),
                'payment_methods_id' => $sale->getPaymentMethodsId(),
                'users_id' => $sale->getUsersId(),
                'customers_id' => $sale->getCustomersId(),
                'total_value' => $sale->getTotalValue(),
                'updated_at' => !empty($sale->getUpdatedAt()) ? $sale->getUpdatedAt()->getDateTime() : null,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM sales WHERE id = :id",
            ['id' => $id]
        );
    }
}