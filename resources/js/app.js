import './bootstrap';
import './color-modes';
import './custom-select';
import './datatable-manager';

// =============================================================================
// 1. COMPONENT IMPORTS (On-demand)
// =============================================================================
// All components are enabled.
// Comment out unused ones to improve tree-shaking and reduce bundle size.

import {
    Alert,       // Dismissible alerts (close button support)
    Button,      // Button states (toggle, loading, etc.)
    Carousel,    // Slideshow / carousel component
    Collapse,    // Required for responsive navbar (hamburger menu)
    Dropdown,    // Dropdown menus (user profile, actions, etc.)
    Modal,       // Modal dialogs (pop-up windows)
    Offcanvas,   // Offcanvas panels (sidebars, drawers)
    Popover,     // Click-triggered popups
    ScrollSpy,   // Updates navigation based on scroll position
    Tab,         // Tabbed interface management
    Toast,       // Toast notifications
    Tooltip      // Hover tooltips
} from 'bootstrap';

// =============================================================================
// 2. INITIALIZATION (Manual for certain components)
// =============================================================================

document.addEventListener('DOMContentLoaded', () => {
    
    // --- Tooltips ---
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(el => new Tooltip(el));

    // --- Popovers ---
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(el => new Popover(el));

    // --- Toasts ---
    const toastElList = document.querySelectorAll('.toast');
    const toastList = [...toastElList].map(el => new Toast(el));
    
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
    Tooltip 
};