// ============================================================================
// CONFIRM MODAL HANDLER
// ============================================================================

// Populate confirm modal form with trigger data
document.addEventListener('show.bs.modal', function (e) {
    if (e.target.id !== 'confirmModal') return;

    const trigger = e.relatedTarget;
    const url = trigger?.dataset?.url ?? '';
    const method = trigger?.dataset?.method ?? 'DELETE';
    const form = document.getElementById('confirmForm');

    if (!form) return;

    form.action = url;
    
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.value = method;
    }
});