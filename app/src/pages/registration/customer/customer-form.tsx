import React, { useEffect, useTransition } from 'react';
import { useForm } from 'react-hook-form';
import { StyleSheet, View } from 'react-native';
import type { CustomerCreateUpdate, CustomerForm as CustomerFormType } from './customer-types';
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
import { InputMask } from '@/core/components/ui-presets/input-mask';
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
import { getAddressRequest } from '@/services/api/address';
import { createCustomerRequest, updateCustomerRequest } from '@/services/api/customer';

const stateOptions = [
  { label: 'Acre', value: 'AC' },
  { label: 'Alagoas', value: 'AL' },
  { label: 'Amapá', value: 'AP' },
  { label: 'Amazonas', value: 'AM' },
  { label: 'Bahia', value: 'BA' },
  { label: 'Ceará', value: 'CE' },
  { label: 'Distrito Federal', value: 'DF' },
  { label: 'Espírito Santo', value: 'ES' },
  { label: 'Goiás', value: 'GO' },
  { label: 'Maranhão', value: 'MA' },
  { label: 'Mato Grosso', value: 'MT' },
  { label: 'Mato Grosso do Sul', value: 'MS' },
  { label: 'Minas Gerais', value: 'MG' },
  { label: 'Pará', value: 'PA' },
  { label: 'Paraíba', value: 'PB' },
  { label: 'Paraná', value: 'PR' },
  { label: 'Pernambuco', value: 'PE' },
  { label: 'Piauí', value: 'PI' },
  { label: 'Rio de Janeiro', value: 'RJ' },
  { label: 'Rio Grande do Norte', value: 'RN' },
  { label: 'Rio Grande do Sul', value: 'RS' },
  { label: 'Rondônia', value: 'RO' },
  { label: 'Roraima', value: 'RR' },
  { label: 'Santa Catarina', value: 'SC' },
  { label: 'São Paulo', value: 'SP' },
  { label: 'Sergipe', value: 'SE' },
  { label: 'Tocantins', value: 'TO' },
];

const rules = {
  name: { required: 'O nome é obrigatório!' },
  cpf: { required: 'O CPF é obrigatório!', validate: (value: string) => validator.cpf(value) },
  is_active: { required: 'O status é obrigatório!' },
};

const customerStyles = ({ sizes }: ThemeValue) =>
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

const CustomerForm = () => {
  const styles = useStyles(customerStyles);
  const [loading, startTransition] = useTransition();
  const [loadingAddress, startAddressTransition] = useTransition();
  const { params } = useRegistrationRouteParams<'CustomerForm'>();
  const customer = params.customer;
  const { setSync } = useSync();
  const navigation = useRegistrationNavigation();
  const form = useForm<CustomerFormType>({
    defaultValues: {
      id: '',
      name: '',
      city: '',
      cpf: '',
      email: '',
      neighborhood: '',
      number: '',
      state: '',
      street: '',
      zip_code: '',
      complement: '',
      is_active: '1',
    },
  });

  const createCustomer = React.useCallback(
    (data: Omit<CustomerCreateUpdate, 'id'>) => {
      startTransition(async () => {
        const response = await createCustomerRequest(data);
        if (response.success) {
          setSync('customer');
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

  const updateCustomer = React.useCallback(
    (data: CustomerCreateUpdate) => {
      startTransition(async () => {
        const response = await updateCustomerRequest(data);
        if (response.success) {
          setSync('customer');
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

  const getAddressByCep = React.useCallback(
    async (cep: string) => {
      startAddressTransition(async () => {
        const response = await getAddressRequest(cep.replace(/\D/g, ''));
        if (response.success) {
          if (!response.data) return;
          form.setValue('street', response.data.logradouro ?? '');
          form.setValue('neighborhood', response.data.bairro ?? '');
          form.setValue('city', response.data.localidade ?? '');
          form.setValue('state', response.data.uf ?? '');
        }
      });
    },
    [form],
  );

  useEffect(() => {
    if (customer) {
      form.setValue('id', customer.id.toString());
      form.setValue('name', customer.name);
      form.setValue('city', customer.city ? customer.city : '');
      form.setValue('cpf', customer.cpf);
      form.setValue('email', customer.email ? customer.email : '');
      form.setValue('neighborhood', customer.neighborhood ? customer.neighborhood : '');
      form.setValue('number', customer.number ? customer.number : '');
      form.setValue('state', customer.state ? customer.state : '');
      form.setValue('street', customer.street ? customer.street : '');
      form.setValue('zip_code', customer.zip_code ? customer.zip_code : '');
      form.setValue('complement', customer.complement ? customer.complement : '');
      form.setValue('is_active', customer.is_active.toString());
    }
  }, [customer, form]);

  const onSubmit = React.useCallback(
    (formData: CustomerFormType) => {
      const newFormData: Omit<CustomerCreateUpdate, 'id'> = {
        name: formData.name,
        city: formData.city,
        cpf: formData.cpf.replace(/\D/g, ''),
        email: formData.email,
        neighborhood: formData.neighborhood,
        number: formData.number,
        state: formData.state,
        street: formData.street,
        zip_code: formData.zip_code,
        complement: formData.complement,
        is_active: Number(formData.is_active),
      };

      if (formData.id) {
        updateCustomer({
          ...newFormData,
          id: Number(formData.id),
        });
      } else {
        createCustomer(newFormData);
      }
    },
    [createCustomer, updateCustomer],
  );

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">{customer ? 'Alterar' : 'Adicionar'} Cliente</HeaderTitle>
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
                onChangeText={field.onChange}
                value={field.value}
              />
            )}
          />

          <FormField
            control={form.control}
            label="CPF"
            name="cpf"
            rules={rules.cpf}
            render={({ field }) => (
              <InputMask
                mask="cpf"
                placeholder="Digite o CPF..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="E-mail"
            name="email"
            render={({ field }) => (
              <InputText
                placeholder="Digite o e-mail..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="CEP"
            name="zip_code"
            render={({ field }) => (
              <InputMask
                mask="cep"
                placeholder="Digite o CEP..."
                disabled={loading || loadingAddress}
                value={field.value}
                onChangeText={(value) => {
                  if (value.length === 9) {
                    getAddressByCep(value);
                  }
                  field.onChange(value);
                }}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Cidade"
            name="city"
            render={({ field }) => (
              <InputText
                placeholder="Digite a cidade..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Estado"
            name="state"
            render={({ field }) => (
              <Select
                options={stateOptions}
                placeholder="Selecione o estado..."
                disabled={loading}
                value={field.value}
                onValueChange={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Bairro"
            name="neighborhood"
            render={({ field }) => (
              <InputText
                placeholder="Digite o bairro..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Rua"
            name="street"
            render={({ field }) => (
              <InputText
                placeholder="Digite a rua..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Número"
            name="number"
            render={({ field }) => (
              <InputNumber
                decimals={0}
                placeholder="Digite o número..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Complemento"
            name="complement"
            render={({ field }) => (
              <InputText
                placeholder="Digite o complemento..."
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

export default CustomerForm;
