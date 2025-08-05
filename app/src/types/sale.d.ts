import { Customer } from './customer';
import { PaymentMethod } from './payment-method';
import { User } from './user';

type Sale = {
  id: number;
  payment_methods_id: number;
  users_id: number;
  customers_dd: number;
  total_value: number;
  created_at: string;
  updated_at: string | null;
  payment_method: PaymentMethod;
  customer: Customer;
  user: User | null;
};
