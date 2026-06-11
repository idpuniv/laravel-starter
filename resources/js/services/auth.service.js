import http from '@/core/https';
import { routes } from '@/data/routes';
import bus from '@/events/bus';
import { storage } from '@/core';

export const authService = {
    // Authenticate user and store session
    async login(email, password) {
        const response = await http.post(routes.login, { email, password });
        const user = response.data;
        
        storage.set('user', user);
        bus.emit('auth:login', user);
        
        return user;
    },

    // Clear session and redirect
    async logout() {
        await http.post('/logout');
        storage.remove('user');
        bus.emit('auth:logout');
        window.location.href = routes.login;
    },

    // Fetch current authenticated user
    async getUser() {
        const response = await http.get('/api/user');
        return response.data;
    },

    // Check if user is logged in
    isAuthenticated() {
        return !!storage.get('user');
    },
};