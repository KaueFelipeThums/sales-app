<?php

namespace SalesAppApi\UseCases\User;

use SalesAppApi\Domain\UserRepositoryInterface;

class GetUserById{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): array
    {
        $user = $this->userRepository->getUserById($id);
        $userArray = $user->toArray();
        $userArray['password'] = null;
        return $userArray;
    }
}
