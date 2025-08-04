type CustomerForm = {
  id: string;
  name: string;
  cpf: string;
  email: string;
  zip_code: string;
  street: string;
  number: string;
  complement: string;
  neighborhood: string;
  city: string;
  state: string;
  is_active: string;
};

type CustomerCreateUpdate = {
  id: number;
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
  is_active: number;
};

export { CustomerForm, CustomerCreateUpdate };
