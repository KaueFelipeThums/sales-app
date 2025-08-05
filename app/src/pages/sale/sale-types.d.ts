type SaleProductsForm = {
  products_id: string;
  quantity: string;
  products_quantity: number;
  products_price: number;
  products_name: string;
};

type SaleForm = {
  id: string;
  payment_methods_id: string;
  customers_id: string;
  products: SaleProductsForm[];
};

type SaleProductsCreateUpdate = {
  products_id: number;
  quantity: number;
};

type SaleCreateUpdate = {
  id: number;
  payment_methods_id: number;
  customers_id: number;
  products: SaleProductsCreateUpdate[];
};

export { SaleForm, SaleCreateUpdate };
