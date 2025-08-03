<?php

namespace SalesAppApi\UseCases\Customer;

use SalesAppApi\Domain\CustomerRepositoryInterface;

class GetCustomerById{

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): array
    {
        $customer = $this->customerRepository->getCustomerById($id);
        return $customer->toArray();
    }
}
