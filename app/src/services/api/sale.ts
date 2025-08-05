import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { SaleCreateUpdate } from '@/pages/sale/sale-types';
import { Sale } from '@/types/sale';

type PaginationProps = {
  search: string | null;
  page: number;
  page_count: number;
  customers_id: number | null;
};

const getAllSalesRequest = async (pagination: PaginationProps): Promise<ApiResponse<Sale[]>> => {
  const response = await apiRequest<Sale[]>({
    method: 'GET',
    url: 'v1/sale/get-all',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
      customers_id: pagination.customers_id,
    },
  });
  return response;
};

type CreateSaleProps = Omit<SaleCreateUpdate, 'id'>;

const createSaleRequest = async (data: CreateSaleProps): Promise<ApiResponse<Sale>> => {
  const response = await apiRequest<Sale>({
    method: 'POST',
    url: 'v1/sale/create',
    data: data,
  });
  return response;
};

type UpateSaleProps = SaleCreateUpdate;

const updateSaleRequest = async (date: UpateSaleProps): Promise<ApiResponse<Sale>> => {
  const response = await apiRequest<Sale>({
    method: 'POST',
    url: 'v1/sale/update',
    data: date,
  });
  return response;
};

const deleteSaleRequest = async (id: number): Promise<ApiResponse<unknown>> => {
  const response = await apiRequest<unknown>({
    method: 'POST',
    url: 'v1/sale/delete',
    data: { id: id },
  });
  return response;
};

export { getAllSalesRequest, createSaleRequest, updateSaleRequest, deleteSaleRequest };
