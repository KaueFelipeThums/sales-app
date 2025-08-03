<?php
namespace SalesAppApi\Domain;

interface UserRepositoryInterface{
    public function getAllUsers(string $search, int $page, int $pageCount): array;
    public function getActiveUserByEmail(string $email): ?User;
    public function getUserById(int $id): ?User;

    public function update(User $user): void;
    public function create(User $user): void;
    public function delete(int $id): void;
}
