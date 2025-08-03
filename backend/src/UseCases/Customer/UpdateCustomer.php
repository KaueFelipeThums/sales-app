<?php

namespace SalesAppApi\UseCases\Customer;

use Exception;
use SalesAppApi\Domain\Customer;
use SalesAppApi\Domain\CustomerRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;

class UpdateCustomer{

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'id' => int,
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
        $customer = $this->customerRepository->getCustomerById($data['id']);

        if(empty($customer)) {
            throw new Exception("Cliente não encontrado", 422);
        }

        $customerCpf = $this->customerRepository->getCustomerByCpf($data['email']);

        if(!empty($customerCpf) && $customerCpf->getId() != $data['id']) {
            throw new Exception("CPF ja cadastrado", 422);
        }

       $newCustomer = new Customer(
            $customer->getId(),
            $customer->getUsersId(),
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
            $customer->getCreatedAt(),
            new DateTime(date('Y-m-d H:i:s')),
            null
        );

        $this->customerRepository->update($newCustomer);
        $newCustomerArray = $newCustomer->toArray();

        return $newCustomerArray;
    }
}
