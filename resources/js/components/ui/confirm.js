import { $ } from '../../core/dom.js';

export const alertModal = (message, options = {}) => {
    const defaults = {
        title: 'Confirmation',
        alertText: 'Confirmer',
        cancelText: 'Annuler',
        alertClass: 'btn-danger',
        onConfirm: null
    };

    const config = { ...defaults, ...options };

    // Mettre à jour la modal - utiliser les bons IDs
    const titleEl = document.getElementById('alertModalTitle');
    const messageEl = document.getElementById('alertModalMessage');
    const confirmBtn = document.getElementById('alertModalConfirm');
    const cancelBtn = document.getElementById('alertModalCancel');

    if (titleEl) titleEl.textContent = config.title;
    if (messageEl) messageEl.textContent = message;
    if (confirmBtn) {
        confirmBtn.textContent = config.alertText;
        confirmBtn.className = `btn ${config.alertClass}`;
    }
    if (cancelBtn) cancelBtn.textContent = config.cancelText;

    // Nettoyer les anciens événements
    const newConfirmBtn = confirmBtn?.cloneNode(true);
    if (newConfirmBtn && confirmBtn?.parentNode) {
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        newConfirmBtn.addEventListener('click', () => {
            if (config.onConfirm) config.onConfirm();
            const modal = bootstrap.Modal.getInstance(document.getElementById('alertModal'));
            if (modal) modal.hide();
        });
    }

    // Afficher la modal
    const modalElement = document.getElementById('alertModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
};