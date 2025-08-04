import { NativeStackHeaderProps } from '@react-navigation/native-stack';
import { Header, HeaderAdornment, HeaderButton, HeaderContent, HeaderHiddenButton, HeaderTitle } from './header';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';

const HeaderRoutes = ({ navigation, options }: NativeStackHeaderProps) => {
  const { headerRight, headerLeft, headerTitleStyle, headerTitleAlign } = options;
  const { colors } = useTheme();
  const showBackButton = navigation.canGoBack();

  return (
    <Header withInsets variant="ghost">
      {(showBackButton || headerLeft) && (
        <HeaderAdornment>
          {showBackButton && (
            <HeaderButton size="icon" variant="outline" icon="ChevronLeft" onPress={() => navigation.goBack()} />
          )}
          {headerLeft && headerLeft({ tintColor: colors.foreground, canGoBack: navigation.canGoBack() })}
        </HeaderAdornment>
      )}

      <HeaderContent>
        {options.title && (
          <HeaderTitle numberOfLines={1} style={[headerTitleStyle, { textAlign: headerTitleAlign }]}>
            {options.title}
          </HeaderTitle>
        )}
      </HeaderContent>
      {(headerRight || showBackButton) && (
        <HeaderAdornment>
          {headerRight &&
            headerRight({
              tintColor: colors.foreground,
              canGoBack: navigation.canGoBack(),
            })}
          {showBackButton && <HeaderHiddenButton />}
        </HeaderAdornment>
      )}
    </Header>
  );
};

export { HeaderRoutes };
