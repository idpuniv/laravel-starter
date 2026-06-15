export const routes = {
    
    categories: '/api/categories',
    category: '/api/categories/{id}',
    media: '/api/media/{id}',
};

export const bindParams = (url, params = {}) => {
    let result = url;
    Object.entries(params).forEach(([key, value]) => {
        result = result.replace(`{${key}}`, value);
    });
    return result;
};