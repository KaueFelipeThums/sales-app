<?php

namespace SalesAppApi\UseCases\User;

use SalesAppApi\Domain\UserRepositoryInterface;

class DeleteUser{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $this->userRepository->delete($id);
    }
}
