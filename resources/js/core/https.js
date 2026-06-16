import axios from 'axios';
import logger from './logger';

/**
 * Reads a cookie value by name.
 * Used to extract the XSRF-TOKEN set by Sanctum.
 */
const getCookie = (name) =>
    document.cookie
        .split('; ')
        .find((row) => row.startsWith(`${name}=`))
        ?.split('=')[1] ?? null;

/**
 * Fetches the CSRF cookie from Sanctum.
 * Must be called before any state-changing request (POST/PUT/PATCH/DELETE).
 */
export const initCsrf = () => axios.get('/sanctum/csrf-cookie');

// HTTP client configured for Sanctum SPA authentication
const http = axios.create({
    withCredentials: true, // sends session cookies on every request
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

// Inject XSRF-TOKEN from Sanctum cookie before each request
http.interceptors.request.use((config) => {
    const xsrf = getCookie('XSRF-TOKEN');
    if (xsrf) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrf);
    }
    return config;
});

// Global response error handler
http.interceptors.response.use(
    (response) => response,
    async (error) => {
        const status = error.response?.status;

        // Re-initialize CSRF and retry once on token mismatch
        if (status === 419) {
            await initCsrf();
            return http.request(error.config);
        }

        // Log only in dev — never in production
        logger.error('[http] Request failed:', status);

        return Promise.reject(error);
    }
);
export default http;