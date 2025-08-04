type User = {
  id: number;
  name: string;
  email: string;
  password: string | null;
  is_active: 1 | 0;
  created_at: string;
  updated_at: string | null;
};

export { User };
