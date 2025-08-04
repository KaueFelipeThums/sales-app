import { RouteProp, useNavigation, useRoute } from '@react-navigation/native';
import { createNativeStackNavigator, NativeStackNavigationProp } from '@react-navigation/native-stack';
import { StyleSheet } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { HeaderRoutes } from '@/components/layout/header-routes';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import PaymentMethod from '@/pages/registration/payment-method/payment-method';
import PaymentMethodForm from '@/pages/registration/payment-method/payment-method-form';
import Product from '@/pages/registration/product/product';
import ProductForm from '@/pages/registration/product/product-form';
import Registration from '@/pages/registration/registration';
import type { PaymentMethod as PaymentMethodType } from '@/types/payment-method';
import type { Product as ProductType } from '@/types/product';

export type RegistrationStackParamList = {
  RegistrationList: undefined;
  Product: undefined;
  ProductForm: { product?: ProductType };
  PaymentMethod: undefined;
  PaymentMethodForm: { paymentMethod?: PaymentMethodType };
};

const RegistrationStack = createNativeStackNavigator<RegistrationStackParamList>();

const navigationStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    navigation: {
      backgroundColor: colors.transparent,
      flexGrow: 1,
      flexShrink: 1,
    },
  });

const RegistrationStackRoutes = () => {
  const { right, left } = useSafeAreaInsets();
  const styles = useStyles(navigationStyles);
  const { selectedTheme } = useTheme();

  return (
    <RegistrationStack.Navigator
      initialRouteName="RegistrationList"
      screenOptions={{
        statusBarStyle: selectedTheme === 'dark' ? 'light' : 'dark',
        header: HeaderRoutes,
        headerShown: false,
        animation: 'slide_from_right',
        contentStyle: [styles.navigation, { paddingRight: right, paddingLeft: left }],
      }}
    >
      <RegistrationStack.Screen name="RegistrationList" component={Registration} />

      <RegistrationStack.Screen name="Product" component={Product} />
      <RegistrationStack.Screen name="ProductForm" component={ProductForm} />

      <RegistrationStack.Screen name="PaymentMethod" component={PaymentMethod} />
      <RegistrationStack.Screen name="PaymentMethodForm" component={PaymentMethodForm} />
    </RegistrationStack.Navigator>
  );
};

function useRegistrationNavigation<RouteName extends keyof RegistrationStackParamList>() {
  return useNavigation<NativeStackNavigationProp<RegistrationStackParamList, RouteName>>();
}

function useRegistrationRouteParams<RouteName extends keyof RegistrationStackParamList>() {
  return useRoute<RouteProp<RegistrationStackParamList, RouteName>>();
}

export { RegistrationStackRoutes, useRegistrationNavigation, useRegistrationRouteParams };
