import { BottomTabBarProps, BottomTabNavigationProp, createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { useNavigation } from '@react-navigation/native';
import { StyleSheet } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { ProfileStackRoutes } from './stacks/profile-stack-routes';
import { RegistrationStackRoutes } from './stacks/registration-stack-routes';
import { SaleStackRoutes } from './stacks/sale-stack-routes';
import { UsersStackRoutes } from './stacks/users-stack-routes';
import { TabBar, TabBarButton, TabBarButtonIcon, TabBarButtonTitle } from '@/components/layout/tab-bar';
import { IconName } from '@/core/components/ui/icon';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { useNetConnection } from '@/hooks/use-net-connection';

export type PrivateTabsParamList = {
  Sale: undefined;
  Registrations: undefined;
  Users: undefined;
  Profile: undefined;
};

const privateTabsIcons: Record<keyof PrivateTabsParamList, IconName> = {
  Sale: 'DollarSign',
  Registrations: 'CirclePlus',
  Users: 'UserPlus',
  Profile: 'User',
};

const PrivateTabs = createBottomTabNavigator<PrivateTabsParamList>();

const navigationStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    navigation: {
      backgroundColor: colors.transparent,
      flexGrow: 1,
      flexShrink: 1,
    },
  });

const PrivateRoutesTabBar = ({ state, navigation, descriptors }: BottomTabBarProps) => {
  return (
    <TabBar withInsets variant="default">
      {state.routes.map((route, index) => {
        const { options } = descriptors[route.key];
        const label = options.tabBarLabel ?? route.name;
        const routeName: keyof PrivateTabsParamList = route.name as keyof PrivateTabsParamList;

        const isFocused = state.index === index;

        const onPress = () => {
          const sale = navigation.emit({
            type: 'tabPress',
            target: route.key,
            canPreventDefault: true,
          });

          if (!isFocused && !sale.defaultPrevented) {
            navigation.navigate(route.name, route.params);
          }
        };

        const onLongPress = () => {
          navigation.emit({
            type: 'tabLongPress',
            target: route.key,
          });
        };

        return (
          <TabBarButton onPress={onPress} onLongPress={onLongPress} key={route.key} active={index === state.index}>
            <TabBarButtonIcon name={privateTabsIcons[routeName]} />
            <TabBarButtonTitle>{label.toString()}</TabBarButtonTitle>
          </TabBarButton>
        );
      })}
    </TabBar>
  );
};

const PrivateRoutes = () => {
  const { right, left } = useSafeAreaInsets();
  const styles = useStyles(navigationStyles);
  useNetConnection();

  return (
    <PrivateTabs.Navigator
      tabBar={PrivateRoutesTabBar}
      initialRouteName="Sale"
      screenOptions={{
        headerShown: false,
        sceneStyle: [styles.navigation, { paddingRight: right, paddingLeft: left }],
      }}
    >
      <PrivateTabs.Screen options={{ tabBarLabel: 'Vendas' }} name="Sale" component={SaleStackRoutes} />
      <PrivateTabs.Screen
        options={{ tabBarLabel: 'Cadastros' }}
        name="Registrations"
        component={RegistrationStackRoutes}
      />
      <PrivateTabs.Screen options={{ tabBarLabel: 'Usuários' }} name="Users" component={UsersStackRoutes} />
      <PrivateTabs.Screen options={{ tabBarLabel: 'Perfil' }} name="Profile" component={ProfileStackRoutes} />
    </PrivateTabs.Navigator>
  );
};

export type AppNavigationProp = BottomTabNavigationProp<PrivateTabsParamList>;

function usePrivateRoutesNavigation() {
  return useNavigation<AppNavigationProp>();
}

export { PrivateRoutes, usePrivateRoutesNavigation };
