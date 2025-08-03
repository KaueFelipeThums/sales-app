<?php

namespace SalesAppApi\UseCases\PaymentMethod;

use Exception;
use SalesAppApi\Domain\PaymentMethod;
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

       $newPaymentMethod = new PaymentMethod(
            $paymentMethod->getId(),
            $paymentMethod->getUsersId(),
            $data['name'],
            $data['installments'],
            $data['is_active'],
            $paymentMethod->getCreatedAt(),
            new DateTime(date('Y-m-d H:i:s')),
            null
        );

        $this->paymentMethodRepository->update($newPaymentMethod);
        $newPaymentMethodArray = $newPaymentMethod->toArray();

        return $newPaymentMethodArray;
    }
}
