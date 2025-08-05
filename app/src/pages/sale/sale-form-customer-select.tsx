import React, { useEffect, useRef, useTransition } from 'react';
import { Select } from '@/core/components/ui-presets/select';
import { parseToInt } from '@/functions/parsers';
import { getAllActiveCustomersRequest } from '@/services/api/customer';
import { Customer } from '@/types/customer';

type SaleFormCustomerSelectProps = Omit<
  React.ComponentPropsWithRef<typeof Select>,
  'onInputChange' | 'filter' | 'placeholder' | 'options'
>;

const SaleFormCustomerSelect = ({ disabled, loading, value, ...props }: SaleFormCustomerSelectProps) => {
  const debounceRef = useRef<NodeJS.Timeout | null>(null);
  const [paymentMethods, setCustomers] = React.useState<Customer[]>([]);
  const [loadingCustomers, startCustomersTransition] = useTransition();

  const getAllCustomers = React.useCallback(
    (search: string) => {
      startCustomersTransition(async () => {
        const response = await getAllActiveCustomersRequest({
          page: 1,
          page_count: 10,
          search,
          id: value ? parseToInt(value) : 0,
        });
        if (response.success) {
          setCustomers(response.data);
        }
      });
    },
    [value],
  );

  const handleCustomerSearch = React.useCallback(
    (value: string) => {
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        getAllCustomers(value);
      }, 400);
    },
    [getAllCustomers],
  );

  useEffect(() => {
    getAllCustomers('');
  }, [getAllCustomers]);

  return (
    <Select
      value={value}
      onInputChange={handleCustomerSearch}
      filter={false}
      loading={loadingCustomers || loading}
      placeholder="Selecione o cliente..."
      options={paymentMethods.map((customer) => ({
        label: customer.name,
        value: customer.id.toString(),
      }))}
      disabled={disabled}
      {...props}
    />
  );
};

export { SaleFormCustomerSelect };
