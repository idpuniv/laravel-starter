import http from '@/core/https';
import { routes, bindParams } from '@/data/routes';

export const mediaService = {
    /**
     * Supprimer un média
     */
    async delete(mediaId) {
        const url = bindParams(routes.mediaDelete, { id: mediaId });
        const response = await http.delete(url);
        return response.data;
    }
};