import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { ProductCreateUpdate } from '@/pages/registration/product/product-types';
import { Product } from '@/types/product';

type PaginationProps = {
  search: string | null;
  page: number;
  page_count: number;
};

const getAllProductsRequest = async (pagination: PaginationProps): Promise<ApiResponse<Product[]>> => {
  const response = await apiRequest<Product[]>({
    method: 'GET',
    url: 'v1/product/get-all',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
    },
  });
  return response;
};

const getAllActiveProductsRequest = async (pagination: PaginationProps): Promise<ApiResponse<Product[]>> => {
  const response = await apiRequest<Product[]>({
    method: 'GET',
    url: 'v1/product/get-all-active',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
    },
  });
  return response;
};

type CreateProductProps = Omit<ProductCreateUpdate, 'id'>;

const createProductRequest = async (data: CreateProductProps): Promise<ApiResponse<Product>> => {
  const response = await apiRequest<Product>({
    method: 'POST',
    url: 'v1/product/create',
    data: data,
  });
  return response;
};

type UpateProductProps = ProductCreateUpdate;

const updateProductRequest = async (date: UpateProductProps): Promise<ApiResponse<Product>> => {
  const response = await apiRequest<Product>({
    method: 'POST',
    url: 'v1/product/update',
    data: date,
  });
  return response;
};

const deleteProductRequest = async (id: number): Promise<ApiResponse<unknown>> => {
  const response = await apiRequest<unknown>({
    method: 'POST',
    url: 'v1/product/delete',
    data: { id: id },
  });
  return response;
};

export {
  getAllProductsRequest,
  createProductRequest,
  updateProductRequest,
  deleteProductRequest,
  getAllActiveProductsRequest,
};
