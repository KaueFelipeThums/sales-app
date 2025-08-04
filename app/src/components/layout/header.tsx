import { StyleSheet, View } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { Text } from '@/core/components/ui/text';
import { Button } from '@/core/components/ui-presets/button';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

const headerStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    default: {
      backgroundColor: colors.background,
      borderBottomColor: colors.border,
      borderBottomWidth: sizes.border.sm,
    },
    ghost: {
      backgroundColor: colors.transparent,
    },
    header: {
      alignItems: 'center',
      flexDirection: 'row',
      gap: sizes.gap.xl,
      overflow: 'hidden',
      paddingHorizontal: sizes.padding.xl,
      paddingVertical: sizes.padding.lg,
      zIndex: 10,
    },
  });

type HeaderProps = React.ComponentPropsWithRef<typeof View> & {
  variant?: 'ghost' | 'default';
  withInsets?: boolean;
};

const Header = ({ style, variant = 'default', withInsets = false, ...props }: HeaderProps) => {
  const styles = useStyles(headerStyles);
  const { sizes } = useTheme();
  const { top } = useSafeAreaInsets();

  return (
    <View
      style={[styles.header, styles[variant], { paddingTop: (withInsets ? top : 0) + sizes.padding.lg }, style]}
      {...props}
    />
  );
};

const itemContentStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    itemContent: {
      flexGrow: 1,
      flexShrink: 1,
      gap: sizes.gap.sm,
      justifyContent: 'center',
      minHeight: sizes.dimension.lg,
    },
  });

const HeaderContent = ({ style, ...props }: React.ComponentPropsWithRef<typeof View>) => {
  const styles = useStyles(itemContentStyles);
  return <View style={[styles.itemContent, style]} {...props} />;
};

const itemAdornmentStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    itemAdornment: {
      alignItems: 'center',
      flexDirection: 'row',
      gap: sizes.gap.md,
    },
  });

const HeaderAdornment = ({ style, ...props }: React.ComponentPropsWithRef<typeof View>) => {
  const styles = useStyles(itemAdornmentStyles);
  return <View style={[styles.itemAdornment, style]} {...props} />;
};

const itemTitleStyles = ({ colors, sizes }: ThemeValue) =>
  StyleSheet.create({
    title: {
      color: colors.foreground,
      fontFamily: '',
      fontWeight: sizes.fontWeight.medium,
    },
  });

const HeaderTitle = ({ style, ...props }: React.ComponentPropsWithRef<typeof Text>) => {
  const styles = useStyles(itemTitleStyles);
  return <Text style={[styles.title, style]} {...props} />;
};

const itemDescriptionStyles = ({ colors, sizes }: ThemeValue) =>
  StyleSheet.create({
    description: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.sm,
    },
  });

const HeaderDescription = ({ style, ...props }: React.ComponentPropsWithRef<typeof Text>) => {
  const styles = useStyles(itemDescriptionStyles);
  return <Text style={[styles.description, style]} {...props} />;
};

const headerButtonStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    headerButton: {
      height: sizes.dimension.xl / 1.1,
      width: sizes.dimension.xl / 1.1,
    },
  });

const HeaderButton = ({ style, ...props }: React.ComponentPropsWithRef<typeof Button>) => {
  const styles = useStyles(headerButtonStyles);
  return (
    <Button
      size="icon"
      style={(pressableState) => [styles.headerButton, typeof style === 'function' ? style(pressableState) : style]}
      {...props}
    />
  );
};

const headerHiddenButtonStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    hiddenButton: {
      height: sizes.dimension.xl / 1.1,
      width: sizes.dimension.xl / 1.1,
    },
  });

const HeaderHiddenButton = ({ style, ...props }: React.ComponentPropsWithRef<typeof View>) => {
  const styles = useStyles(headerHiddenButtonStyles);
  return <View style={[styles.hiddenButton, style]} {...props} />;
};

export { Header, HeaderContent, HeaderAdornment, HeaderTitle, HeaderDescription, HeaderButton, HeaderHiddenButton };
