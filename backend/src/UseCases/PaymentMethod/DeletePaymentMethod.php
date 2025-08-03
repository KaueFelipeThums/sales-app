<?php

namespace SalesAppApi\UseCases\PaymentMethod;

use SalesAppApi\Domain\PaymentMethodRepositoryInterface;

class DeletePaymentMethod{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ){}

 
    /**
     * Execute
     *
     * @param int $id
     * @return array
     */
    public function execute(int $id): void
    {
        $this->paymentMethodRepository->delete($id);
    }
}
