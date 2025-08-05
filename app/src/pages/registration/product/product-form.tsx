import React, { useEffect, useTransition } from 'react';
import { useForm } from 'react-hook-form';
import { StyleSheet, View } from 'react-native';
import type { ProductCreateUpdate, ProductForm as ProductFormType } from './product-types';
import { ContainerScrollView } from '@/components/layout/container';
import {
  Header,
  HeaderAdornment,
  HeaderButton,
  HeaderContent,
  HeaderHiddenButton,
  HeaderTitle,
} from '@/components/layout/header';
import { InputBaseAdornment } from '@/core/components/ui/input';
import { KeyboardAvoidingContent } from '@/core/components/ui/keyboard-avoid-content';
import { Text } from '@/core/components/ui/text';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { FormField } from '@/core/components/ui-presets/form-field';
import { InputNumber } from '@/core/components/ui-presets/input-number';
import { InputText } from '@/core/components/ui-presets/input-text';
import { Select } from '@/core/components/ui-presets/select';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { replaceToFloatValue, replaceToStringValue } from '@/functions/parsers';
import validator from '@/functions/validators';
import { useSync } from '@/providers/sync/sync-provider';

import {
  useRegistrationNavigation,
  useRegistrationRouteParams,
} from '@/routes/private-routes/stacks/registration-stack-routes';
import { createProductRequest, updateProductRequest } from '@/services/api/product';

const rules = {
  name: { required: 'O nome é obrigatório!' },
  quantity: { required: 'A quantidade é obrigatória!', validate: (value: string) => validator.number(value, 0) },
  price: { required: 'O preco é obrigatório!', validate: (value: string) => validator.number(value, 0) },
  is_active: { required: 'O status é obrigatório!' },
};

const productStyles = ({ sizes }: ThemeValue) =>
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

const ProductForm = () => {
  const styles = useStyles(productStyles);
  const [loading, startTransition] = useTransition();
  const { params } = useRegistrationRouteParams<'ProductForm'>();
  const product = params.product;
  const { setSync } = useSync();
  const navigation = useRegistrationNavigation();
  const form = useForm<ProductFormType>({
    defaultValues: {
      id: '',
      price: '',
      is_active: '1',
      name: '',
      quantity: '',
    },
  });

  const createProduct = React.useCallback(
    (data: Omit<ProductCreateUpdate, 'id'>) => {
      startTransition(async () => {
        const response = await createProductRequest(data);
        if (response.success) {
          setSync('product');
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

  const updateProduct = React.useCallback(
    (data: ProductCreateUpdate) => {
      startTransition(async () => {
        const response = await updateProductRequest(data);
        if (response.success) {
          setSync('product');
          setSync('sale');
          setSync('saleProduct');
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
    if (product) {
      form.setValue('id', product.id.toString());
      form.setValue('name', product.name);
      form.setValue('quantity', product.quantity.toString());
      form.setValue('price', replaceToStringValue(product.price));
      form.setValue('is_active', product.is_active.toString());
    }
  }, [product, form]);

  const onSubmit = React.useCallback(
    (formData: ProductFormType) => {
      const newFormData: Omit<ProductCreateUpdate, 'id'> = {
        name: formData.name,
        quantity: Number(formData.quantity),
        price: replaceToFloatValue(formData.price),
        is_active: Number(formData.is_active),
      };

      if (formData.id) {
        updateProduct({
          ...newFormData,
          id: Number(formData.id),
        });
      } else {
        createProduct(newFormData);
      }
    },
    [createProduct, updateProduct],
  );

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">{product ? 'Alterar' : 'Adicionar'} Produto</HeaderTitle>
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
            label="Quantidade"
            name="quantity"
            rules={rules.quantity}
            render={({ field }) => (
              <InputNumber
                decimals={0}
                placeholder="Digite a quantidade..."
                disabled={loading}
                value={field.value}
                onChangeText={field.onChange}
              />
            )}
          />

          <FormField
            control={form.control}
            label="Preço"
            name="price"
            rules={rules.price}
            render={({ field }) => (
              <InputNumber
                decimals={2}
                leftAdornment={
                  <InputBaseAdornment>
                    <Text>R$</Text>
                  </InputBaseAdornment>
                }
                placeholder="Digite o preço..."
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

export default ProductForm;
