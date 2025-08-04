import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
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

export { getAllCustomersRequest };
