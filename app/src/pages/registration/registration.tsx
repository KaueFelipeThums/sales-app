import { StyleSheet, View } from 'react-native';
import { ContainerScrollView } from '@/components/layout/container';
import { Header, HeaderContent, HeaderTitle } from '@/components/layout/header';
import { Icon } from '@/core/components/ui/icon';
import { ItemAdornment, ItemContent, ItemDescription, ItemPressable, ItemTitle } from '@/core/components/ui/item';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { useRegistrationNavigation } from '@/routes/private-routes/stacks/registration-stack-routes';

const usersStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    header: {
      gap: sizes.gap.lg,
    },
    layout: {
      flex: 1,
    },
  });

const Registration = () => {
  const styles = useStyles(usersStyles);
  const navigation = useRegistrationNavigation();
  const { sizes } = useTheme();

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderContent>
          <HeaderTitle align="center">Cadastros</HeaderTitle>
        </HeaderContent>
      </Header>
      <ContainerScrollView keyboardShouldPersistTaps="handled">
        <ItemPressable>
          <ItemAdornment>
            <Icon name="Users" size={sizes.fontSize['2xl']} />
          </ItemAdornment>
          <ItemContent>
            <ItemTitle numberOfLines={1}>Clientes</ItemTitle>
            <ItemDescription numberOfLines={1}>Gestão</ItemDescription>
          </ItemContent>
          <ItemAdornment>
            <ItemAdornment>
              <Icon name="ChevronRight" size={sizes.fontSize.sm} />
            </ItemAdornment>
          </ItemAdornment>
        </ItemPressable>
        <ItemPressable onPress={() => navigation.navigate('Product')}>
          <ItemAdornment>
            <Icon name="Package" size={sizes.fontSize['2xl']} />
          </ItemAdornment>
          <ItemContent>
            <ItemTitle numberOfLines={1}>Produtos</ItemTitle>
            <ItemDescription numberOfLines={1}>Gestão</ItemDescription>
          </ItemContent>
          <ItemAdornment>
            <ItemAdornment>
              <Icon name="ChevronRight" size={sizes.fontSize.sm} />
            </ItemAdornment>
          </ItemAdornment>
        </ItemPressable>
        <ItemPressable onPress={() => navigation.navigate('PaymentMethod')}>
          <ItemAdornment>
            <Icon name="CreditCard" size={sizes.fontSize['2xl']} />
          </ItemAdornment>
          <ItemContent>
            <ItemTitle numberOfLines={1}>Formas de Pagamento</ItemTitle>
            <ItemDescription numberOfLines={1}>Gestão</ItemDescription>
          </ItemContent>
          <ItemAdornment>
            <ItemAdornment>
              <Icon name="ChevronRight" size={sizes.fontSize.sm} />
            </ItemAdornment>
          </ItemAdornment>
        </ItemPressable>
      </ContainerScrollView>
    </View>
  );
};

export default Registration;
