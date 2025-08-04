import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { UsersCreateUpdate } from '@/pages/users/users-types';
import { User } from '@/types/user';

type PaginationProps = {
  search: string | null;
  page: number;
  page_count: number;
};

const getAllUsersRequest = async (pagination: PaginationProps): Promise<ApiResponse<User[]>> => {
  const response = await apiRequest<User[]>({
    method: 'GET',
    url: 'v1/user/get-all',
    params: {
      search: pagination.search,
      page: pagination.page,
      page_count: pagination.page_count,
    },
  });
  return response;
};

type CreateUserProps = Omit<UsersCreateUpdate, 'id'>;

const createUserRequest = async (data: CreateUserProps): Promise<ApiResponse<User>> => {
  const response = await apiRequest<User>({
    method: 'POST',
    url: 'v1/user/create',
    data: data,
  });
  return response;
};

type UpateUserProps = UsersCreateUpdate;

const updateUserRequest = async (date: UpateUserProps): Promise<ApiResponse<User>> => {
  const response = await apiRequest<User>({
    method: 'POST',
    url: 'v1/user/update',
    data: date,
  });
  return response;
};

const deleteUserRequest = async (id: number): Promise<ApiResponse<unknown>> => {
  const response = await apiRequest<unknown>({
    method: 'POST',
    url: 'v1/user/delete',
    data: { id: id },
  });
  return response;
};

export { getAllUsersRequest, createUserRequest, updateUserRequest, deleteUserRequest };
