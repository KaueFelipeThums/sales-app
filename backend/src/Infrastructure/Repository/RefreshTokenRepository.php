<?php
namespace SalesAppApi\Infrastructure\Repository;

use SalesAppApi\Domain\RefreshToken;
use SalesAppApi\Domain\RefreshTokenRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Infrastructure\Database\Database;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(private Database $database)
    { 
    }

    public function getRefreshTokenByToken(string $token): ?RefreshToken
    {
        $query = $this->database->query(
            "SELECT * FROM refresh_tokens WHERE token = :token",
            ['token' => $token]
        );

        $response = $this->database->fetch($query);
        if(empty($response)) {
            return null;
        }

        return new RefreshToken(
            $response['id'],
            $response['users_id'],
            $response['token'],
            new DateTime($response['expires_at']),
            new DateTime($response['created_at'])
        );
    }

    public function create(RefreshToken $refreshToken): void
    {
        $this->database->query(
            "INSERT INTO
                refresh_tokens
                (users_id, token, expires_at, created_at) 
            VALUES 
                (:users_id, :token, :expires_at, :created_at)",
            [
                'users_id' => $refreshToken->getUsersId(),
                'token' => $refreshToken->getToken(),
                'expires_at' => $refreshToken->getExpiresAt()->getDateTime(),
                'created_at' => $refreshToken->getCreatedAt()->getDateTime()
            ]
        );
    }

    public function update(RefreshToken $refreshToken): void
    {
        $this->database->query(
            "UPDATE refresh_tokens SET
                users_id = :users_id,
                token = :token,
                expires_at = :expires_at,
                created_at = :created_at
            WHERE 
                id = :id",
            [
                'users_id' => $refreshToken->getUsersId(),
                'token' => $refreshToken->getToken(),
                'expires_at' => $refreshToken->getExpiresAt()->getDateTime(),
                'created_at' => $refreshToken->getCreatedAt()->getDateTime(),
                'id' => $refreshToken->getId()
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            "DELETE FROM refresh_tokens WHERE id = :id",
            ['id' => $id]
        );
    }
}