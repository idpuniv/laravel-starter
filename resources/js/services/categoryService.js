import http from '@/core/https';
import { routes, bindParams } from '@/data/routes';

export const categoryService = {
    /**
     * Récupérer toutes les catégories
     */
    async getAll() {
        const response = await http.get(routes.categories);
        return response.data;
    },

    /**
     * Récupérer une catégorie par son ID
     */
    async getById(id) {
        const url = bindParams(routes.category, { id });
        const response = await http.get(url);
        return response.data;
    },

    /**
     * Créer une nouvelle catégorie
     */
    async create(data) {
        const response = await http.post(routes.categories, data);
        return response.data;
    },

    /**
     * Mettre à jour une catégorie
     */
    async update(id, data) {
        const url = bindParams(routes.category, { id });
        const response = await http.put(url, data);
        return response.data;
    },

    /**
     * Supprimer une catégorie
     */
    async delete(id) {
        const url = bindParams(routes.media, { id });
        const response = await http.delete(url);
        return response.data;
    }
};