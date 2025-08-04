import { User } from './user';

type Customer = {
  id: number;
  users_id: number;
  name: string;
  cpf: string;
  email: string | null;
  zip_code: string | null;
  street: string | null;
  number: string | null;
  complement: string | null;
  neighborhood: string | null;
  city: string | null;
  state: string | null;
  is_active: 1 | 0;
  created_at: string;
  updated_at: string | null;
  user: User | null;
};
