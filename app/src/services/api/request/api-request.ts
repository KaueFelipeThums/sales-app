import { AxiosRequestConfig } from 'axios';
import { ApiResponse } from './api-request-types';
import api from '@/services/api/config/api-config';

/**
 * Configuração da requisição HTTP.
 *
 * @param {AxiosRequestConfig} config
 * @returns {Promise<ApiResponse<T>>}
 */
const apiRequest = async <T, TFields = string>(config: AxiosRequestConfig): Promise<ApiResponse<T, TFields>> => {
  try {
    const response = await api(config);
    return {
      success: true,
      data: response.data,
    };
  } catch (error: any) {
    if (error.response)
      if (error.response?.status === 401) {
        return {
          success: false,
          error: {
            code: 401,
            error: 'Unauthorized',
            message: 'Unauthorized',
            causes: [],
          },
        };
      }

    return {
      success: false,
      error: error.response,
    };
  }
};

export default apiRequest;
