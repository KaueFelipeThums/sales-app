<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\PaymentMethod;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\User;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function getAllPaymentMethods(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
                -- PaymentMethod
                payment_methods.id AS payment_methods_id,
                payment_methods.users_id AS payment_methods_users_id,
                payment_methods.name AS payment_methods_name,
                payment_methods.installments AS payment_methods_installments,
                payment_methods.is_active AS payment_methods_is_active,
                payment_methods.created_at AS payment_methods_created_at,
                payment_methods.updated_at AS payment_methods_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                payment_methods 
            LEFT JOIN
                users ON payment_methods.users_id = users.id
            WHERE 
                1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (payment_methods.name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY payment_methods.name, payment_methods.id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayPaymentMethods = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayPaymentMethods[] = new PaymentMethod(
                $r['payment_methods_id'],
                $r['payment_methods_users_id'],
                $r['payment_methods_name'],
                $r['payment_methods_installments'],
                $r['payment_methods_is_active'],
                new DateTime($r['payment_methods_created_at']),
                !empty($r['payment_methods_updated_at']) ? new DateTime($r['payment_methods_updated_at']) : null,
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
        return $arrayPaymentMethods;
    }

    public function getAllActivePaymentMethods(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
                -- PaymentMethod
                payment_methods.id AS payment_methods_id,
                payment_methods.users_id AS payment_methods_users_id,
                payment_methods.name AS payment_methods_name,
                payment_methods.installments AS payment_methods_installments,
                payment_methods.is_active AS payment_methods_is_active,
                payment_methods.created_at AS payment_methods_created_at,
                payment_methods.updated_at AS payment_methods_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                payment_methods 
            LEFT JOIN
                users ON payment_methods.users_id = users.id
            WHERE 
                payment_methods.is_active = 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (payment_methods.name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY payment_methods.name, payment_methods.id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayPaymentMethods = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayPaymentMethods[] = new PaymentMethod(
                $r['payment_methods_id'],
                $r['payment_methods_users_id'],
                $r['payment_methods_name'],
                $r['payment_methods_installments'],
                $r['payment_methods_is_active'],
                new DateTime($r['payment_methods_created_at']),
                !empty($r['payment_methods_updated_at']) ? new DateTime($r['payment_methods_updated_at']) : null,
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
        return $arrayPaymentMethods;
    }

    public function getPaymentMethodById(int $id): ?PaymentMethod
    {
        $query = $this->database->query(
            "SELECT 
                -- PaymentMethod
                payment_methods.id AS payment_methods_id,
                payment_methods.users_id AS payment_methods_users_id,
                payment_methods.name AS payment_methods_name,
                payment_methods.installments AS payment_methods_installments,
                payment_methods.is_active AS payment_methods_is_active,
                payment_methods.created_at AS payment_methods_created_at,
                payment_methods.updated_at AS payment_methods_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                payment_methods 
            LEFT JOIN
                users ON payment_methods.users_id = users.id
            WHERE 
                payment_methods.id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new PaymentMethod(
            $response['payment_methods_id'],
            $response['payment_methods_users_id'],
            $response['payment_methods_name'],
            $response['payment_methods_installments'],
            $response['payment_methods_is_active'],
            new DateTime($response['payment_methods_created_at']),
            !empty($r['payment_methods_updated_at']) ? new DateTime($response['payment_methods_updated_at']) : null,
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

    public function create(PaymentMethod $paymentMethod): int
    {
        $this->database->query(
            "INSERT INTO
                payment_methods 
                (
                    users_id,
                    name,
                    installments,
                    is_active,
                    created_at,
                    updated_at
                )
            VALUES 
                (
                    :users_id,
                    :name,
                    :installments,
                    :is_active,
                    :created_at,
                    :updated_at
                )",
            [
                'users_id' => $paymentMethod->getUsersId(),
                'name' => $paymentMethod->getName(),
                'installments' => $paymentMethod->getInstallments(),
                'is_active' => $paymentMethod->getIsActive(),
                'created_at' => $paymentMethod->getCreatedAt()->getDateTime(),
                'updated_at' => null
            ]
        );

        return $this->database->lastInsertId();
    }

    public function update(PaymentMethod $paymentMethod): void
    {
        $this->database->query(
            "UPDATE payment_methods SET 
                users_id=:users_id,
                name=:name,
                installments=:installments,
                is_active=:is_active,
                updated_at=:updated_at
            WHERE 
                id = :id",
            [
                'id' => $paymentMethod->getId(),
                'users_id' => $paymentMethod->getUsersId(),
                'name' => $paymentMethod->getName(),
                'installments' => $paymentMethod->getInstallments(),
                'is_active' => $paymentMethod->getIsActive(),
                'updated_at' => $paymentMethod->getUpdatedAt()->getDateTime()
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM payment_methods WHERE id = :id",
            ['id' => $id]
        );
    }
}