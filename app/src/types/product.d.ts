import { User } from './user';

type Product = {
  id: number;
  users_id: number;
  name: string;
  quantity: number;
  price: number;
  is_active: 1 | 0;
  created_at: string;
  updated_at: string | null;
  user: User | null;
};

export { Product };
