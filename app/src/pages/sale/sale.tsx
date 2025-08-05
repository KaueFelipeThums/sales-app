import { format, parseISO } from 'date-fns';
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
import formaters from '@/functions/formaters';
import { useSkipInitialFocusEffect } from '@/hooks/use-skip-initial-focus-effect';
import { useSync } from '@/providers/sync/sync-provider';
import { useSaleNavigation } from '@/routes/private-routes/stacks/sale-stack-routes';
import { deleteSaleRequest, getAllSalesRequest } from '@/services/api/sale';
import type { Sale as SaleType } from '@/types/sale';
import { reducer } from '@/utils/reducer';

const saleStyles = ({ sizes, colors }: ThemeValue) =>
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

const Sale = () => {
  const styles = useStyles(saleStyles);
  const confirm = usePopConfirm();
  const [hydrating, setHydrating] = useState<boolean>(true);
  const [loading, startTransition] = useTransition();
  const [paginationLoading, startPaginationTransition] = useTransition();
  const [sale, dispatch] = useReducer(reducer, [] as SaleType[]);
  const { sizes } = useTheme();
  const page = useRef<number>(1);
  const listRef = useRef<FlatList<SaleType>>(null);
  const hasMoreRef = useRef<boolean>(true);
  const isLoading = loading || hydrating;
  const navigation = useSaleNavigation();
  const { getShouldSync, clearSync } = useSync();
  const [search, setSearch] = useState<string>('');
  const [showSearch, setShowSearch] = useState<boolean>(false);
  const debounceRef = useRef<NodeJS.Timeout | null>(null);

  const getAllSale = useCallback(
    (pageToLoad: number = 1, search: string = '', append: boolean = false) => {
      const transition = append ? startPaginationTransition : startTransition;
      if (!append) {
        listRef.current?.scrollToOffset({ offset: 0, animated: false });
      }
      transition(async () => {
        const response = await getAllSalesRequest({
          search: search,
          page: pageToLoad,
          page_count: 10,
          customers_id: null,
        });

        if (response.success) {
          dispatch({ type: append ? 'CONCAT' : 'LOAD', payload: response.data });
          hasMoreRef.current = response.data.length == 10;
          page.current = pageToLoad;
          /**
           * Limpa os dados de sincronização
           */
          if (!append) {
            clearSync('sale');
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

  const deleteSale = useCallback(
    (data: SaleType) => {
      startTransition(async () => {
        const response = await deleteSaleRequest(data.id);
        if (response.success) {
          getAllSale();
          toast.success({ title: 'Registro deletado com sucesso!' });
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [getAllSale],
  );

  useSkipInitialFocusEffect(
    useCallback(() => {
      /**
       * Verifica se há a necessidade de sincronizar e recarrega a lista
       */
      if (getShouldSync('sale')) {
        setSearch('');
        setShowSearch(false);
        getAllSale();
      }
    }, [getAllSale, getShouldSync]),
  );

  useEffect(() => {
    getAllSale();
  }, [getAllSale]);

  const handleSearch = useCallback(
    (value: string) => {
      setSearch(value);
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        getAllSale(1, value);
      }, 400);
    },
    [getAllSale],
  );

  const toggleSearch = useCallback(
    (show: boolean) => {
      if (!show && search !== '') {
        setSearch('');
        getAllSale();
      }
      setShowSearch(show);
    },
    [getAllSale, search],
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
            <HeaderTitle align="left">Vendas</HeaderTitle>
          )}
        </HeaderContent>
        <HeaderAdornment>
          <HeaderButton
            icon="Plus"
            variant="default"
            onPress={() => navigation.navigate('SaleForm', { sale: undefined })}
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
            hasMoreRef.current && !isLoading && getAllSale(page.current + 1, search, true);
          }}
          refreshControl={<RefreshControl refreshing={loading} onRefresh={() => getAllSale(1, '', false)} />}
          ListEmptyComponent={!isLoading ? <Empty title="Nenhum registro encontrado!" /> : null}
          contentContainerStyle={[styles.contentContainerList]}
          style={styles.layout}
          data={!isLoading ? sale : []}
          keyExtractor={(item) => item.id.toString()}
          renderItem={({ item: sale }) => {
            return (
              <MenuActions
                items={[
                  {
                    key: 'details',
                    label: 'Detalhes',
                    icon: <Icon name="ListCollapse" />,
                    shortcut: <Icon name="ChevronRight" />,
                    onPress: () => navigation.navigate('SaleForm', { sale: sale }),
                  },
                  {
                    key: 'edit',
                    label: 'Editar',
                    icon: <Icon name="Pen" />,
                    shortcut: <Icon name="ChevronRight" />,
                    onPress: () => navigation.navigate('SaleForm', { sale: sale }),
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
                        onConfirm: () => deleteSale(sale),
                      }),
                  },
                ]}
              >
                <ItemPressable>
                  <ItemAdornment>
                    <Icon name="ShoppingCart" size={sizes.fontSize['2xl']} />
                  </ItemAdornment>
                  <ItemContent>
                    <ItemTitle numberOfLines={1}>{sale.customer.name}</ItemTitle>
                    <ItemDescription numberOfLines={1}>
                      {format(parseISO(sale.created_at), 'dd/MM/yyyy')}
                    </ItemDescription>
                  </ItemContent>
                  <ItemAdornment>
                    <ItemAdornment>
                      <ItemDescription numberOfLines={1}>R$ {formaters.money(sale.total_value, 2)}</ItemDescription>
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
                onPress={() => getAllSale(page.current + 1, '', true)}
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

export default Sale;
