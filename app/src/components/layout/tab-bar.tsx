import { createContext, useContext, useEffect, useRef } from 'react';
import { Animated, Easing, Pressable, StyleSheet, View } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { ButtonTitle } from '@/core/components/ui/button';
import { Icon } from '@/core/components/ui/icon';
import { Text } from '@/core/components/ui/text';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { useKeyboard } from '@/hooks/useKeyboard';

const tabBarStyles = ({ colors, sizes }: ThemeValue) =>
  StyleSheet.create({
    default: {
      backgroundColor: colors.background,
      borderTopColor: colors.border,
      borderTopWidth: sizes.border.sm,
    },
    ghost: {
      backgroundColor: colors.transparent,
    },
    hidden: {
      display: 'none',
    },
    hiddenView: {
      width: '100%',
    },
    tabBar: {
      alignItems: 'center',
      flexDirection: 'row',
    },
  });

type TabBarProps = React.ComponentPropsWithRef<typeof View> & {
  variant?: 'ghost' | 'default';
  withInsets?: boolean;
};

const TabBar = ({ style, variant = 'default', withInsets = false, ...props }: TabBarProps) => {
  const { keyboardVisible } = useKeyboard();
  const styles = useStyles(tabBarStyles);
  const { bottom } = useSafeAreaInsets();

  if (keyboardVisible) {
    return <View style={[styles.hiddenView, withInsets && { paddingBottom: bottom }]} />;
  }

  return (
    <View
      style={[
        styles.tabBar,
        styles[variant],
        keyboardVisible && styles.hidden,
        withInsets && { paddingBottom: bottom },
        style,
      ]}
      {...props}
    />
  );
};

type TabBarButtonContextProps = {
  active?: boolean;
};

export const TabBarButtonContext = createContext<TabBarButtonContextProps>({ active: false });

const useTabBarButtonContext = () => {
  const context = useContext(TabBarButtonContext);
  if (!context) {
    throw new Error('Button compound components cannot be rendered outside the Button component');
  }
  return context;
};

const tabBarButtonStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    active: {
      backgroundColor: `${colors.foreground}10`,
      borderTopColor: colors.foreground,
      borderTopWidth: sizes.border.sm,
    },
    button: {
      alignItems: 'center',
      backgroundColor: colors.transparent,
      borderTopColor: colors.transparent,
      borderTopWidth: sizes.border.sm,
      flexDirection: 'column',
      flex: 1,
      gap: sizes.gap.sm,
      justifyContent: 'center',
      marginTop: -sizes.border.sm,
      padding: sizes.padding.lg,
    },
    disabled: {
      opacity: 0.8,
      pointerEvents: 'none',
    },
    invalid: {
      borderColor: colors.destructive,
    },
    pressed: {
      opacity: 0.8,
    },
  });

type TabBarButtonProps = React.ComponentPropsWithRef<typeof Pressable> & {
  active?: boolean;
};

const TabBarButton = ({ style, active, disabled, ...props }: TabBarButtonProps) => {
  const styles = useStyles(tabBarButtonStyles);
  const { colors } = useTheme();

  return (
    <TabBarButtonContext.Provider value={{ active }}>
      <Pressable
        aria-disabled={disabled ?? undefined}
        role="button"
        disabled={disabled ?? undefined}
        android_ripple={{
          color: `${colors.foreground}20`,
          borderless: false,
        }}
        style={(styleState) => [
          styles.button,
          disabled && styles.disabled,
          active && styles.active,
          styleState.pressed && styles.pressed,
          typeof style === 'function' ? style(styleState) : style,
        ]}
        {...props}
      />
    </TabBarButtonContext.Provider>
  );
};

const tabBarButtonTitleStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    active: {
      color: colors.foreground,
      fontWeight: sizes.fontWeight.medium,
    },
    title: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.xs / 1.2,
    },
  });

const TabBarButtonTitle = ({ style, ...props }: React.ComponentPropsWithRef<typeof ButtonTitle>) => {
  const styles = useStyles(tabBarButtonTitleStyles);
  const { active } = useTabBarButtonContext();

  return <Text numberOfLines={1} style={[styles.title, active && styles.active, style]} {...props} />;
};

const TabBarButtonIcon = ({ ...props }: React.ComponentPropsWithRef<typeof Icon>) => {
  const { active } = useTabBarButtonContext();
  const { colors, sizes } = useTheme();

  const scale = useRef(new Animated.Value(1)).current;

  useEffect(() => {
    if (active) {
      Animated.sequence([
        Animated.timing(scale, {
          toValue: 1.2,
          duration: 200,
          easing: Easing.out(Easing.ease),
          useNativeDriver: true,
        }),
        Animated.timing(scale, {
          toValue: 1,
          duration: 200,
          easing: Easing.out(Easing.ease),
          useNativeDriver: true,
        }),
      ]).start();
    }
  }, [active, scale]);

  return (
    <Animated.View style={{ transform: [{ scale }] }}>
      <Icon
        size={sizes.fontSize.xl}
        strokeWidth={active ? 2.5 : undefined}
        color={active ? colors.foreground : colors.mutedForeground}
        {...props}
      />
    </Animated.View>
  );
};

export { TabBar, TabBarButton, TabBarButtonTitle, TabBarButtonIcon };
