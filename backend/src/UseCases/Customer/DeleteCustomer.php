<?php

namespace SalesAppApi\UseCases\Customer;

use SalesAppApi\Domain\CustomerRepositoryInterface;

class DeleteCustomer{

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $this->customerRepository->delete($id);
    }
}
