import { User } from './user';

type PaymentMethod = {
  id: number;
  users_id: number;
  name: string;
  installments: number;
  is_active: 1 | 0;
  created_at: string;
  updated_at: string | null;
  user: User | null;
};

export { PaymentMethod };
