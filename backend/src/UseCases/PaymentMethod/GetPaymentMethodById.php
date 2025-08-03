<?php

namespace SalesAppApi\UseCases\PaymentMethod;

use SalesAppApi\Domain\PaymentMethodRepositoryInterface;

class GetPaymentMethodById{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): array
    {
        $paymentMethod = $this->paymentMethodRepository->getPaymentMethodById($id);
        if(empty($paymentMethod)){
            return [];
        }
        
        return $paymentMethod->toArray();
    }
}
