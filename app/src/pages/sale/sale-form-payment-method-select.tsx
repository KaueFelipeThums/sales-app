import React, { useEffect, useRef, useTransition } from 'react';
import { Select } from '@/core/components/ui-presets/select';
import { getAllActivePaymentMethodsRequest } from '@/services/api/payment-method';
import { PaymentMethod } from '@/types/payment-method';

type SaleFormPaymentMethodSelectProps = Omit<
  React.ComponentPropsWithRef<typeof Select>,
  'onInputChange' | 'filter' | 'placeholder' | 'options'
>;

const SaleFormPaymentMethodSelect = ({ disabled, loading, ...props }: SaleFormPaymentMethodSelectProps) => {
  const debounceRef = useRef<NodeJS.Timeout | null>(null);
  const [paymentMethods, setPaymentMethods] = React.useState<PaymentMethod[]>([]);
  const [loadingPaymentMethods, startPaymentMethodsTransition] = useTransition();

  const getAllPaymentMethods = React.useCallback((search: string) => {
    startPaymentMethodsTransition(async () => {
      const response = await getAllActivePaymentMethodsRequest({ page: 1, page_count: 10, search });
      if (response.success) {
        setPaymentMethods(response.data);
      }
    });
  }, []);

  const handlePaymentMethodSearch = React.useCallback(
    (value: string) => {
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        getAllPaymentMethods(value);
      }, 400);
    },
    [getAllPaymentMethods],
  );

  useEffect(() => {
    getAllPaymentMethods('');
  }, [getAllPaymentMethods]);

  return (
    <Select
      onInputChange={handlePaymentMethodSearch}
      filter={false}
      loading={loadingPaymentMethods || loading}
      placeholder="Selecione o meio de pagamento..."
      options={paymentMethods.map((customer) => ({
        label: customer.name,
        value: customer.id.toString(),
      }))}
      disabled={loadingPaymentMethods || disabled}
      {...props}
    />
  );
};

export { SaleFormPaymentMethodSelect };
