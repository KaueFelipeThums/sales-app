import React, { useEffect, useRef, useTransition } from 'react';
import { useFieldArray, useForm } from 'react-hook-form';
import { FlatList, StyleSheet, View } from 'react-native';
import { SaleFormCustomerSelect } from './sale-form-customer-select';
import { SaleFormPaymentMethodSelect } from './sale-form-payment-method-select';
import { SaleFormProductSelect } from './sale-form-product-select';
import type { SaleCreateUpdate, SaleForm as SaleFormType } from './sale-types';
import { ContainerScrollView } from '@/components/layout/container';
import { Empty } from '@/components/layout/empty';
import {
  Header,
  HeaderAdornment,
  HeaderButton,
  HeaderContent,
  HeaderHiddenButton,
  HeaderTitle,
} from '@/components/layout/header';
import { Icon } from '@/core/components/ui/icon';
import { Item, ItemAdornment, ItemContent, ItemDescription, ItemTitle } from '@/core/components/ui/item';
import { KeyboardAvoidingContent } from '@/core/components/ui/keyboard-avoid-content';
import { Separator } from '@/core/components/ui/separator';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { FormField } from '@/core/components/ui-presets/form-field';
import { InputNumber } from '@/core/components/ui-presets/input-number';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import formaters from '@/functions/formaters';
import { parseToInt } from '@/functions/parsers';
import validator from '@/functions/validators';
import { useSync } from '@/providers/sync/sync-provider';
import { useSaleNavigation, useSaleRouteParams } from '@/routes/private-routes/stacks/sale-stack-routes';
import { createSaleRequest, updateSaleRequest } from '@/services/api/sale';
import { Product } from '@/types/product';

const rules = {
  customers_id: { required: 'O cliente é obrigatório!' },
  payment_methods_id: { required: 'O meio de pagamento é obrigatório!' },
  products: { required: 'O produto é obrigatório!' },
};

const saleStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    button: {
      height: sizes.dimension.lg,
      width: sizes.dimension.lg,
    },
    content: {
      gap: sizes.gap.xl,
      paddingHorizontal: sizes.padding.xl,
    },
    contentContainerList: {
      gap: sizes.gap.xl,
      paddingVertical: sizes.padding.md,
    },
    input: {
      width: sizes.dimension['3xl'],
    },
    layout: {
      flex: 1,
    },
    required: {
      borderColor: colors.destructive,
    },
    textDanger: {
      color: colors.destructive,
    },
  });

const SaleForm = () => {
  const styles = useStyles(saleStyles);
  const [loading, startTransition] = useTransition();
  const [open, setOpen] = React.useState(false);
  const { params } = useSaleRouteParams<'SaleForm'>();
  const sale = params.sale;
  const { setSync } = useSync();
  const navigation = useSaleNavigation();
  const debounceRef = useRef<NodeJS.Timeout | null>(null);
  const form = useForm<SaleFormType>({
    defaultValues: {
      id: '',
      customers_id: '',
      payment_methods_id: '',
      products: [],
    },
  });

  const { fields, remove, append } = useFieldArray({
    control: form.control,
    name: 'products',
  });

  // const productsField = form.watch('products');

  const createSale = React.useCallback(
    (data: Omit<SaleCreateUpdate, 'id'>) => {
      startTransition(async () => {
        const response = await createSaleRequest(data);
        if (response.success) {
          setSync('sale');
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

  const updateSale = React.useCallback(
    (data: SaleCreateUpdate) => {
      startTransition(async () => {
        const response = await updateSaleRequest(data);
        if (response.success) {
          setSync('sale');
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
    if (sale) {
      form.setValue('id', sale.id.toString());
      form.setValue('customers_id', sale.customers_id.toString());
      form.setValue('payment_methods_id', sale.payment_methods_id.toString());

      const products = sale.sale_products.map((saleProduct) => ({
        products_id: saleProduct.products_id.toString(),
        quantity: saleProduct.quantity.toString(),
        products_name: saleProduct.product.name,
        products_price: saleProduct.product.price,
        products_quantity: saleProduct.product.quantity + saleProduct.quantity,
      }));
      form.setValue('products', products);
    }
  }, [sale, form]);

  const onSubmit = React.useCallback(
    (formData: SaleFormType) => {
      if (formData.products.length === 0) {
        toast.error({ title: 'Selecione pelo menos um produto!' });
        return;
      }

      const newFormData: Omit<SaleCreateUpdate, 'id'> = {
        customers_id: Number(formData.customers_id),
        payment_methods_id: Number(formData.payment_methods_id),
        products: formData.products.map((product) => ({
          products_id: Number(product.products_id),
          quantity: Number(product.quantity),
        })),
      };

      if (formData.id) {
        updateSale({
          ...newFormData,
          id: Number(formData.id),
        });
      } else {
        createSale(newFormData);
      }
    },
    [createSale, updateSale],
  );

  const onProductSelect = React.useCallback(
    (product: Product) => {
      setOpen(false);
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        let quantity = product.quantity;

        if (sale) {
          const productExists = sale.sale_products.find((sale_product) => sale_product.product.id === product.id);
          if (productExists) {
            quantity = productExists.product.quantity + productExists.quantity;
          }
        }

        append({
          products_id: product.id.toString(),
          quantity: '1',
          products_name: product.name,
          products_price: product.price,
          products_quantity: quantity,
        });
      }, 200);
    },
    [append, sale],
  );

  const products = form.watch('products');
  const total = products.reduce((acc, product) => acc + parseToInt(product.quantity) * product.products_price, 0);

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">{sale ? 'Alterar' : 'Adicionar'} Venda</HeaderTitle>
        </HeaderContent>
        <HeaderAdornment>
          <HeaderHiddenButton />
        </HeaderAdornment>
      </Header>
      <KeyboardAvoidingContent>
        <ContainerScrollView contentContainerStyle={styles.contentContainerList}>
          <View style={styles.content}>
            <FormField
              control={form.control}
              label="Cliente"
              name="customers_id"
              rules={rules.customers_id}
              render={({ field }) => (
                <SaleFormCustomerSelect disabled={loading} value={field.value} onValueChange={field.onChange} />
              )}
            />

            <FormField
              control={form.control}
              label="Forma de Pagamento"
              name="payment_methods_id"
              rules={rules.payment_methods_id}
              render={({ field }) => (
                <SaleFormPaymentMethodSelect disabled={loading} value={field.value} onValueChange={field.onChange} />
              )}
            />
          </View>

          <Item>
            <ItemAdornment>
              <Icon name="Package" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Produtos</ItemTitle>
            </ItemContent>
            <ItemAdornment>
              <SaleFormProductSelect
                selectedArrayId={products.map((product) => parseToInt(product.products_id))}
                open={open}
                onOpenChange={setOpen}
                onProductSelect={onProductSelect}
              >
                <Button size="icon" style={styles.button} variant="default" icon="Plus" />
              </SaleFormProductSelect>
            </ItemAdornment>
          </Item>

          <FlatList
            data={fields}
            keyExtractor={(product) => product.id.toString()}
            scrollEnabled={false}
            ListEmptyComponent={<Empty title="Nenhum registro encontrado!" />}
            ItemSeparatorComponent={() => <Separator />}
            renderItem={({ item: field, index }) => (
              <Item>
                <ItemContent>
                  <ItemTitle numberOfLines={1}>{field.products_name}</ItemTitle>
                  <ItemDescription>
                    R$ {formaters.money(field.products_price)}{' '}
                    <ItemDescription weight="bold" numberOfLines={1}>
                      (R$ {formaters.money(parseToInt(products[index].quantity) * products[index].products_price)})
                    </ItemDescription>
                  </ItemDescription>
                  <ItemDescription
                    weight="normal"
                    numberOfLines={1}
                    style={field.products_quantity === 0 && styles.textDanger}
                  >
                    Estoque: {field.products_quantity}
                  </ItemDescription>
                </ItemContent>
                <ItemAdornment>
                  <FormField
                    control={form.control}
                    name={`products.${index}.quantity`}
                    rules={{
                      required: 'obrigatório!',
                      validate: (value: string) => validator.number(value, 1, field.products_quantity),
                    }}
                    hideErrorMessage
                    render={({ field, fieldState }) => (
                      <InputNumber
                        style={[styles.input, fieldState.invalid && styles.required]}
                        keyboardType="numeric"
                        maxLength={5}
                        decimals={0}
                        disabled={loading}
                        placeholder="0"
                        onChangeText={field.onChange}
                        value={field.value}
                      />
                    )}
                  />
                  <Button
                    size="icon"
                    style={styles.button}
                    variant="ghost"
                    icon="Trash"
                    onPress={() => remove(index)}
                  />
                </ItemAdornment>
              </Item>
            )}
          />
          <Separator />
          <Item>
            <ItemAdornment>
              <Icon name="DollarSign" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Total</ItemTitle>
            </ItemContent>
            <ItemAdornment>
              <ItemTitle>R$ {formaters.money(total)}</ItemTitle>
            </ItemAdornment>
          </Item>
          <View style={styles.content}>
            <Button icon="Send" iconPlacement="right" loading={loading} onPress={form.handleSubmit(onSubmit)}>
              Finalizar
            </Button>
          </View>
        </ContainerScrollView>
      </KeyboardAvoidingContent>
    </View>
  );
};

export default SaleForm;
