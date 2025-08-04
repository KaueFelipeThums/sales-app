import { NavigationContainer } from '@react-navigation/native';
import { StyleSheet, View } from 'react-native';
import { PrivateRoutes } from './private-routes/private-routes';
import { PublicRoutes } from './public-routes';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import AuthRoutes from '@/utils/auth-routes';

const routesStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    container: {
      backgroundColor: colors.background,
      flex: 1,
    },
  });

const Routes = () => {
  const styles = useStyles(routesStyles);

  return (
    <View style={styles.container}>
      <NavigationContainer>
        <AuthRoutes privateRoutesElement={<PrivateRoutes />} publicRoutesElement={<PublicRoutes />} />
      </NavigationContainer>
    </View>
  );
};

export default Routes;
