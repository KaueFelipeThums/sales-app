type ProductForm = {
  id: string;
  name: string;
  quantity: string;
  price: string;
  is_active: string;
};

type ProductCreateUpdate = {
  id: number;
  name: string;
  quantity: number;
  price: number;
  is_active: number;
};

export { ProductForm, ProductCreateUpdate };
