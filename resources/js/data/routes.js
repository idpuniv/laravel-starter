export const routes = {
    home: '/',
    login: '/login',
    register: '/register',
    dashboard: '/admin/dashboard',
    users: '/admin/users',
    usersCreate: '/admin/users/create',
    usersShow: '/admin/users/{id}',
    usersEdit: '/admin/users/{id}/edit',
    profile: '/profile',
    apiUsers: '/api/users',
    apiUser: '/api/users/{id}',
    
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