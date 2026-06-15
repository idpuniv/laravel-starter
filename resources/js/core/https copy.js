import axios from 'axios';

// CSRF token from Blade
const csrfToken = () => {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.content : null;
};

const http = axios.create({
    baseURL: '/',
    withCredentials: true, // 🔥 IMPORTANT POUR SANCTUM
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Inject CSRF token
const token = csrfToken();
if (token) {
    http.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

export default http;