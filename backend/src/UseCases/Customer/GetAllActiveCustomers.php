<?php

namespace SalesAppApi\UseCases\Customer;

use SalesAppApi\Domain\CustomerRepositoryInterface;

class GetAllActiveCustomers{

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
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
        $customers = $this->customerRepository->getAllActiveCustomers($data['search'], $data['page'], $data['page_count']);

        $arrayCustomers = [];

        foreach ($customers as $customer) {
            $arrayCustomers[] = $customer->toArray();
        }

        return $arrayCustomers;
    }
}
