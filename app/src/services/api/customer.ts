import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { CustomerCreateUpdate } from '@/pages/registration/customer/customer-types';
import { Customer } from '@/types/customer';

type PaginationProps = {
  search: string | null;
  page: number;
  page_count: number;
};

const getAllCustomersRequest = async (pagination: PaginationProps): Promise<ApiResponse<Customer[]>> => {
  const response = await apiRequest<Customer[]>({
    method: 'GET',
    url: 'v1/customer/get-all',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
    },
  });
  return response;
};

type CreateCustomerProps = Omit<CustomerCreateUpdate, 'id'>;

const createCustomerRequest = async (data: CreateCustomerProps): Promise<ApiResponse<Customer>> => {
  const response = await apiRequest<Customer>({
    method: 'POST',
    url: 'v1/customer/create',
    data: data,
  });
  return response;
};

type UpateCustomerProps = CustomerCreateUpdate;

const updateCustomerRequest = async (date: UpateCustomerProps): Promise<ApiResponse<Customer>> => {
  const response = await apiRequest<Customer>({
    method: 'POST',
    url: 'v1/customer/update',
    data: date,
  });
  return response;
};

const deleteCustomerRequest = async (id: number): Promise<ApiResponse<unknown>> => {
  const response = await apiRequest<unknown>({
    method: 'POST',
    url: 'v1/customer/delete',
    data: { id: id },
  });
  return response;
};

export { getAllCustomersRequest, createCustomerRequest, updateCustomerRequest, deleteCustomerRequest };
