import { useTransition } from 'react';
import { StyleSheet, View } from 'react-native';
import { ContainerScrollView } from '@/components/layout/container';
import { Header, HeaderContent, HeaderTitle } from '@/components/layout/header';
import env from '@/config/env/env-config';
import { Avatar, AvatarFallback } from '@/core/components/ui/avatar';
import { Icon } from '@/core/components/ui/icon';
import { Item, ItemAdornment, ItemContent, ItemPressable, ItemTitle } from '@/core/components/ui/item';
import { Text } from '@/core/components/ui/text';
import { usePopConfirm } from '@/core/components/ui-presets/popconfirm';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { useAuth } from '@/hooks/use-auth';
import UpdateTheme from '@/pages/update-theme/update-theme';
import { useProfileNavigation } from '@/routes/private-routes/stacks/profile-stack-routes';

const APP_VERSION = env.APP_VERSION;

const profileStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    description: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.sm,
    },
    layout: {
      flex: 1,
    },
    list: {
      gap: sizes.padding['4xl'],
    },
    title: {
      color: colors.foreground,
      fontSize: sizes.fontSize['2xl'],
      fontWeight: sizes.fontWeight.bold,
    },
    user: {
      alignItems: 'center',
      flexDirection: 'row',
      gap: sizes.gap.lg,
      paddingHorizontal: sizes.padding.xl,
    },
    userDescriptionContent: {
      flex: 1,
    },
  });

const Profile = () => {
  const styles = useStyles(profileStyles);
  const [loading, startTransition] = useTransition();
  const confirm = usePopConfirm();
  const { session, endSession } = useAuth();
  const navigation = useProfileNavigation();

  const logout = () => {
    startTransition(async () => {
      await endSession();
    });
  };

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderContent>
          <HeaderTitle align="center">Perfil</HeaderTitle>
        </HeaderContent>
      </Header>
      <ContainerScrollView contentContainerStyle={styles.list}>
        <View style={styles.user}>
          <Avatar alt="avatar">
            <AvatarFallback>
              <Text>{session?.user_name?.slice(0, 1)}</Text>
            </AvatarFallback>
          </Avatar>
          <View style={styles.userDescriptionContent}>
            <Text numberOfLines={1} style={styles.description}>
              Usuário
            </Text>
            <Text numberOfLines={1} style={styles.title}>
              {session?.user_name}
            </Text>
          </View>
        </View>

        <View>
          <ItemPressable disabled={loading} onPress={() => navigation.navigate('UpdatePassword')}>
            <ItemAdornment>
              <Icon name="Lock" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Alterar Senha</ItemTitle>
            </ItemContent>
            <ItemAdornment>
              <Icon name="ChevronRight" />
            </ItemAdornment>
          </ItemPressable>

          <Item>
            <ItemAdornment>
              <Icon name="SunMoon" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Tema</ItemTitle>
            </ItemContent>
            <ItemAdornment>
              <Icon name="ChevronDown" />
            </ItemAdornment>
          </Item>

          <UpdateTheme />

          <ItemPressable disabled>
            <ItemAdornment>
              <Icon name="Rocket" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Versão {APP_VERSION}</ItemTitle>
            </ItemContent>
            <ItemAdornment>
              <Icon name="Info" />
            </ItemAdornment>
          </ItemPressable>

          <ItemPressable
            disabled={loading}
            onPress={() =>
              confirm.open({
                title: 'Deseja realmente sair?',
                onConfirm: () => logout(),
              })
            }
          >
            <ItemAdornment>
              <Icon name="LogOut" />
            </ItemAdornment>
            <ItemContent>
              <ItemTitle>Sair</ItemTitle>
            </ItemContent>
            <ItemAdornment>
              <Icon name="ChevronRight" />
            </ItemAdornment>
          </ItemPressable>
        </View>
      </ContainerScrollView>
    </View>
  );
};

export default Profile;
