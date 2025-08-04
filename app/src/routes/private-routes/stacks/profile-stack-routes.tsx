import { RouteProp, useNavigation, useRoute } from '@react-navigation/native';
import { createNativeStackNavigator, NativeStackNavigationProp } from '@react-navigation/native-stack';
import { StyleSheet } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { HeaderRoutes } from '@/components/layout/header-routes';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import Profile from '@/pages/profile/profile';
import UpdatePassword from '@/pages/update-password/update-password';

export type ProfileStackParamList = {
  ProfileList: undefined;
  UpdatePassword: undefined;
};

const ProfileStack = createNativeStackNavigator<ProfileStackParamList>();

const navigationStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    navigation: {
      backgroundColor: colors.transparent,
      flexGrow: 1,
      flexShrink: 1,
    },
  });

const ProfileStackRoutes = () => {
  const { right, left } = useSafeAreaInsets();
  const styles = useStyles(navigationStyles);
  const { selectedTheme } = useTheme();

  return (
    <ProfileStack.Navigator
      initialRouteName="ProfileList"
      screenOptions={{
        statusBarStyle: selectedTheme === 'dark' ? 'light' : 'dark',
        header: HeaderRoutes,
        headerShown: false,
        animation: 'slide_from_right',
        contentStyle: [styles.navigation, { paddingRight: right, paddingLeft: left }],
      }}
    >
      <ProfileStack.Screen name="ProfileList" component={Profile} />
      <ProfileStack.Screen name="UpdatePassword" component={UpdatePassword} />
    </ProfileStack.Navigator>
  );
};

function useProfileNavigation<RouteName extends keyof ProfileStackParamList>() {
  return useNavigation<NativeStackNavigationProp<ProfileStackParamList, RouteName>>();
}

function useProfileRouteParams<RouteName extends keyof ProfileStackParamList>() {
  return useRoute<RouteProp<ProfileStackParamList, RouteName>>();
}

export { ProfileStackRoutes, useProfileNavigation, useProfileRouteParams };
