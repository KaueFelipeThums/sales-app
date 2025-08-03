<?php

namespace SalesAppApi\UseCases\User;

use Exception;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;

class UpdateUser{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'id' => int,
     *      'name' => string,
     *      'email' => string,
     *      'password' => string,
     *      'is_active' => int,
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $user = $this->userRepository->getUserById($data['id']);

        if(empty($user)) {
            throw new Exception("Usuário não encontrado", 422);
        }

        $userEmail = $this->userRepository->getUserByEmail($data['email']);

        if(!empty($userEmail) && $userEmail->getId() != $data['id']) {
            throw new Exception("E-mail ja cadastrado", 422);
        }

        $user->setName($data['name'])
            ->setEmail($data['email'])
            ->setIsActive($data['is_active'])
            ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        if(!empty($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        }

        $this->userRepository->update($user);
        $user->setPassword(null);

        return $user->toArray();
    }
}
