import axios from 'axios';
import logger from '../logger';
import { getCookie } from './cookies';
import { initCsrf } from './csrf';

export const http = axios.create({
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

http.interceptors.request.use((config) => {
    const token = getCookie('XSRF-TOKEN');

    if (token) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
    }

    return config;
});

http.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error.response?.status === 419) {
            await initCsrf();
            return http.request(error.config);
        }

        logger.error('[HTTP]', error);

        return Promise.reject(error);
    }
);
