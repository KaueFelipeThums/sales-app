<?php

namespace SalesAppApi\UseCases\Auth;

<<<<<<< HEAD
=======
use Exception;
>>>>>>> 410b339 (feat: ajustes locais após recriação do repositório)
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
<<<<<<< HEAD
=======
     *      'new_password' => string
>>>>>>> 410b339 (feat: ajustes locais após recriação do repositório)
     *  ]
     * @return array
     */
    public function execute(array $data): void
    {
        $user = $this->userRepository->getUserById(Auth::id());
<<<<<<< HEAD
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT))->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));
=======

        if(empty($user)) {
            throw new Exception("Usuário nao encontrado", 422);
        }

        if(!password_verify($data['password'], $user->getPassword())) {
            throw new Exception("Senha inválida", 422);
        }

        $user->setPassword(password_hash($data['new_password'], PASSWORD_DEFAULT))->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));
>>>>>>> 410b339 (feat: ajustes locais após recriação do repositório)
        $this->userRepository->update($user);
    }
}
