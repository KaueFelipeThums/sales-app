import axios from 'axios';
import { refreshTokenRequest } from '../auth';
import env from '@/config/env/env-config';
import { storage } from '@/core/utils/local-storage';

const API_BACKEND = env.API_BACKEND;

const api = axios.create({
  baseURL: API_BACKEND,
});

let isRefreshing = false;
let failedQueue: any[] = [];

const processQueue = (error: any, token: string | null = null) => {
  failedQueue.forEach((prom) => {
    if (token) {
      prom.resolve(token);
    } else {
      prom.reject(error);
    }
  });

  failedQueue = [];
};

api.interceptors.request.use(
  async (config) => {
    const authToken: string | null = await storage.get('sales-app-auth-token');
    if (authToken) config.headers.Authorization = `Bearer ${authToken}`;
    return config;
  },
  (error) => Promise.reject(error),
);

api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;

      const refreshToken = await storage.get('sales-app-refresh-token');

      if (!refreshToken) {
        await storage.remove('sales-app-user');
        await storage.remove('sales-app-auth-token');
        await storage.remove('sales-app-refresh-token');
        return Promise.reject(error);
      }

      if (isRefreshing) {
        // Aguarda o refresh atual terminar
        return new Promise(function (resolve, reject) {
          failedQueue.push({
            resolve: (token: string) => {
              originalRequest.headers.Authorization = `Bearer ${token}`;
              resolve(api(originalRequest));
            },
            reject: (err: any) => {
              reject(err);
            },
          });
        });
      }

      isRefreshing = true;

      try {
        const response = await refreshTokenRequest(refreshToken);
        if (response.success) {
          const newAccessToken = response.data.access_token;
          const newRefreshToken = response.data.refresh_token;

          await storage.set('sales-app-auth-token', newAccessToken);
          await storage.set('sales-app-refresh-token', newRefreshToken);

          processQueue(null, newAccessToken);

          originalRequest.headers.Authorization = `Bearer ${newAccessToken}`;
          return api(originalRequest);
        } else {
          processQueue(error, null);
          throw error;
        }
      } catch (err) {
        processQueue(err, null);

        await storage.remove('sales-app-user');
        await storage.remove('sales-app-auth-token');
        await storage.remove('sales-app-refresh-token');

        return Promise.reject(err);
      } finally {
        isRefreshing = false;
      }
    }

    return Promise.reject(error);
  },
);

export default api;
