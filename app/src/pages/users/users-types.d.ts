type UsersForm = {
  id: string;
  name: string;
  email: string;
  password: string;
  is_active: string;
};

type UsersCreateUpdate = {
  id: number;
  name: string;
  email: string;
  password: string;
  is_active: number;
};

export { UsersForm, UsersCreateUpdate };
