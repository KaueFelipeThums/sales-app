import { useCallback, useEffect, useReducer, useRef, useState, useTransition } from 'react';
import { FlatList, StyleSheet, View } from 'react-native';
import { CardSkeleton } from '@/components/layout/card-skeleton';
import { Empty } from '@/components/layout/empty';
import { Header, HeaderAdornment, HeaderButton, HeaderContent, HeaderTitle } from '@/components/layout/header';

import { RefreshControl } from '@/components/layout/refresh-control';
import { Icon } from '@/core/components/ui/icon';
import { ItemAdornment, ItemContent, ItemDescription, ItemPressable, ItemTitle } from '@/core/components/ui/item';
import { KeyboardAvoidingContent } from '@/core/components/ui/keyboard-avoid-content';
import { toast } from '@/core/components/ui/toast';
import { Button } from '@/core/components/ui-presets/button';
import { InputText } from '@/core/components/ui-presets/input-text';
import { MenuActions } from '@/core/components/ui-presets/menu-actions';
import { usePopConfirm } from '@/core/components/ui-presets/popconfirm';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { useTheme } from '@/core/theme/theme-provider/theme-provider';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import { useSkipInitialFocusEffect } from '@/hooks/use-skip-initial-focus-effect';
import { useSync } from '@/providers/sync/sync-provider';
import { useRegistrationNavigation } from '@/routes/private-routes/stacks/registration-stack-routes';
import { deletePaymentMethodRequest, getAllPaymentMethodsRequest } from '@/services/api/payment-method';
import type { PaymentMethod as PaymentMethodType } from '@/types/payment-method';
import { reducer } from '@/utils/reducer';

const paymentMethodStyles = ({ sizes, colors }: ThemeValue) =>
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
    search: {
      height: sizes.dimension.lg,
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

const PaymentMethod = () => {
  const styles = useStyles(paymentMethodStyles);
  const confirm = usePopConfirm();
  const [hydrating, setHydrating] = useState<boolean>(true);
  const [loading, startTransition] = useTransition();
  const [paginationLoading, startPaginationTransition] = useTransition();
  const [paymentMethod, dispatch] = useReducer(reducer, [] as PaymentMethodType[]);
  const { sizes } = useTheme();
  const page = useRef<number>(1);
  const listRef = useRef<FlatList<PaymentMethodType>>(null);
  const hasMoreRef = useRef<boolean>(true);
  const isLoading = loading || hydrating;
  const navigation = useRegistrationNavigation();
  const { getShouldSync, clearSync } = useSync();
  const [search, setSearch] = useState<string>('');
  const [showSearch, setShowSearch] = useState<boolean>(false);
  const debounceRef = useRef<NodeJS.Timeout | null>(null);

  const getAllPaymentMethod = useCallback(
    (pageToLoad: number = 1, search: string = '', append: boolean = false) => {
      const transition = append ? startPaginationTransition : startTransition;
      if (!append) {
        listRef.current?.scrollToOffset({ offset: 0, animated: false });
      }
      transition(async () => {
        const response = await getAllPaymentMethodsRequest({
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
            clearSync('payment-method');
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

  const deletePaymentMethod = useCallback(
    (data: PaymentMethodType) => {
      startTransition(async () => {
        const response = await deletePaymentMethodRequest(data.id);
        if (response.success) {
          getAllPaymentMethod();
          toast.success({ title: 'Registro deletado com sucesso!' });
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [getAllPaymentMethod],
  );

  useSkipInitialFocusEffect(
    useCallback(() => {
      /**
       * Verifica se há a necessidade de sincronizar e recarrega a lista
       */
      if (getShouldSync('payment-method')) {
        setSearch('');
        setShowSearch(false);
        getAllPaymentMethod();
      }
    }, [getAllPaymentMethod, getShouldSync]),
  );

  useEffect(() => {
    getAllPaymentMethod();
  }, [getAllPaymentMethod]);

  const handleSearch = useCallback(
    (value: string) => {
      setSearch(value);
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        getAllPaymentMethod(1, value);
      }, 400);
    },
    [getAllPaymentMethod],
  );

  const toggleSearch = useCallback(
    (show: boolean) => {
      if (!show && search !== '') {
        getAllPaymentMethod();
        setSearch('');
      }
      setShowSearch(show);
    },
    [getAllPaymentMethod, search],
  );

  return (
    <View style={styles.layout}>
      <Header withInsets variant="ghost">
        <HeaderAdornment>
          <HeaderButton icon="ChevronLeft" variant="outline" onPress={() => navigation.goBack()} />
          <HeaderButton icon={!showSearch ? 'Search' : 'X'} variant="ghost" onPress={() => toggleSearch(!showSearch)} />
        </HeaderAdornment>
        <HeaderContent>
          {showSearch ? (
            <InputText
              autoFocus
              style={styles.search}
              placeholder="Pesquisar..."
              value={search}
              onChangeText={handleSearch}
            />
          ) : (
            <HeaderTitle align="left">Mét. de Pagamento</HeaderTitle>
          )}
        </HeaderContent>
        <HeaderAdornment>
          <HeaderButton
            icon="Plus"
            variant="default"
            onPress={() => navigation.navigate('PaymentMethodForm', { paymentMethod: undefined })}
          />
        </HeaderAdornment>
      </Header>
      <KeyboardAvoidingContent>
        <FlatList
          scrollEventThrottle={16}
          initialNumToRender={10}
          removeClippedSubviews
          ref={listRef}
          maxToRenderPerBatch={5}
          onEndReachedThreshold={0}
          onEndReached={() => {
            if (isLoading || paginationLoading) return;
            hasMoreRef.current && !isLoading && getAllPaymentMethod(page.current + 1, search, true);
          }}
          refreshControl={<RefreshControl refreshing={loading} onRefresh={() => getAllPaymentMethod(1, '', false)} />}
          ListEmptyComponent={!isLoading ? <Empty title="Nenhum registro encontrado!" /> : null}
          contentContainerStyle={[styles.contentContainerList]}
          style={styles.layout}
          data={!isLoading ? paymentMethod : []}
          keyExtractor={(item) => item.id.toString()}
          renderItem={({ item: paymentMethod }) => {
            return (
              <MenuActions
                items={[
                  {
                    key: 'edit',
                    label: 'Editar',
                    icon: <Icon name="Pen" />,
                    shortcut: <Icon name="ChevronRight" />,
                    onPress: () => navigation.navigate('PaymentMethodForm', { paymentMethod: paymentMethod }),
                  },
                  {
                    key: 'delete',
                    label: 'Deletar',
                    icon: <Icon name="Trash" />,
                    shortcut: <Icon name="ChevronRight" />,
                    onPress: () =>
                      confirm.open({
                        title: 'Deseja realmente deletar o registro?',
                        variant: 'destructive',
                        onConfirm: () => deletePaymentMethod(paymentMethod),
                      }),
                  },
                ]}
              >
                <ItemPressable>
                  <ItemAdornment>
                    <Icon name="CreditCard" size={sizes.fontSize['2xl']} />
                  </ItemAdornment>
                  <ItemContent>
                    <ItemTitle numberOfLines={1}>{paymentMethod.name}</ItemTitle>
                    <ItemDescription numberOfLines={1}>{paymentMethod.installments}</ItemDescription>
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
                onPress={() => getAllPaymentMethod(page.current + 1, search, true)}
              >
                Carregar mais
              </Button>
            ) : null
          }
        />
      </KeyboardAvoidingContent>
    </View>
  );
};

export default PaymentMethod;
