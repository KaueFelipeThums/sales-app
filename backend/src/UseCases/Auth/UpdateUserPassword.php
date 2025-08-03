<?php

namespace SalesAppApi\UseCases\Auth;

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
     *  ]
     * @return array
     */
    public function execute(array $data): void
    {
        $user = $this->userRepository->getUserById(Auth::id());
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT))->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));
        $this->userRepository->update($user);
    }
}
