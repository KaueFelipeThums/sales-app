import { StyleSheet, View } from 'react-native';
import { Icon } from '@/core/components/ui/icon';
import { Text } from '@/core/components/ui/text';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

const emptyStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    empty: {
      alignItems: 'center',
      flex: 1,
      gap: sizes.gap.lg,
      justifyContent: 'center',
      padding: sizes.padding.xl,
      width: '100%',
    },
    emptyText: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.sm,
      textAlign: 'center',
    },
  });

const Empty = ({ style, title, ...props }: React.ComponentPropsWithRef<typeof View> & { title?: string }) => {
  const styles = useStyles(emptyStyles);
  const { sizes, colors } = useTheme();

  return (
    <View style={[styles.empty, style]} {...props}>
      <Icon name="Search" color={colors.mutedForeground} size={sizes.dimension['2xl']} />
      {title && <Text style={styles.emptyText}>{title}</Text>}
    </View>
  );
};

export { Empty };
