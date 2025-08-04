type PaymentMethodForm = {
  id: string;
  name: string;
  installments: string;
  is_active: string;
};

type PaymentMethodCreateUpdate = {
  id: number;
  name: string;
  installments: number;
  is_active: number;
};

export { PaymentMethodForm, PaymentMethodCreateUpdate };
