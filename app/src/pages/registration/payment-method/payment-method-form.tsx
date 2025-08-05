import React, { useEffect, useTransition } from 'react';
import { useForm } from 'react-hook-form';
import { StyleSheet, View } from 'react-native';
import type { PaymentMethodCreateUpdate, PaymentMethodForm as PaymentMethodFormType } from './payment-method-types';
import { ContainerScrollView } from '@/components/layout/container';
import {
  Header,
  HeaderAdornment,
  HeaderButton,
  HeaderContent,
  HeaderHiddenButton,
  HeaderTitle,
} from '@/components/layout/header';
import { KeyboardAvoidingContent } from '@/core/components/ui/keyboard-avoid-content';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { FormField } from '@/core/components/ui-presets/form-field';
import { InputNumber } from '@/core/components/ui-presets/input-number';
import { InputText } from '@/core/components/ui-presets/input-text';
import { Select } from '@/core/components/ui-presets/select';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import validator from '@/functions/validators';
import { useSync } from '@/providers/sync/sync-provider';
import {
  useRegistrationNavigation,
  useRegistrationRouteParams,
} from '@/routes/private-routes/stacks/registration-stack-routes';
import { createPaymentMethodRequest, updatePaymentMethodRequest } from '@/services/api/payment-method';

const rules = {
  name: { required: 'O nome é obrigatório!' },
  installments: {
    required: 'O número de parcelas é obrigatório!',
    validate: (value: string) => validator.number(value, 0),
  },
  is_active: { required: 'O status é obrigatório!' },
};

const paymentMethodStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    contentContainerList: {
      gap: sizes.gap.xl,
      paddingHorizontal: sizes.padding.xl,
      paddingVertical: sizes.padding.md,
    },
    layout: {
      flex: 1,
    },
  });

const PaymentMethodForm = () => {
  const styles = useStyles(paymentMethodStyles);
  const [loading, startTransition] = useTransition();
  const { params } = useRegistrationRouteParams<'PaymentMethodForm'>();
  const paymentMethod = params.paymentMethod;
  const { setSync } = useSync();
  const navigation = useRegistrationNavigation();
  const form = useForm<PaymentMethodFormType>({
    defaultValues: {
      id: '',
      installments: '',
      is_active: '1',
      name: '',
    },
  });

  const createPaymentMethod = React.useCallback(
    (data: Omit<PaymentMethodCreateUpdate, 'id'>) => {
      startTransition(async () => {
        const response = await createPaymentMethodRequest(data);
        if (response.success) {
          setSync('payment-method');
          toast.success({ title: 'Registro inserido com sucesso!' });
          navigation.goBack();
          form.reset();
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [navigation, setSync, form],
  );

  const updatePaymentMethod = React.useCallback(
    (data: PaymentMethodCreateUpdate) => {
      startTransition(async () => {
        const response = await updatePaymentMethodRequest(data);
        if (response.success) {
          setSync('payment-method');
          toast.success({ title: 'Registro alterado com sucesso!' });
          navigation.goBack();
          form.reset();
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [navigation, setSync, form],
  );

  useEffect(() => {
    if (paymentMethod) {
      form.setValue('id', paymentMethod.id.toString());
      form.setValue('name', paymentMethod.name);
      form.setValue('installments', paymentMethod.installments.toString());
      form.setValue('is_active', paymentMethod.is_active.toString());
    }
  }, [paymentMethod, form]);

  const onSubmit = React.useCallback(
    (formData: PaymentMethodFormType) => {
      const newFormData: Omit<PaymentMethodCreateUpdate, 'id'> = {
        name: formData.name,
        installments: Number(formData.installments),
        is_active: Number(formData.is_active),
      };

      if (formData.id) {
        updatePaymentMethod({
          ...newFormData,
          id: Number(formData.id),
        });
      } else {
        createPaymentMethod(newFormData);
      }
    },
    [createPaymentMethod, updatePaymentMethod],
  );

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">{paymentMethod ? 'Alterar' : 'Adicionar'} Met. Pagamento</HeaderTitle>
        </HeaderContent>
        <HeaderAdornment>
          <HeaderHiddenButton />
        </HeaderAdornment>
      </Header>
      <KeyboardAvoidingContent>
        <ContainerScrollView contentContainerStyle={styles.contentContainerList}>
          <FormField
            control={form.control}
            label="Nome"
            name="name"
            rules={rules.name}
            render={({ field }) => (
              <InputText
                placeholder="Digite o nome..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Número de Parcelas"
            name="installments"
            rules={rules.installments}
            render={({ field }) => (
              <InputNumber
                decimals={0}
                placeholder="Digite o número de parcelas..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Status"
            name="is_active"
            rules={rules.is_active}
            render={({ field }) => (
              <Select
                placeholder="Selecione o status..."
                showSearch={false}
                options={[
                  { label: 'Ativo', value: '1' },
                  { label: 'Inativo', value: '0' },
                ]}
                disabled={loading}
                value={field.value}
                onValueChange={field.onChange}
              />
            )}
          />

          <Button icon="Send" iconPlacement="right" loading={loading} onPress={form.handleSubmit(onSubmit)}>
            Salvar
          </Button>
        </ContainerScrollView>
      </KeyboardAvoidingContent>
    </View>
  );
};

export default PaymentMethodForm;
