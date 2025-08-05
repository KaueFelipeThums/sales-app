import React from 'react';
import { Pressable, StyleSheet, View } from 'react-native';
import { Text } from '@/core/components/ui/text';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

const themeCardStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    card: {
      alignItems: 'flex-end',
      borderRadius: sizes.radius.default,
      borderWidth: sizes.border.md,
      height: 100,
      justifyContent: 'flex-end',
      overflow: 'hidden',
      width: '100%',
    },
    dark: {
      backgroundColor: colors.gray[800],
      borderColor: colors.gray[700],
    },
    disabled: {
      opacity: 0.7,
      pointerEvents: 'none',
    },
    light: {
      backgroundColor: colors.gray[300],
      borderColor: colors.gray[200],
    },
    pressed: {
      opacity: 0.8,
    },
    selected: {
      borderColor: colors.primary,
      boxShadow: `0px 0px 0px 4px ${colors.primary}50`,
    },
  });

const themeCardContentStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    content: {
      borderLeftWidth: sizes.border.md,
      borderTopLeftRadius: sizes.radius.default,
      borderTopWidth: sizes.border.md,
      height: '75%',
      padding: sizes.padding.lg,
      width: '75%',
    },
    dark: {
      backgroundColor: colors.gray[900],
      borderColor: colors.gray[700],
    },
    light: {
      backgroundColor: colors.gray[100],
      borderColor: colors.gray[200],
    },
  });

const themeCardTextStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    dark: {
      color: colors.white,
    },
    light: {
      color: colors.black,
    },
    text: {
      fontSize: sizes.fontSize.lg,
    },
  });

type ThemeCardProps = React.ComponentPropsWithRef<typeof Pressable> & {
  variant: 'dark' | 'light';
  isSelected?: boolean;
};

const ThemeCard = ({ variant, style, isSelected, disabled, ...props }: ThemeCardProps) => {
  const styles = useStyles(themeCardStyles);
  const contentStyles = useStyles(themeCardContentStyles);
  const textStyles = useStyles(themeCardTextStyles);
  const { colors } = useTheme();

  return (
    <Pressable
      disabled={disabled}
      android_ripple={{
        color: `${colors.foreground}20`,
        foreground: true,
      }}
      style={(pressableState) => [
        styles.card,
        styles[variant],
        pressableState.pressed && styles.pressed,
        disabled && styles.disabled,
        isSelected && styles.selected,
        typeof style === 'function' ? style(pressableState) : style,
      ]}
      {...props}
    >
      <View style={[contentStyles.content, contentStyles[variant]]}>
        <Text style={[textStyles.text, textStyles[variant]]}>Aa</Text>
      </View>
      {isSelected && <Indicator />}
    </Pressable>
  );
};

const indicatorStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    dot: {
      backgroundColor: colors.primary,
      borderRadius: sizes.radius.full,
      height: sizes.dimension.xs / 1.5,
      width: sizes.dimension.xs / 1.5,
    },
    indicator: {
      alignItems: 'center',
      borderColor: colors.primary,
      borderRadius: sizes.radius.full,
      borderWidth: sizes.border.md,
      bottom: sizes.padding.md,
      height: sizes.dimension.md / 1.5,
      justifyContent: 'center',
      position: 'absolute',
      right: sizes.padding.md,
      width: sizes.dimension.md / 1.5,
    },
  });

const Indicator = ({ style, ...props }: React.ComponentPropsWithRef<typeof View>) => {
  const styles = useStyles(indicatorStyles);

  return (
    <View style={[styles.indicator, style]} {...props}>
      <View style={styles.dot} />
    </View>
  );
};

export { ThemeCard };
