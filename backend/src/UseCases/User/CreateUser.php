<?php

namespace SalesAppApi\UseCases\User;

use Exception;
use SalesAppApi\Domain\User;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;

class CreateUser{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'name' => string,
     *      'email' => string,
     *      'password' => string,
     *      'is_active' => int,
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $userEmail = $this->userRepository->getUserByEmail($data['email']);

        if(!empty($userEmail)) {
            throw new Exception("E-mail ja cadastrado", 422);
        }

        $newUser = new User(
            null,
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['is_active'],
            new DateTime(date('Y-m-d H:i:s')),
            null
        );

        $id = $this->userRepository->create($newUser);
        $user = $this->userRepository->getUserById($id)->toArray();
        $user['password'] = null;

        return $user;
    }
}
