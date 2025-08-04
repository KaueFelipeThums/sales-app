import { RouteProp, useNavigation, useRoute } from '@react-navigation/native';
import { createNativeStackNavigator, NativeStackNavigationProp } from '@react-navigation/native-stack';
import { StyleSheet } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import Login from '@/pages/auth/login';

export type PublicStackParamList = {
  Login: undefined;
};

const PublicStack = createNativeStackNavigator<PublicStackParamList>();

const navigationStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    navigation: {
      backgroundColor: colors.transparent,
      flexGrow: 1,
      flexShrink: 1,
    },
  });

const PublicRoutes = () => {
  const { bottom, right, left, top } = useSafeAreaInsets();
  const { selectedTheme } = useTheme();
  const styles = useStyles(navigationStyles);

  return (
    <PublicStack.Navigator
      initialRouteName="Login"
      screenOptions={{
        statusBarStyle: selectedTheme === 'dark' ? 'light' : 'dark',
        headerShown: false,
        animation: 'slide_from_right',
        contentStyle: [
          styles.navigation,
          { paddingBottom: bottom, paddingRight: right, paddingLeft: left, paddingTop: top },
        ],
      }}
    >
      <PublicStack.Screen name="Login" component={Login} />
    </PublicStack.Navigator>
  );
};

function usePublicNavigation<RouteName extends keyof PublicStackParamList>() {
  return useNavigation<NativeStackNavigationProp<PublicStackParamList, RouteName>>();
}

function usePublicRouteParams<RouteName extends keyof PublicStackParamList>() {
  return useRoute<RouteProp<PublicStackParamList, RouteName>>();
}

export { PublicRoutes, usePublicNavigation, usePublicRouteParams };
