<?php

namespace SalesAppApi\UseCases\Auth;

use SalesAppApi\Domain\UserRepositoryInterface;
use SalesAppApi\Shared\Auth\Auth;

class GetAuthUser{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

    /**
     * Execute
     *
     * @return array
     */
    public function execute(): array
    {
        $user = $this->userRepository->getUserById(Auth::id());
        $user->setPassword(null);
        return $user->toArray();
    }
}
