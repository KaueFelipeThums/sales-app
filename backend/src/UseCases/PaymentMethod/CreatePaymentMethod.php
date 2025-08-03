<?php

namespace SalesAppApi\UseCases\PaymentMethod;

use SalesAppApi\Domain\PaymentMethod;
use SalesAppApi\Domain\PaymentMethodRepositoryInterface;
use SalesAppApi\Domain\ValueObjects\DateTime;
use SalesAppApi\Shared\Auth\Auth;

class CreatePaymentMethod{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ){}

    /**
     * Execute
     *
     * @param array $data
     *  [
     *      'name' => string,
     *      'installments' => int,
     *      'is_active' => int
     *  ]
     * @return array
     */
    public function execute(array $data): array
    {
        $newPaymentMethod = new PaymentMethod(
            null,
            Auth::id(),
            $data['name'],
            $data['installments'],
            $data['is_active'],
            new DateTime(date('Y-m-d H:i:s')),
            null,
            null
        );

        $id = $this->paymentMethodRepository->create($newPaymentMethod);
        $paymentMethod = $this->paymentMethodRepository->getPaymentMethodById($id)->toArray();

        return $paymentMethod;
    }
}
