import { AxiosRequestConfig } from 'axios';
import { ApiResponse } from './api-request-types';
import api from '@/services/api/config/api-config';

/**
 * Configuração da requisição HTTP.
 *
 * @param {AxiosRequestConfig} config
 * @returns {Promise<T>}
 */
const apiRequest = async <T>(config: AxiosRequestConfig): Promise<ApiResponse<T>> => {
  try {
    const response = await api(config);
    return {
      success: true,
      data: response.data,
    };
  } catch (error: any) {
    if (error.response) {
      return {
        success: false,
        error: error.response,
      };
    } else {
      return {
        success: false,
        error: {
          code: 0,
          message: 'Ocorreu um erro inesperado.',
        },
      };
    }
  }
};

export default apiRequest;
