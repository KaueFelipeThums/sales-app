import { useCallback, useEffect, useReducer, useRef, useState, useTransition } from 'react';
import { FlatList, StyleSheet, View } from 'react-native';
import { CardSkeleton } from '@/components/layout/card-skeleton';
import { Empty } from '@/components/layout/empty';
import {
  Header,
  HeaderAdornment,
  HeaderButton,
  HeaderContent,
  HeaderHiddenButton,
  HeaderTitle,
} from '@/components/layout/header';

import { RefreshControl } from '@/components/layout/refresh-control';
import { Icon } from '@/core/components/ui/icon';
import { ItemAdornment, ItemContent, ItemDescription, ItemPressable, ItemTitle } from '@/core/components/ui/item';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { MenuActions } from '@/core/components/ui-presets/menu-actions';
import { usePopConfirm } from '@/core/components/ui-presets/popconfirm';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { useSkipInitialFocusEffect } from '@/hooks/use-skip-initial-focus-effect';
import { useSync } from '@/providers/sync/sync-provider';
import { useUsersNavigation } from '@/routes/private-routes/stacks/users-stack-routes';
import { deleteUserRequest, getAllUsersRequest } from '@/services/api/users';
import { User } from '@/types/user';
import { reducer } from '@/utils/reducer';

const usersStyles = ({ sizes, colors }: ThemeValue) =>
  StyleSheet.create({
    contentContainerList: {
      paddingBottom: sizes.padding.xl,
      paddingTop: sizes.padding.xs,
    },
    description: {
      color: colors.mutedForeground,
      fontSize: sizes.fontSize.sm,
    },
    header: {
      gap: sizes.gap.lg,
    },
    layout: {
      flex: 1,
    },
  });

const SkeletonList = () => {
  return (
    <View>
      {Array.from({ length: 10 }).map((_, index) => (
        <CardSkeleton key={`skeleton-${index}`} />
      ))}
    </View>
  );
};

const Users = () => {
  const styles = useStyles(usersStyles);
  const confirm = usePopConfirm();
  const [hydrating, setHydrating] = useState<boolean>(true);
  const [loading, startTransition] = useTransition();
  const [paginationLoading, startPaginationTransition] = useTransition();
  const [users, dispatch] = useReducer(reducer, [] as User[]);
  const { sizes } = useTheme();
  const page = useRef<number>(1);
  const listRef = useRef<FlatList<User>>(null);
  const hasMoreRef = useRef<boolean>(true);
  const isLoading = loading || hydrating;
  const navigation = useUsersNavigation();
  const { getShouldSync, clearSync } = useSync();

  const getAllUsers = useCallback(
    (pageToLoad: number = 1, search: string = '', append: boolean = false) => {
      const transition = append ? startPaginationTransition : startTransition;
      if (!append) {
        listRef.current?.scrollToOffset({ offset: 0, animated: false });
      }
      transition(async () => {
        const response = await getAllUsersRequest({
          search: search,
          page: pageToLoad,
          page_count: 10,
        });

        if (response.success) {
          dispatch({ type: append ? 'CONCAT' : 'LOAD', payload: response.data });
          hasMoreRef.current = response.data.length == 10;
          page.current = pageToLoad;
          /**
           * Limpa os dados de sincronização
           */
          if (!append) {
            clearSync('users');
          }
        } else {
          hasMoreRef.current = false;
          page.current = 1;
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
        setHydrating(false);
      });
    },
    [clearSync],
  );

  const deleteUser = useCallback(
    (data: User) => {
      startTransition(async () => {
        const response = await deleteUserRequest(data.id);
        if (response.success) {
          getAllUsers();
          toast.success({ title: 'Registro deletado com sucesso!' });
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [getAllUsers],
  );

  useSkipInitialFocusEffect(
    useCallback(() => {
      /**
       * Verifica se há a necessidade de sincronizar e recarrega a lista
       */
      if (getShouldSync('users')) {
        getAllUsers();
      }
    }, [getAllUsers, getShouldSync]),
  );

  useEffect(() => {
    getAllUsers();
  }, [getAllUsers]);

  const handleRefresh = useCallback(() => {
    getAllUsers(1, '', false);
  }, [getAllUsers]);

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderHiddenButton />
        </HeaderAdornment>
        <HeaderContent>
          <HeaderTitle align="center">Usuários</HeaderTitle>
        </HeaderContent>
        <HeaderAdornment>
          <HeaderButton
            icon="Plus"
            variant="outline"
            onPress={() => navigation.navigate('UsersForm', { users: undefined })}
          />
        </HeaderAdornment>
      </Header>
      <FlatList
        scrollEventThrottle={16}
        initialNumToRender={10}
        removeClippedSubviews
        ref={listRef}
        maxToRenderPerBatch={5}
        onEndReachedThreshold={0}
        onEndReached={() => {
          if (isLoading || paginationLoading) return;
          hasMoreRef.current && !isLoading && getAllUsers(page.current + 1, '', true);
        }}
        refreshControl={<RefreshControl refreshing={loading} onRefresh={handleRefresh} />}
        ListEmptyComponent={!isLoading ? <Empty title="Nenhum usuário encontrado!" /> : null}
        contentContainerStyle={[styles.contentContainerList]}
        style={styles.layout}
        data={!isLoading ? users : []}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item: user }) => {
          return (
            <MenuActions
              items={[
                {
                  key: 'game-schedule',
                  label: 'Editar',
                  icon: <Icon name="Pen" />,
                  shortcut: <Icon name="ChevronRight" />,
                  onPress: () => navigation.navigate('UsersForm', { users: user }),
                },
                {
                  key: 'publications',
                  label: 'Deletar',
                  icon: <Icon name="Trash" />,
                  shortcut: <Icon name="ChevronRight" />,
                  onPress: () =>
                    confirm.open({
                      title: 'Deseja realmente deletar o registro?',
                      variant: 'destructive',
                      onConfirm: () => deleteUser(user),
                    }),
                },
              ]}
            >
              <ItemPressable>
                <ItemAdornment>
                  <Icon name="User" size={sizes.fontSize['2xl']} />
                </ItemAdornment>
                <ItemContent>
                  <ItemTitle numberOfLines={1}>{user.name}</ItemTitle>
                  <ItemDescription numberOfLines={1}>{user.email}</ItemDescription>
                </ItemContent>
                <ItemAdornment>
                  <ItemAdornment>
                    <Icon name="ChevronRight" size={sizes.fontSize.sm} />
                  </ItemAdornment>
                </ItemAdornment>
              </ItemPressable>
            </MenuActions>
          );
        }}
        ListFooterComponent={
          isLoading ? (
            <SkeletonList />
          ) : hasMoreRef.current ? (
            <Button
              variant="ghost"
              loading={isLoading || paginationLoading}
              onPress={() => getAllUsers(page.current + 1, '', true)}
            >
              Carregar mais
            </Button>
          ) : null
        }
      />
    </View>
  );
};

export default Users;
