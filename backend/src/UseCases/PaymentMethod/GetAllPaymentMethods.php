<?php

namespace SalesAppApi\UseCases\PaymentMethod;

use SalesAppApi\Domain\PaymentMethodRepositoryInterface;

class GetAllPaymentMethods{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
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
        $paymentMethods = $this->paymentMethodRepository->getAllPaymentMethods($data['search'], $data['page'], $data['page_count']);

        $arrayPaymentMethods = [];

        foreach ($paymentMethods as $paymentMethod) {
            $arrayPaymentMethods[] = $paymentMethod->toArray();
        }

        return $arrayPaymentMethods;
    }
}
