<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\Customer;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\User;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function getAllCustomers(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
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
                customers.updated_at AS customers_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.password AS users_password,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                customers 
            LEFT JOIN
                users ON customers.users_id = users.id
            WHERE 
                1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (customers.name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY customers.name, customers.id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayCustomers = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayCustomers[] = new Customer(
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
                new User(
                    $r['users_id'],
                    $r['users_name'],
                    $r['users_email'],
                    $r['users_password'],
                    $r['users_is_active'],
                    new DateTime($r['users_created_at']),
                    !empty($r['users_updated_at']) ? new DateTime($r['users_updated_at']) : null
                )
              
            );
        }
        return $arrayCustomers;
    }

    public function getAllActiveCustomers(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT 
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
                customers.updated_at AS customers_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.password AS users_password,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                customers 
            LEFT JOIN
                users ON customers.users_id = users.id
            WHERE 
                customers.is_active = 1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (customers.name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY customers.name, customers.id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayCustomers = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayCustomers[] = new Customer(
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
                new User(
                    $r['users_id'],
                    $r['users_name'],
                    $r['users_email'],
                    $r['users_password'],
                    $r['users_is_active'],
                    new DateTime($r['users_created_at']),
                    !empty($r['users_updated_at']) ? new DateTime($r['users_updated_at']) : null
                )
              
            );
        }
        return $arrayCustomers;
    }

    public function getCustomerById(int $id): ?Customer
    {
        $query = $this->database->query(
            "SELECT 
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
                customers.updated_at AS customers_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.password AS users_password,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                customers 
            LEFT JOIN
                users ON customers.users_id = users.id
            WHERE 
                customers.id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new Customer(
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
            new User(
                $response['users_id'],
                $response['users_name'],
                $response['users_email'],
                $response['users_password'],
                $response['users_is_active'],
                new DateTime($response['users_created_at']),
                !empty($response['users_updated_at']) ? new DateTime($response['users_updated_at']) : null
            )
            
        );
    }

    public function getCustomerByCpf(int $cpf): ?Customer
    {
        $query = $this->database->query(
            "SELECT 
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
                customers.updated_at AS customers_updated_at,

                -- User
                users.id AS users_id,
                users.name AS users_name,
                users.email AS users_email,
                users.password AS users_password,
                users.is_active AS users_is_active,
                users.created_at AS users_created_at,
                users.updated_at AS users_updated_at
            FROM 
                customers 
            LEFT JOIN
                users ON customers.users_id = users.id
            WHERE 
                customers.cpf = :cpf",
            ['cpf' => $cpf]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new Customer(
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
            new User(
                $response['users_id'],
                $response['users_name'],
                $response['users_email'],
                $response['users_password'],
                $response['users_is_active'],
                new DateTime($response['users_created_at']),
                !empty($response['users_updated_at']) ? new DateTime($response['users_updated_at']) : null
            )
            
        );
    }

    public function create(Customer $customer): int
    {
        $this->database->query(
            "INSERT INTO
                customers 
                (
                    users_id,
                    name,
                    cpf,
                    email,
                    zip_code,
                    street,
                    number,
                    complement,
                    neighborhood,
                    city,
                    state,
                    is_active,
                    created_at,
                    updated_at
                )
            VALUES 
                (
                    :users_id,
                    :name,
                    :cpf,
                    :email,
                    :zip_code,
                    :street,
                    :number,
                    :complement,
                    :neighborhood,
                    :city,
                    :state,
                    :is_active,
                    :created_at,
                    :updated_at
                )",
            [
                'users_id' => $customer->getUsersId(),
                'name' => $customer->getName(),
                'cpf' => $customer->getCpf(),
                'email' => $customer->getEmail(),
                'zip_code' => $customer->getZipCode(),
                'street' => $customer->getStreet(),
                'number' => $customer->getNumber(),
                'complement' => $customer->getComplement(),
                'neighborhood' => $customer->getNeighborhood(),
                'city' => $customer->getCity(),
                'state' => $customer->getState(),
                'is_active' => $customer->getIsActive(),
                'created_at' => $customer->getCreatedAt()->getDateTime(),
                'updated_at' => $customer->getUpdatedAt()->getDateTime()
            ]
        );

        return $this->database->lastInsertId();
    }

    public function update(Customer $customer): void
    {
        $this->database->query(
            "UPDATE customers SET 
                users_id=:users_id,
                name=:name,
                cpf=:cpf,
                email=:email,
                zip_code=:zip_code,
                street=:street,
                number=:number,
                complement=:complement,
                neighborhood=:neighborhood,
                city=:city,
                state=:state,
                is_active=:is_active,
                updated_at=:updated_at
            WHERE 
                id = :id",
            [
                'id' => $customer->getId(),
                'users_id' => $customer->getUsersId(),
                'name' => $customer->getName(),
                'cpf' => $customer->getCpf(),
                'email' => $customer->getEmail(),
                'zip_code' => $customer->getZipCode(),
                'street' => $customer->getStreet(),
                'number' => $customer->getNumber(),
                'complement' => $customer->getComplement(),
                'neighborhood' => $customer->getNeighborhood(),
                'city' => $customer->getCity(),
                'state' => $customer->getState(),
                'is_active' => $customer->getIsActive(),
                'updated_at' => $customer->getUpdatedAt()->getDateTime()
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM customers WHERE id = :id",
            ['id' => $id]
        );
    }
}