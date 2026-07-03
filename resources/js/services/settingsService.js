import http from '@/core/https';
import { routes } from '@/data/routes';

/**
 * Service de gestion des paramètres
 */
export const settingsService = {
    /**
     * Met à jour un paramètre
     * @param {string} key - La clé du paramètre
     * @param {string|number|boolean} value - La valeur du paramètre
     * @returns {Promise} Promesse contenant la réponse du serveur
     */
    update(key, value) {
        return http.post(routes.settings, {
            key: key,
            value: value
        });
    }
};

export default settingsService;