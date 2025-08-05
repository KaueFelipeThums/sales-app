import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
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

export { getAllSalesRequest };
