import { StyleSheet } from 'react-native';
import { Card } from '@/core/components/ui/card';
import { Item, ItemAdornment, ItemContent } from '@/core/components/ui/item';
import { Skeleton } from '@/core/components/ui/skeleton';
import { useStyles } from '@/core/theme/hooks/use-styles';
import { ThemeValue } from '@/core/theme/theme-provider/theme-provider-types';

const cardSekeletonStyles = ({ sizes }: ThemeValue) =>
  StyleSheet.create({
    descriptionSkeleton: {
      borderRadius: sizes.radius.default,
      height: sizes.dimension.xs,
      width: '100%',
    },
    iamgeSkeleton: {
      alignItems: 'center',
      borderRadius: sizes.radius.full,
      height: sizes.dimension.md,
      width: sizes.dimension.md,
    },
    titleSkeleton: {
      borderRadius: sizes.radius.default,
      height: sizes.dimension.xs,
      width: '70%',
    },
  });

const CardSkeleton = ({ style, ...props }: React.ComponentPropsWithRef<typeof Card>) => {
  const styles = useStyles(cardSekeletonStyles);

  return (
    <Item style={style} {...props}>
      <ItemAdornment>
        <Skeleton style={styles.iamgeSkeleton} />
      </ItemAdornment>
      <ItemContent>
        <Skeleton style={styles.titleSkeleton} />
        <Skeleton style={styles.descriptionSkeleton} />
      </ItemContent>
    </Item>
  );
};

export { CardSkeleton };
