import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { Sale } from '@/types/sale';

type PaginationProps = {
  search: string | null;
  page: number;
  page_count: number;
  customer_id: number | null;
  product_id: number | null;
};

const getAllSalesRequest = async (pagination: PaginationProps): Promise<ApiResponse<Sale[]>> => {
  const response = await apiRequest<Sale[]>({
    method: 'GET',
    url: 'v1/sale/get-all',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
      customer_id: pagination.customer_id,
      product_id: pagination.product_id,
    },
  });
  return response;
};

export { getAllSalesRequest };
