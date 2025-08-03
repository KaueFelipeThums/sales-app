<?php

namespace SalesAppApi\UseCases\Customer;

use Exception;
use SalesAppApi\Domain\Customer;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class CreateCustomer{

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'name' => string,
     *      'cpf' => string,
     *      'email' => string,
     *      'zip_code' => string,
     *      'street' => string,
     *      'number' => string,
     *      'complement' => string,
     *      'neighborhood' => string,
     *      'city' => string,
     *      'state' => string,
     *      'is_active' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $customerCpf = $this->customerRepository->getCustomerByCpf($data['cpf']);

        if(!empty($customerCpf)) {
            throw new Exception("CPF ja cadastrado", 422);
        }

        $newCustomer = new Customer(
            null,
            Auth::id(),
            $data['name'],
            $data['cpf'],
            $data['email'],
            $data['zip_code'],
            $data['street'],
            $data['number'],
            $data['complement'],
            $data['neighborhood'],
            $data['city'],
            $data['state'],
            $data['is_active'],
            new DateTime(date('Y-m-d H:i:s')),
            null,
            null
        );

        $id = $this->customerRepository->create($newCustomer);
        $customer = $this->customerRepository->getCustomerById($id)->toArray();

        return $customer;
    }
}
