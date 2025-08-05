import apiRequest from './request/api-request';
import { ApiResponse } from './request/api-request-types';
import { Login } from '@/pages/auth/login-types';
import { UpdatePassword } from '@/pages/update-password/update-password-types';
import { User } from '@/types/user';

const getUserRequest = async (): Promise<ApiResponse<User>> => {
  const response = await apiRequest<User>({
    method: 'GET',
    url: 'v1/auth/user',
  });
  return response;
};

type LoginResponse = {
  access_token: string;
  refresh_token: string;
  user: {
    id: number;
    name: string;
    email: string;
  };
};

const loginRequest = async (data: Login): Promise<ApiResponse<LoginResponse>> => {
  const response = await apiRequest<LoginResponse>({
    method: 'POST',
    url: 'v1/auth/login',
    data: data,
  });
  return response;
};

const updatePasswordRequest = async (
  data: Omit<UpdatePassword, 'new_password_confirmation'>,
): Promise<ApiResponse<unknown>> => {
  const response = await apiRequest<unknown>({
    method: 'POST',
    url: 'v1/auth/update-password',
    data: data,
  });
  return response;
};

type RefreshTokenResponse = {
  access_token: string;
  refresh_token: string;
};

const refreshTokenRequest = async (refreshToken: string): Promise<ApiResponse<RefreshTokenResponse>> => {
  const response = await apiRequest<RefreshTokenResponse>({
    method: 'POST',
    url: 'v1/auth/refresh',
    data: { refresh_token: refreshToken },
  });
  return response;
};

export { getUserRequest, loginRequest, updatePasswordRequest, refreshTokenRequest };
