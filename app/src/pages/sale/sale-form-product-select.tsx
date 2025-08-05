import React, { useEffect, useRef, useTransition } from 'react';
import { StyleSheet, View } from 'react-native';
import { CardSkeleton } from '@/components/layout/card-skeleton';
import { Empty } from '@/components/layout/empty';
import { Dialog, DialogBodyScroll, DialogContent, DialogHeader, DialogTrigger } from '@/core/components/ui/dialog';
import { Icon } from '@/core/components/ui/icon';
import { InputBaseAdornment } from '@/core/components/ui/input';
import { ItemAdornment, ItemContent, ItemDescription, ItemPressable, ItemTitle } from '@/core/components/ui/item';
import { Separator } from '@/core/components/ui/separator';
import { InputText } from '@/core/components/ui-presets/input-text';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';
import formaters from '@/functions/formaters';
import { getAllActiveProductsRequest } from '@/services/api/product';
import { Product } from '@/types/product';

const saleFormProductSelectStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    body: {
      gap: sizes.gap.none,
      padding: sizes.padding.none,
    },
    header: {
      paddingHorizontal: sizes.padding.sm,
      paddingVertical: sizes.padding.sm,
    },
    search: {
      borderWidth: 0,
      boxShadow: 'none',
    },
  });

const SkeletonList = () => {
  return (
    <View>
      {Array.from({ length: 2 }).map((_, index) => (
        <CardSkeleton key={`skeleton-${index}`} />
      ))}
    </View>
  );
};

type SaleFormProductSelectProps = {
  children: React.ReactNode;
  onProductSelect: (product: Product) => void;
  onOpenChange: (open: boolean) => void;
  open?: boolean;
};

const SaleFormProductSelect = ({ children, onOpenChange, onProductSelect, open }: SaleFormProductSelectProps) => {
  const debounceRef = useRef<NodeJS.Timeout | null>(null);
  const styles = useStyles(saleFormProductSelectStyles);
  const [products, setProducts] = React.useState<Product[]>([]);
  const [loadingProducts, startProductsTransition] = useTransition();

  const getAllProducts = React.useCallback((search: string) => {
    startProductsTransition(async () => {
      const response = await getAllActiveProductsRequest({ page: 1, page_count: 10, search });
      if (response.success) {
        setProducts(response.data);
      }
    });
  }, []);

  const handleProductSearch = React.useCallback(
    (value: string) => {
      if (debounceRef.current) {
        clearTimeout(debounceRef.current);
      }

      debounceRef.current = setTimeout(() => {
        getAllProducts(value);
      }, 400);
    },
    [getAllProducts],
  );

  useEffect(() => {
    getAllProducts('');
  }, [getAllProducts]);

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader style={styles.header}>
          <InputText
            style={styles.search}
            leftAdornment={
              <InputBaseAdornment>
                <Icon name="Search" />
              </InputBaseAdornment>
            }
            placeholder="Pesquisar..."
            onChangeText={handleProductSearch}
          />
        </DialogHeader>
        <Separator />
        <DialogBodyScroll contentContainerStyle={styles.body}>
          {loadingProducts && <SkeletonList />}
          {products.length === 0 && <Empty title="Nenhum produto encontrado!" />}
          {!loadingProducts &&
            products.map((product) => (
              <ItemPressable key={product.id} onPress={() => onProductSelect(product)}>
                <ItemAdornment>
                  <Icon name="Package" />
                </ItemAdornment>
                <ItemContent>
                  <ItemTitle numberOfLines={1}>{product.name}</ItemTitle>
                  <ItemDescription numberOfLines={1}>R$ {formaters.money(product.price)}</ItemDescription>
                </ItemContent>
              </ItemPressable>
            ))}
        </DialogBodyScroll>
      </DialogContent>
    </Dialog>
  );
};

export { SaleFormProductSelect };
