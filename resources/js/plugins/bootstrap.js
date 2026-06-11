// ============================================================================
// BOOTSTRAP COMPONENT IMPORTS
// ============================================================================

import {
    Alert,      // Dismissible alerts
    Button,     // Button states (toggle, loading)
    Carousel,   // Slideshow component
    Collapse,   // Responsive navbar hamburger menu
    Dropdown,   // Dropdown menus
    Modal,      // Modal dialogs
    Offcanvas,  // Offcanvas sidebars/drawers
    Popover,    // Click-triggered popups
    ScrollSpy,  // Navigation based on scroll position
    Tab,        // Tabbed interface
    Toast,      // Toast notifications
    Tooltip,    // Hover tooltips
} from "bootstrap";


// ============================================================================
// GLOBAL EXPOSURE
// ============================================================================

// Expose Bootstrap for inline scripts and Blade templates
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


// ============================================================================
// BOOTSTRAP INITIALIZATION
// ============================================================================


document.addEventListener("DOMContentLoaded", () => {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(el => new Tooltip(el));

    // Initialize popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].map(el => new Popover(el));

    // Initialize toasts
    const toastElList = document.querySelectorAll(".toast");
    [...toastElList].map(el => new Toast(el));

    // Note: Dropdown, Modal, Collapse auto-initialize via data attributes
});

document.querySelectorAll('.toast').forEach(toastEl => {
    new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 3000
    });
});