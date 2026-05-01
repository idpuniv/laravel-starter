document.addEventListener('DOMContentLoaded', function() {
    function initPhoneInput(container) {
        const btns = container ? container.querySelectorAll('[id^="phone_btn_"]') : document.querySelectorAll('[id^="phone_btn_"]');
        
        btns.forEach(btn => {
            const btnId = btn.id;
            const dropdownItems = btn.closest('.input-group').querySelectorAll('.dropdown-item');
            
            dropdownItems.forEach(item => {
                item.removeEventListener('click', handleClick);
                item.addEventListener('click', handleClick);
            });
        });
    }
    
    function handleClick(e) {
        e.preventDefault();
        const iso2 = this.dataset.iso2;
        const code = this.dataset.code;
        const targetBtnId = this.dataset.target;
        const targetCodeInputId = this.dataset.codeInput;
        
        const btn = document.getElementById(targetBtnId);
        const codeInput = document.getElementById(targetCodeInputId);
        
        if (btn && codeInput) {
            codeInput.value = code;
            btn.innerHTML = '<span class="fi fi-' + iso2 + '"></span>';
        }
    }
    
    initPhoneInput();
    
    // Pour les instances ajoutées dynamiquement
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('phone-input-component')) {
                        initPhoneInput(node);
                    }
                });
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    }
});