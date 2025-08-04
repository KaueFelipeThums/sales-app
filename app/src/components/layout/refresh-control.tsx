import { Platform, RefreshControl as RNRefreshControl } from 'react-native';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';

const RefreshControl = ({ ...props }: React.ComponentPropsWithRef<typeof RNRefreshControl>) => {
  const { colors } = useTheme();

  return (
    <RNRefreshControl
      tintColor={Platform.OS === 'ios' ? colors.foreground : colors.background}
      colors={[colors.background]}
      progressBackgroundColor={colors.foreground}
      {...props}
    />
  );
};

export { RefreshControl };
