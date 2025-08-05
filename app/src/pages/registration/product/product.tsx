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
import { useRegistrationNavigation } from '@/routes/private-routes/stacks/registration-stack-routes';
import { deleteProductRequest, getAllProductsRequest } from '@/services/api/product';
import type { Product as ProductType } from '@/types/product';
import { reducer } from '@/utils/reducer';

const productStyles = ({ sizes, colors }: ThemeValue) =>
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

const Product = () => {
  const styles = useStyles(productStyles);
  const confirm = usePopConfirm();
  const [hydrating, setHydrating] = useState<boolean>(true);
  const [loading, startTransition] = useTransition();
  const [paginationLoading, startPaginationTransition] = useTransition();
  const [product, dispatch] = useReducer(reducer, [] as ProductType[]);
  const { sizes } = useTheme();
  const page = useRef<number>(1);
  const listRef = useRef<FlatList<ProductType>>(null);
  const hasMoreRef = useRef<boolean>(true);
  const isLoading = loading || hydrating;
  const navigation = useRegistrationNavigation();
  const { getShouldSync, clearSync } = useSync();
  const [search, setSearch] = useState<string>('');
  const [showSearch, setShowSearch] = useState<boolean>(false);
  const debounceRef = useRef<NodeJS.Timeout | null>(null);

  const getAllProduct = useCallback(
    (pageToLoad: number = 1, search: string = '', append: boolean = false) => {
      const transition = append ? startPaginationTransition : startTransition;
      if (!append) {
        listRef.current?.scrollToOffset({ offset: 0, animated: false });
      }
      transition(async () => {
        const response = await getAllProductsRequest({
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
            clearSync('product');
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

  const deleteProduct = useCallback(
    (data: ProductType) => {
      startTransition(async () => {
        const response = await deleteProductRequest(data.id);
        if (response.success) {
          getAllProduct();
          toast.success({ title: 'Registro deletado com sucesso!' });
        } else {
          toast.error({ title: 'Ops, houve algum erro!', description: response.error?.message });
        }
      });
    },
    [getAllProduct],
  );

  useSkipInitialFocusEffect(
    useCallback(() => {
      /**
       * Verifica se há a necessidade de sincronizar e recarrega a lista
       */
      if (getShouldSync('product')) {
        setSearch('');
        setShowSearch(false);
        getAllProduct();
      }
    }, [getAllProduct, getShouldSync]),
  );

  useEffect(() => {
    getAllProduct();
  }, [getAllProduct]);

  const handleSearch = useCallback(
    (value: string) => {
      setSearch(value);
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        getAllProduct(1, value);
      }, 400);
    },
    [getAllProduct],
  );

  const toggleSearch = useCallback(
    (show: boolean) => {
      if (!show && search !== '') {
        setSearch('');
        getAllProduct();
      }
      setShowSearch(show);
    },
    [getAllProduct, search],
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
            <HeaderTitle align="left">Produtos</HeaderTitle>
          )}
        </HeaderContent>
        <HeaderAdornment>
          <HeaderButton
            icon="Plus"
            variant="default"
            onPress={() => navigation.navigate('ProductForm', { product: undefined })}
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
            hasMoreRef.current && !isLoading && getAllProduct(page.current + 1, search, true);
          }}
          refreshControl={<RefreshControl refreshing={loading} onRefresh={() => getAllProduct(1, '', false)} />}
          ListEmptyComponent={!isLoading ? <Empty title="Nenhum registro encontrado!" /> : null}
          contentContainerStyle={[styles.contentContainerList]}
          style={styles.layout}
          data={!isLoading ? product : []}
          keyExtractor={(item) => item.id.toString()}
          renderItem={({ item: product }) => {
            return (
              <MenuActions
                items={[
                  {
                    key: 'edit',
                    label: 'Editar',
                    icon: <Icon name="Pen" />,
                    shortcut: <Icon name="ChevronRight" />,
                    onPress: () => navigation.navigate('ProductForm', { product: product }),
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
                        onConfirm: () => deleteProduct(product),
                      }),
                  },
                ]}
              >
                <ItemPressable>
                  <ItemAdornment>
                    <Icon name="Package" size={sizes.fontSize['2xl']} />
                  </ItemAdornment>
                  <ItemContent>
                    <ItemTitle numberOfLines={1}>{product.name}</ItemTitle>
                    <ItemDescription numberOfLines={1}>{product.quantity}</ItemDescription>
                  </ItemContent>
                  <ItemAdornment>
                    <ItemAdornment>
                      <ItemDescription numberOfLines={1}>R$ {formaters.money(product.price, 2)}</ItemDescription>
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
                onPress={() => getAllProduct(page.current + 1, '', true)}
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

export default Product;
