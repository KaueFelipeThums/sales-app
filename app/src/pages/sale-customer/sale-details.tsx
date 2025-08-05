import React from 'react';
import { FlatList, StyleSheet, View } from 'react-native';
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
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import formaters from '@/functions/formaters';
import { parseToInt } from '@/functions/parsers';
import {
  useRegistrationNavigation,
  useRegistrationRouteParams,
} from '@/routes/private-routes/stacks/registration-stack-routes';

const saleDetailsStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    button: {
      height: sizes.dimension.lg,
      width: sizes.dimension.lg,
    },
    content: {
      paddingHorizontal: sizes.padding.xl,
    },
    contentContainerList: {
      gap: sizes.gap.md,
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
  });

const SaleCustomerDetails = () => {
  const styles = useStyles(saleDetailsStyles);
  const { params } = useRegistrationRouteParams<'SaleCustomerDetails'>();
  const navigation = useRegistrationNavigation();
  const sale = params.sale;

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">Detalhes da Venda</HeaderTitle>
        </HeaderContent>
        <HeaderAdornment>
          <HeaderHiddenButton />
        </HeaderAdornment>
      </Header>
      <KeyboardAvoidingContent>
        <ContainerScrollView contentContainerStyle={styles.contentContainerList}>
          <Item>
            <ItemAdornment>
              <Icon name="User" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>{sale.customer.name}</ItemTitle>
              <ItemDescription>Cliente</ItemDescription>
            </ItemContent>
          </Item>
          <Item>
            <ItemAdornment>
              <Icon name="CreditCard" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>
                {sale.payment_method.name} ({sale.payment_method.installments} parcelas)
              </ItemTitle>
              <ItemDescription>Método de Pagamento</ItemDescription>
            </ItemContent>
          </Item>
          <Separator />
          <Item>
            <ItemAdornment>
              <Icon name="Package" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Produtos</ItemTitle>
            </ItemContent>
          </Item>

          <FlatList
            data={sale.sale_products}
            keyExtractor={(product) => product.id.toString()}
            scrollEnabled={false}
            ListEmptyComponent={<Empty title="Nenhum registro encontrado!" />}
            ItemSeparatorComponent={() => <Separator />}
            renderItem={({ item: field }) => (
              <Item>
                <ItemContent>
                  <ItemTitle numberOfLines={1}>{field.product.name}</ItemTitle>
                  <ItemDescription>
                    R$ {formaters.money(field.product.price)}{' '}
                    <ItemDescription weight="bold" numberOfLines={1}>
                      (R$ {formaters.money(parseToInt(field.quantity) * field.product.price)})
                    </ItemDescription>
                  </ItemDescription>
                </ItemContent>
                <ItemAdornment>
                  <ItemTitle>{field.quantity}</ItemTitle>
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
              <ItemTitle>R$ {formaters.money(sale.total_value)}</ItemTitle>
            </ItemAdornment>
          </Item>
        </ContainerScrollView>
      </KeyboardAvoidingContent>
    </View>
  );
};

export default SaleCustomerDetails;
