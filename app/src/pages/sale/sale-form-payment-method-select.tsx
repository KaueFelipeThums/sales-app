import React, { useEffect, useRef, useTransition } from 'react';
import { StyleSheet, View } from 'react-native';
import { Text } from '@/core/components/ui/text';
import { Select } from '@/core/components/ui-presets/select';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { parseToInt } from '@/functions/parsers';
import { getAllActivePaymentMethodsRequest } from '@/services/api/payment-method';
import { PaymentMethod } from '@/types/payment-method';

const saleFormPaymentMethodSelectStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    content: {
      gap: sizes.gap.md,
    },
    label: {
      color: colors.mutedForeground,
    },
  });

type SaleFormPaymentMethodSelectProps = Omit<
  React.ComponentPropsWithRef<typeof Select>,
  'onInputChange' | 'filter' | 'placeholder' | 'options'
>;

const SaleFormPaymentMethodSelect = ({ disabled, value, loading, ...props }: SaleFormPaymentMethodSelectProps) => {
  const debounceRef = useRef<NodeJS.Timeout | null>(null);
  const styles = useStyles(saleFormPaymentMethodSelectStyles);
  const [paymentMethods, setPaymentMethods] = React.useState<PaymentMethod[]>([]);
  const [loadingPaymentMethods, startPaymentMethodsTransition] = useTransition();

  const getAllPaymentMethods = React.useCallback(
    (search: string) => {
      startPaymentMethodsTransition(async () => {
        const response = await getAllActivePaymentMethodsRequest({
          page: 1,
          page_count: 10,
          search,
          id: value ? parseToInt(value) : 0,
        });
        if (response.success) {
          setPaymentMethods(response.data);
        }
      });
    },
    [value],
  );

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
    <View style={styles.content}>
      <Select
        value={value}
        onInputChange={handlePaymentMethodSearch}
        filter={false}
        loading={loadingPaymentMethods || loading}
        placeholder="Selecione o meio de pagamento..."
        options={paymentMethods.map((customer) => ({
          label: customer.name,
          value: customer.id.toString(),
        }))}
        disabled={disabled}
        {...props}
      />
      {value && (
        <Text style={styles.label} size="sm">
          {paymentMethods.find((paymentMethod) => paymentMethod.id === parseToInt(value))?.installments} parcelas
        </Text>
      )}
    </View>
  );
};

export { SaleFormPaymentMethodSelect };
