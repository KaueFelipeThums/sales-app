import { ActivityIndicator, StyleSheet, View } from 'react-native';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

const loadingSectionStyles = ({ colors }: ThemeValue) =>
  StyleSheet.create({
    content: {
      alignItems: 'center',
      backgroundColor: colors.background,
      flexGrow: 1,
      justifyContent: 'center',
    },
  });

const LoadingSection = ({ style, ...props }: React.ComponentPropsWithRef<typeof View>) => {
  const styles = useStyles(loadingSectionStyles);
  const { colors } = useTheme();

  return (
    <View style={[styles.content, style]} {...props}>
      <ActivityIndicator size="large" color={colors.foreground} />
    </View>
  );
};

export { LoadingSection };
