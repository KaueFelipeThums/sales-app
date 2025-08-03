<?php

namespace SalesAppApi\UseCases\PaymentMethod;

use Exception;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;

class UpdatePaymentMethod{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ){}

 
    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'id' => int,
     *      'name' => string,
     *      'installments' => int,
     *      'is_active' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $paymentMethod = $this->paymentMethodRepository->getPaymentMethodById($data['id']);

        if(empty($paymentMethod)) {
            throw new Exception("Produto não encontrado", 422);
        }

        $paymentMethod->setName($data['name'])
            ->setInstallments($data['installments'])
            ->setIsActive($data['is_active'])
            ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s')));

        $this->paymentMethodRepository->update($paymentMethod);

        return $paymentMethod->toArray();
    }
}
