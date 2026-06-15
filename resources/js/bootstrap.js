// import axios from 'axios';
// window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


import axios from 'axios';
import { initCsrf } from '@/core/https';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Establish Sanctum SPA session once at boot — required before any API GET
initCsrf();