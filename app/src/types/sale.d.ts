import { Customer } from './customer';
import { PaymentMethod } from './payment-method';
import { Product } from './product';
import { User } from './user';

type Sale = {
  id: number;
  payment_method_id: number;
  users_id: number;
  product_id: number;
  customer_id: number;
  quantity: number;
  total_value: number;
  base_value: number;
  created_at: string;
  canceled_at: string | null;
  updated_at: string | null;
  payment_method: PaymentMethod;
  customer: Customer;
  product: Product;
  user: User | null;
};
