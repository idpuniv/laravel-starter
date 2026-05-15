import "./bootstrap";
import "./color-modes";
import "./custom-select";
import "./datatable-manager";
import "./phone-input";

// =============================================================================
// 1. COMPONENT IMPORTS (On-demand)
// =============================================================================
// All components are enabled.
// Comment out unused ones to improve tree-shaking and reduce bundle size.

import {
    Alert, // Dismissible alerts (close button support)
    Button, // Button states (toggle, loading, etc.)
    Carousel, // Slideshow / carousel component
    Collapse, // Required for responsive navbar (hamburger menu)
    Dropdown, // Dropdown menus (user profile, actions, etc.)
    Modal, // Modal dialogs (pop-up windows)
    Offcanvas, // Offcanvas panels (sidebars, drawers)
    Popover, // Click-triggered popups
    ScrollSpy, // Updates navigation based on scroll position
    Tab, // Tabbed interface management
    Toast, // Toast notifications
    Tooltip, // Hover tooltips
} from "bootstrap";

// =============================================================================
// 2. INITIALIZATION (Manual for certain components)
// =============================================================================

document.addEventListener("DOMContentLoaded", () => {
    // --- Tooltips ---
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]',
    );
    const tooltipList = [...tooltipTriggerList].map((el) => new Tooltip(el));

    // --- Popovers ---
    const popoverTriggerList = document.querySelectorAll(
        '[data-bs-toggle="popover"]',
    );
    const popoverList = [...popoverTriggerList].map((el) => new Popover(el));

    // --- Toasts ---
    const toastElList = document.querySelectorAll(".toast");
    const toastList = [...toastElList].map((el) => new Toast(el));

    // Note:
    // Dropdown, Modal, and Collapse are automatically initialized
    // via Bootstrap data attributes (no manual JS required).
});

// =============================================================================
// 3. GLOBAL EXPOSURE (Useful for inline scripts / Blade templates)
// =============================================================================
window.bootstrap = {
    Alert,
    Button,
    Carousel,
    Collapse,
    Dropdown,
    Modal,
    Offcanvas,
    Popover,
    ScrollSpy,
    Tab,
    Toast,
    Tooltip,
};

document.addEventListener('show.bs.modal', event => {
    console.log('1 - show.bs.modal déclenché');
    
    const modal = event.target;
    if (modal.id !== 'confirmModal') return;
    
    console.log('2 - Modal confirmModal OK');
    
    const button = event.relatedTarget;
    console.log('3 - Bouton:', button);
    
    if (!button) return;
    
    const confirmForm = document.getElementById('confirmForm');
    const actionUrl = button.getAttribute('data-url');
    const method = button.getAttribute('data-method') || 'POST';
    
    confirmForm.setAttribute('action', actionUrl);
    
    const existingMethodInput = confirmForm.querySelector('input[name="_method"]');
    if (existingMethodInput) existingMethodInput.remove();
    
    if (method.toUpperCase() !== 'POST') {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = method.toUpperCase();
        confirmForm.appendChild(methodInput);
    }
});


document.querySelectorAll('.toast').forEach(toast => {
    new bootstrap.Toast(toast, { autohide: true, delay: 3000 }).show();
});