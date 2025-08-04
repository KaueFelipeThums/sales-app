import { StyleSheet, useColorScheme, View } from 'react-native';
import { ThemeCard } from '@/components/layout/theme-card';
import { Text } from '@/core/components/ui/text';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

const updateThemeStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    content: {
      flexDirection: 'row',
      gap: sizes.gap.lg,
      padding: sizes.padding['2xl'],
      width: '100%',
    },
    option: {
      flex: 1,
      gap: sizes.gap.lg,
    },
    optionText: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.sm,
    },
    optionTextSelected: {
      color: colors.foreground,
      fontSize: sizes.fontSize.sm,
      fontWeight: sizes.fontWeight.semibold,
    },
  });

const UpdateTheme = () => {
  const styles = useStyles(updateThemeStyles);
  const colorScheme = useColorScheme();
  const { setThemeMode, themeMode } = useTheme();

  return (
    <View style={styles.content}>
      <View style={styles.option}>
        <ThemeCard variant="light" isSelected={themeMode === 'light'} onPress={() => setThemeMode('light')} />
        <Text style={[styles.optionText, themeMode === 'light' && styles.optionTextSelected]}>Claro</Text>
      </View>
      <View style={styles.option}>
        <ThemeCard variant="dark" isSelected={themeMode === 'dark'} onPress={() => setThemeMode('dark')} />
        <Text style={[styles.optionText, themeMode === 'dark' && styles.optionTextSelected]}>Escuro</Text>
      </View>
      <View style={styles.option}>
        <ThemeCard
          variant={colorScheme ?? 'light'}
          isSelected={themeMode === 'system'}
          onPress={() => setThemeMode('system')}
        />
        <Text
          style={[styles.optionText, themeMode === 'system' && styles.optionTextSelected]}
          onPress={() => setThemeMode('system')}
        >
          Sistema
        </Text>
      </View>
    </View>
  );
};

export default UpdateTheme;
