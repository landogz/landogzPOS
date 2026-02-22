/**
 * PharmaPOS / Landogz POS - API clients
 * POS terminals use localApi (LAN). Dashboard can use cloudApi.
 */

import axios from 'axios';

const LOCAL_API = import.meta.env.VITE_LOCAL_API_URL || 'http://localhost:8000/api/v1';
const CLOUD_API = import.meta.env.VITE_CLOUD_API_URL || 'https://yourdomain.com/api/v1';

export const localApi = axios.create({
  baseURL: LOCAL_API,
  timeout: 5000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    'X-Node-Type': 'pos-terminal',
  },
});

export const cloudApi = axios.create({
  baseURL: CLOUD_API,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    'X-Node-Type': 'cloud-client',
  },
});

const attachAuth = (instance) => {
  instance.interceptors.request.use((config) => {
    const token = localStorage.getItem('auth_token');
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  });

  instance.interceptors.response.use(
    (res) => res,
    (err) => {
      if (err.response?.status === 401) {
        if (typeof window !== 'undefined') window.location.href = '/login';
      }
      if (!err.response && typeof window !== 'undefined') {
        window.dispatchEvent(new Event('pharmapos:offline'));
      }
      return Promise.reject(err);
    }
  );
};

[localApi, cloudApi].forEach(attachAuth);
