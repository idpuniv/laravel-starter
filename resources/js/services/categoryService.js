// src/services/categoryService.js
import { http, initCsrf } from '@/core';
import { routes, bindParams } from '@/data/routes';

export const categoryService = {
    /** Fetch all categories */
    async getAll() {
        const response = await http.get(routes.categories);
        return response.data;
    },

    /** Fetch a single category by ID */
    async getById(id) {
        const url = bindParams(routes.category, { id });
        const response = await http.get(url);
        return response.data;
    },

    /** Create a new category */
    async create(data) {
        await initCsrf();
        const response = await http.post(routes.categories, data);
        return response.data;
    },

    /** Update an existing category */
    async update(id, data) {
        await initCsrf();
        const url = bindParams(routes.category, { id });
        const response = await http.put(url, data);
        return response.data;
    },

    /** Delete a category by ID */
    async delete(id) {
        await initCsrf();
        const url = bindParams(routes.category, { id }); // ← routes.category, pas routes.media
        const response = await http.delete(url);
        return response.data;
    },
};