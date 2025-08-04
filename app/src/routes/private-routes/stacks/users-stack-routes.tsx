import { RouteProp, useNavigation, useRoute } from '@react-navigation/native';
import { createNativeStackNavigator, NativeStackNavigationProp } from '@react-navigation/native-stack';
import { StyleSheet } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { HeaderRoutes } from '@/components/layout/header-routes';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import Users from '@/pages/users/users';
import UsersForm from '@/pages/users/users-form';
import { User } from '@/types/user';

export type UsersStackParamList = {
  UsersList: undefined;
  UsersForm: { users?: User };
};

const UsersStack = createNativeStackNavigator<UsersStackParamList>();

const navigationStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    navigation: {
      backgroundColor: colors.transparent,
      flexGrow: 1,
      flexShrink: 1,
    },
  });

const UsersStackRoutes = () => {
  const { right, left } = useSafeAreaInsets();
  const styles = useStyles(navigationStyles);
  const { selectedTheme } = useTheme();

  return (
    <UsersStack.Navigator
      initialRouteName="UsersList"
      screenOptions={{
        statusBarStyle: selectedTheme === 'dark' ? 'light' : 'dark',
        header: HeaderRoutes,
        headerShown: false,
        animation: 'slide_from_right',
        contentStyle: [styles.navigation, { paddingRight: right, paddingLeft: left }],
      }}
    >
      <UsersStack.Screen name="UsersList" component={Users} />
      <UsersStack.Screen name="UsersForm" component={UsersForm} />
    </UsersStack.Navigator>
  );
};

function useUsersNavigation<RouteName extends keyof UsersStackParamList>() {
  return useNavigation<NativeStackNavigationProp<UsersStackParamList, RouteName>>();
}

function useUsersRouteParams<RouteName extends keyof UsersStackParamList>() {
  return useRoute<RouteProp<UsersStackParamList, RouteName>>();
}

export { UsersStackRoutes, useUsersNavigation, useUsersRouteParams };
