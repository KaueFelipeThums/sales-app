<?php

namespace SalesAppApi\UseCases\Customer;

use Exception;
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

        $customer->setName($data['name'])
            ->setCpf($data['cpf'])
            ->setEmail($data['email'])
            ->setZipCode($data['zip_code'])
            ->setStreet($data['street'])
            ->setNumber($data['number'])
            ->setComplement($data['complement'])
            ->setNeighborhood($data['neighborhood'])
            ->setCity($data['city'])
            ->setState($data['state'])
            ->setIsActive($data['is_active'])
            ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        $this->customerRepository->update($customer);

        return $customer->toArray();
    }
}
