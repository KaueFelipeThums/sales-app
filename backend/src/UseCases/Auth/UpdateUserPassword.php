<?php

namespace SalesAppApi\UseCases\Auth;

use Exception;
use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class UpdateUserPassword{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'password' => string
     *      'new_password' => string
     *  ]
     * @return array
     */
    public function execute(array $data): void
    {
        $user = $this->userRepository->getUserById(Auth::id());

        if(empty($user)) {
            throw new Exception("Usuário nao encontrado", 422);
        }

        if(!password_verify($data['password'], $user->getPassword())) {
            throw new Exception("Senha inválida", 422);
        }

        $user->setPassword(password_hash($data['new_password'], PASSWORD_DEFAULT))->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));
        $this->userRepository->update($user);
    }
}
