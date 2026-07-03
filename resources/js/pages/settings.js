import { settingsService } from '@/services/settingsService';
import logger from '@/core/logger';
import events from '@/core/events';
import { flash } from '@/components/ui/flash';

document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.settings-auto-save').forEach(element => {
        const eventType = (element.type === 'checkbox' || element.type === 'select') ? 'change' : 'input';
        
        element.addEventListener(eventType, function() {
            const key = this.dataset.key;
            let value;

            if (this.type === 'checkbox') {
                value = this.checked ? '1' : '0';
            } else {
                value = this.value;
            }

            settingsService.update(key, value)
                .then(response => {
                    logger.log(`Paramètre ${key} mis à jour:`, value);
                    events.emit('setting:updated', { key, value, response });
                    
                    // Flash de succès
                    const toast = document.getElementById('saveToast');
                    if (toast) {
                        const bsToast = new bootstrap.Toast(toast, { delay: 2000 });
                        bsToast.show();
                    }
                })
                .catch(error => {
                    logger.error(`[settings] Erreur lors de la mise à jour de ${key}:`, error);
                    events.emit('setting:error', { key, value, error });
                    
                    // Flash d'erreur
                    const toast = document.getElementById('errorToast');
                    if (toast) {
                        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
                        bsToast.show();
                    }
                });
        });
    });

    events.on('setting:updated', ({ key, value }) => {
        logger.log(`[settings] ${key} = ${value} sauvegardé avec succès`);
    });

    events.on('setting:error', ({ key, error }) => {
        logger.error(`[settings] Erreur pour ${key}:`, error);
    });
});