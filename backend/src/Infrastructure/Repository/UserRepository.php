<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\User;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function getAllUsers(string $search = '', int $page = 1, int $pageCount = 10): array
    {
        $offset = ($page - 1) * $pageCount;
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name LIKE :search)";
            $params['search'] = "%".$search."%";
        }

        $sql .= " ORDER BY name, id LIMIT ".(int)$pageCount." OFFSET ".(int)$offset;

        $query = $this->database->query($sql, $params);

        $arrayUsers = [];
        foreach ($this->database->fetchAll($query) as $r) {
            $arrayUsers[] = new User(
                $r['id'],
                $r['name'],
                $r['email'],
                $r['password'],
                $r['is_active'],
                new DateTime($r['created_at']),
                !empty($r['updated_at']) ? new DateTime($r['updated_at']) : null
            );
        }
        return $arrayUsers;
    }

    public function getActiveUserByEmail(string $email): ?User
    {
        $query = $this->database->query(
            "SELECT * FROM users WHERE email = :email AND is_active = 1",
            ['email' => $email]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new User(
            $response['id'],
            $response['name'],
            $response['email'],
            $response['password'],
            $response['is_active'],
            new DateTime($response['created_at']),
            !empty($response['updated_at']) ? new DateTime($response['updated_at']) : null
        );
    }

    public function getUserByEmail(string $email): ?User
    {
        $query = $this->database->query(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new User(
            $response['id'],
            $response['name'],
            $response['email'],
            $response['password'],
            $response['is_active'],
            new DateTime($response['created_at']),
            !empty($response['updated_at']) ? new DateTime($response['updated_at']) : null
        );
    }

    public function getUserById(int $id): ?User
    {
        $query = $this->database->query(
            "SELECT * FROM users WHERE id = :id",
            ['id' => $id]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new User(
            $response['id'],
            $response['name'],
            $response['email'],
            $response['password'],
            $response['is_active'],
            new DateTime($response['created_at']),
            !empty($response['updated_at']) ? new DateTime($response['updated_at']) : null
        );
    }

    public function create(User $user): int
    {
        $this->database->query(
            "INSERT INTO
                users 
                (name, email, password, is_active, created_at, updated_at) 
            VALUES 
                (:name, :email, :password, :is_active, :created_at, :updated_at)",
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'is_active' => $user->getIsActive(),
                'created_at' => $user->getCreatedAt()->getDateTime(),
                'updated_at' => null
            ]
        );

        return $this->database->lastInsertId();
    }

    public function update(User $user): void
    {
        $this->database->query(
            "UPDATE users SET 
                name = :name, 
                email = :email, 
                password = :password, 
                is_active = :is_active, 
                updated_at = :updated_at 
            WHERE 
                id = :id",
            [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'is_active' => $user->getIsActive(),
                'updated_at' => $user->getUpdatedAt()->getDateTime()
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM users WHERE id = :id",
            ['id' => $id]
        );
    }
}