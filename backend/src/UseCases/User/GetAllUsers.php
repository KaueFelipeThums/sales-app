<?php

namespace SalesAppApi\UseCases\User;

use SalesAppApi\Domain\UserRepositoryInterface;

class GetAllUsers{

    public function __construct(
        private UserRepositoryInterface $userRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'search' => string,
     *      'page' => int,
     *      'page_count' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $users = $this->userRepository->getAllUsers($data['search'], $data['page'], $data['page_count']);

        $arrayUsers = [];

        foreach ($users as $user) {
            $arrayUsers[] = $user->toArray();
        }

        return $arrayUsers;
    }
}
