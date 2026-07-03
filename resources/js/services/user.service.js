import { http, initCsrf } from '@/core';
import { routes, bindParams } from '@/data/routes';

export const userService = {
    getUsers() {
        return http.get(routes.apiUsers);
    },

    getUser(id) {
        return http.get(bindParams(routes.apiUser, { id }));
    },

    createUser(data) {
        return http.post(routes.users, data);
    },

    updateUser(id, data) {
        return http.put(bindParams(routes.usersEdit, { id }), data);
    },

    deleteUser(id) {
        return http.delete(bindParams(routes.usersShow, { id }));
    },
};