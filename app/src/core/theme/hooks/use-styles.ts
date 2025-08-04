import React from 'react';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

type Generator<TStyles> = (theme: ThemeValue) => TStyles;

function useStyles<TStyles extends object>(generator: Generator<TStyles>): TStyles {
  const { colors, sizes, selectedTheme, themeMode } = useTheme();

  const themedStyles = React.useMemo(
    () => generator({ colors, sizes, selectedTheme, themeMode }),
    [generator, colors, sizes, selectedTheme, themeMode],
  );

  return themedStyles;
}

export { useStyles };
