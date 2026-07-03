const API_BASE_URL = import.meta.env.VITE_API_URL || '/api';

export const routes = {
    categories: `${API_BASE_URL}/categories`,
    category: `${API_BASE_URL}/categories/{id}`,
    settings: `${API_BASE_URL}/settings/update`,
    media: `${API_BASE_URL}/media/{id}`,
};

export const bindParams = (url, params = {}) => {
    let result = url;
    Object.entries(params).forEach(([key, value]) => {
        result = result.replace(`{${key}}`, value);
    });
    return result;
};