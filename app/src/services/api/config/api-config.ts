import axios from 'axios';
import env from '@/config/env/env-config';
import { storage } from '@/core/utils/local-storage';

const API_BACKEND = env.API_BACKEND;

const api = axios.create({
  baseURL: API_BACKEND,
});

api.interceptors.request.use(
  async (config) => {
    const authToken: string | null = await storage.get('sales-app-auth-token');
    if (authToken) config.headers.Authorization = `Bearer ${authToken}`;
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);

api.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response && error.response.status === 401) {
      await storage.remove('sales-app-user');
      await storage.remove('sales-app-auth-token');
    }
    return Promise.reject(error);
  },
);

export default api;
