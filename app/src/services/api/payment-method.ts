import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { PaymentMethodCreateUpdate } from '@/pages/registration/payment-method/payment-method-types';
import { PaymentMethod } from '@/types/payment-method';

type PaginationProps = {
  search: string | null;
  page: number;
  page_count: number;
};

const getAllPaymentMethodsRequest = async (pagination: PaginationProps): Promise<ApiResponse<PaymentMethod[]>> => {
  const response = await apiRequest<PaymentMethod[]>({
    method: 'GET',
    url: 'v1/payment-method/get-all',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
    },
  });
  return response;
};

type CreatePaymentMethodProps = Omit<PaymentMethodCreateUpdate, 'id'>;

const createPaymentMethodRequest = async (data: CreatePaymentMethodProps): Promise<ApiResponse<PaymentMethod>> => {
  const response = await apiRequest<PaymentMethod>({
    method: 'POST',
    url: 'v1/payment-method/create',
    data: data,
  });
  return response;
};

type UpatePaymentMethodProps = PaymentMethodCreateUpdate;

const updatePaymentMethodRequest = async (date: UpatePaymentMethodProps): Promise<ApiResponse<PaymentMethod>> => {
  const response = await apiRequest<PaymentMethod>({
    method: 'POST',
    url: 'v1/payment-method/update',
    data: date,
  });
  return response;
};

const deletePaymentMethodRequest = async (id: number): Promise<ApiResponse<unknown>> => {
  const response = await apiRequest<unknown>({
    method: 'POST',
    url: 'v1/payment-method/delete',
    data: { id: id },
  });
  return response;
};

export {
  getAllPaymentMethodsRequest,
  createPaymentMethodRequest,
  updatePaymentMethodRequest,
  deletePaymentMethodRequest,
};
