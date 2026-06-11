(function () {
    "use strict";

    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    const overlay = document.getElementById("sidebarOverlay");

    let desktopCollapsed = false;

    const mobileQuery = window.matchMedia("(max-width: 768px)");

    function openMobileSidebar() {
        if (mobileQuery.matches) {
            sidebar.classList.add("mobile-open");
            overlay.classList.add("open");
            document.body.style.overflow = "hidden";
        }
    }

    function closeMobileSidebar() {
        if (mobileQuery.matches) {
            sidebar.classList.remove("mobile-open");
            overlay.classList.remove("open");
            document.body.style.overflow = "";
        }
    }

    function toggleSidebar() {
        if (mobileQuery.matches) {
            if (sidebar.classList.contains("mobile-open")) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        } else {
            desktopCollapsed = !desktopCollapsed;
            if (desktopCollapsed) {
                sidebar.classList.add("desktop-collapsed");
            } else {
                sidebar.classList.remove("desktop-collapsed");
            }
        }
    }

    function handleScreenChange(e) {
        if (e.matches) {
            console.log("📱 Mode mobile activé");
            sidebar.classList.remove("desktop-collapsed");
            overlay.classList.remove("open");
            sidebar.classList.remove("mobile-open");
            document.body.style.overflow = "";
        } else {
            console.log("🖥️ Mode desktop activé");
            sidebar.classList.remove("mobile-open");
            overlay.classList.remove("open");
            document.body.style.overflow = "";

            if (desktopCollapsed) {
                sidebar.classList.add("desktop-collapsed");
            } else {
                sidebar.classList.remove("desktop-collapsed");
            }
        }
    }

    mobileQuery.addEventListener("change", handleScreenChange);
    handleScreenChange(mobileQuery);

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener("click", closeMobileSidebar);
    }

    // CORRECTION ICI : Ne fermer le sidebar que pour les VRAIS liens de navigation
    // Exclure les boutons avec data-treeview-toggle (menus parents)
    const allNavLinks = document.querySelectorAll(".sidebar-menu .nav-link");
    allNavLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            // Vérifier si c'est un VRAI lien (a href) et non un bouton de menu
            const isRealLink = this.tagName === 'A' && this.getAttribute('href') !== '#';
            const isTreeviewToggle = this.hasAttribute("data-treeview-toggle");
            
            // Ne fermer le sidebar mobile que pour les vrais liens, pas pour les toggles
            if (
                isRealLink &&
                !isTreeviewToggle &&
                mobileQuery.matches &&
                sidebar.classList.contains("mobile-open")
            ) {
                setTimeout(closeMobileSidebar, 150);
            }
            
            // Gestion de l'état actif (pour les vrais liens uniquement)
            if (!isTreeviewToggle && isRealLink) {
                allNavLinks.forEach((l) => l.classList.remove("active"));
                this.classList.add("active");
            }
        });
    });

    function initTreeview() {
        const treeviewToggles = document.querySelectorAll(
            "[data-treeview-toggle]",
        );

        treeviewToggles.forEach((toggle) => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                const parentLi = this.closest(".nav-item");
                if (!parentLi) return;

                const submenu = parentLi.querySelector(
                    ":scope > .nav-treeview",
                );
                if (!submenu) return;

                const isExpanded =
                    this.getAttribute("aria-expanded") === "true";

                if (isExpanded) {
                    submenu.classList.remove("show");
                    this.setAttribute("aria-expanded", "false");
                } else {
                    submenu.classList.add("show");
                    this.setAttribute("aria-expanded", "true");
                }
            });
        });
    }

    initTreeview();

    // ============================================================
    // GESTION DES PRÉFÉRENCES D'ANIMATION
    // ============================================================
    const reducedMotionQuery = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    );
    function handleReducedMotion(e) {
        if (e.matches) {
            console.log("♿ Préférence : animations réduites");
            document.body.classList.add("reduced-motion");
        } else {
            document.body.classList.remove("reduced-motion");
        }
    }
    reducedMotionQuery.addEventListener("change", handleReducedMotion);
    handleReducedMotion(reducedMotionQuery);

    // ============================================================
    // GESTION DE L'ORIENTATION
    // ============================================================
    const orientationQuery = window.matchMedia("(orientation: landscape)");
    function handleOrientation(e) {
        if (e.matches) {
            console.log("Mode paysage");
            document.body.classList.add("landscape");
            document.body.classList.remove("portrait");
        } else {
            console.log("Mode portrait");
            document.body.classList.add("portrait");
            document.body.classList.remove("landscape");
        }
    }
    orientationQuery.addEventListener("change", handleOrientation);
    handleOrientation(orientationQuery);

    // ============================================================
    // DÉTECTION TACTILE
    // ============================================================
    const touchQuery = window.matchMedia("(pointer: coarse)");
    function handleTouch(e) {
        if (e.matches) {
            console.log("Appareil tactile détecté");
            document.body.classList.add("touch-device");
        } else {
            document.body.classList.remove("touch-device");
        }
    }
    touchQuery.addEventListener("change", handleTouch);
    handleTouch(touchQuery);

    console.log('Dashboard initialisé - Gestion du thème confiée à color-modes.js');
})();