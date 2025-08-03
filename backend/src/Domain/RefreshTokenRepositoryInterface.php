<?php
namespace SalesAppApi\Domain;

interface RefreshTokenRepositoryInterface{
    public function getRefreshTokenByToken(string $token): ?RefreshToken;
    public function create(RefreshToken $refreshToken): void;
    public function update(RefreshToken $refreshToken): void;
    public function delete(int $id): void;
}
