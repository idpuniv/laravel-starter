import axios from 'axios';

// Retrieves CSRF token from meta tag
const csrfToken = () => {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.content : null;
};

// HTTP client instance with default headers
const http = axios.create({
    baseURL: '/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Inject CSRF token into all requests
const token = csrfToken();
if (token) {
    http.defaults.headers.common['X-CSRF-TOKEN'] = token;
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

export default http;