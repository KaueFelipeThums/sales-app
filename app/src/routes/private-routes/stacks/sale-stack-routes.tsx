import { RouteProp, useNavigation, useRoute } from '@react-navigation/native';
import { createNativeStackNavigator, NativeStackNavigationProp } from '@react-navigation/native-stack';
import { StyleSheet } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { HeaderRoutes } from '@/components/layout/header-routes';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import Sale from '@/pages/sale/sale';
import SaleDetails from '@/pages/sale/sale-details';
import SaleForm from '@/pages/sale/sale-form';
import type { Sale as SaleType } from '@/types/sale';

export type SaleStackParamList = {
  SaleList: undefined;
  SaleForm: { sale?: SaleType };
  SaleDetails: { sale: SaleType };
};

const SaleStack = createNativeStackNavigator<SaleStackParamList>();

const navigationStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    navigation: {
      backgroundColor: colors.transparent,
      flexGrow: 1,
      flexShrink: 1,
    },
  });

const SaleStackRoutes = () => {
  const { right, left } = useSafeAreaInsets();
  const styles = useStyles(navigationStyles);
  const { selectedTheme } = useTheme();

  return (
    <SaleStack.Navigator
      initialRouteName="SaleList"
      screenOptions={{
        statusBarStyle: selectedTheme === 'dark' ? 'light' : 'dark',
        header: HeaderRoutes,
        headerShown: false,
        animation: 'slide_from_right',
        contentStyle: [styles.navigation, { paddingRight: right, paddingLeft: left }],
      }}
    >
      <SaleStack.Screen name="SaleList" component={Sale} />
      <SaleStack.Screen name="SaleForm" component={SaleForm} />
      <SaleStack.Screen name="SaleDetails" component={SaleDetails} />
    </SaleStack.Navigator>
  );
};

function useSaleNavigation<RouteName extends keyof SaleStackParamList>() {
  return useNavigation<NativeStackNavigationProp<SaleStackParamList, RouteName>>();
}

function useSaleRouteParams<RouteName extends keyof SaleStackParamList>() {
  return useRoute<RouteProp<SaleStackParamList, RouteName>>();
}

export { SaleStackRoutes, useSaleNavigation, useSaleRouteParams };
