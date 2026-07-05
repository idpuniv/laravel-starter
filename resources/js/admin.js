(function () {
    "use strict";

    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggleBtn");
    const overlay = document.getElementById("sidebarOverlay");
    const hideBtn = document.getElementById("sidebarHideBtn");

    // Fallback matches $dashboard-breakpoint-md in case the custom
    // property is missing or fails to parse (e.g. CSS not yet loaded).
    const FALLBACK_BREAKPOINT_MD = 768;

    function readBreakpointMd() {
        const raw = getComputedStyle(document.documentElement)
            .getPropertyValue("--dashboard-breakpoint-md")
            .trim();
        const parsed = parseInt(raw, 10);
        return Number.isNaN(parsed) ? FALLBACK_BREAKPOINT_MD : parsed;
    }

    let sidebarCollapsed = false;
    let sidebarHidden = false;

    // Single source of truth for the breakpoint lives in SCSS
    // ($dashboard-breakpoint-md) — this only reads it.
    const belowBreakpointQuery = window.matchMedia(
        `(max-width: ${readBreakpointMd()}px)`,
    );

    function openOffcanvasSidebar() {
        if (belowBreakpointQuery.matches) {
            sidebar.classList.add("sidebar-offcanvas-open");
            overlay.classList.add("open");
            document.body.classList.add("sidebar-offcanvas-locked");
        }
    }

    function closeOffcanvasSidebar() {
        if (belowBreakpointQuery.matches) {
            sidebar.classList.remove("sidebar-offcanvas-open");
            overlay.classList.remove("open");
            document.body.classList.remove("sidebar-offcanvas-locked");
        }
    }

    // Fully hidden state — independent from the rail-collapse toggle,
    // desktop only (offcanvas already achieves "fully gone" on mobile).
    function hideSidebar() {
        if (belowBreakpointQuery.matches) {
            return;
        }
        sidebarHidden = true;
        sidebar.classList.add("sidebar-hidden");
        document.body.classList.add("sidebar-hidden-layout");
    }

    function showSidebar() {
        sidebarHidden = false;
        sidebar.classList.remove("sidebar-hidden");
        document.body.classList.remove("sidebar-hidden-layout");
    }

    function toggleSidebar() {
        if (belowBreakpointQuery.matches) {
            if (sidebar.classList.contains("sidebar-offcanvas-open")) {
                closeOffcanvasSidebar();
            } else {
                openOffcanvasSidebar();
            }
            return;
        }

        // Bringing the menu back is the more intuitive action here than
        // toggling a collapse state the user cannot currently see.
        if (sidebarHidden) {
            showSidebar();
            return;
        }

        sidebarCollapsed = !sidebarCollapsed;
        sidebar.classList.toggle("sidebar-collapsed", sidebarCollapsed);
    }

    function handleBreakpointChange(e) {
        if (e.matches) {
            // Below breakpoint: offcanvas mode. Collapsed/hidden desktop
            // states are meaningless here and must not linger — otherwise
            // resizing back down could leave the sidebar permanently gone
            // with no offcanvas trigger able to reveal it.
            sidebar.classList.remove("sidebar-collapsed");
            sidebar.classList.remove("sidebar-hidden");
            document.body.classList.remove("sidebar-hidden-layout");
            overlay.classList.remove("open");
            sidebar.classList.remove("sidebar-offcanvas-open");
            document.body.classList.remove("sidebar-offcanvas-locked");
        } else {
            // Above breakpoint: offcanvas state is meaningless, restore
            // the persisted collapsed/hidden preference instead.
            sidebar.classList.remove("sidebar-offcanvas-open");
            overlay.classList.remove("open");
            document.body.classList.remove("sidebar-offcanvas-locked");
            sidebar.classList.toggle("sidebar-collapsed", sidebarCollapsed);
            sidebar.classList.toggle("sidebar-hidden", sidebarHidden);
            document.body.classList.toggle("sidebar-hidden-layout", sidebarHidden);
        }
    }

    belowBreakpointQuery.addEventListener("change", handleBreakpointChange);
    handleBreakpointChange(belowBreakpointQuery);

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function (e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener("click", closeOffcanvasSidebar);
    }

    if (hideBtn) {
        hideBtn.addEventListener("click", hideSidebar);
    }

    // Only elements explicitly marked as treeview toggles are parent-menu
    // buttons; everything else is treated as a real navigation link. This
    // avoids misclassifying anchors that use href="#" as a placeholder.
    const allNavLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    allNavLinks.forEach((link) => {
        link.addEventListener("click", function () {
            const isTreeviewToggle = this.hasAttribute("data-treeview-toggle");

            if (isTreeviewToggle) {
                return;
            }

            if (
                belowBreakpointQuery.matches &&
                sidebar.classList.contains("sidebar-offcanvas-open")
            ) {
                setTimeout(closeOffcanvasSidebar, 150);
            }

            allNavLinks.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
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

                submenu.classList.toggle("show", !isExpanded);
                this.setAttribute("aria-expanded", String(!isExpanded));
            });
        });
    }

    initTreeview();

    // ============================================================
    // ORIENTATION
    // ============================================================
    const orientationQuery = window.matchMedia("(orientation: landscape)");
    function handleOrientation(e) {
        document.body.classList.toggle("landscape", e.matches);
        document.body.classList.toggle("portrait", !e.matches);
    }
    orientationQuery.addEventListener("change", handleOrientation);
    handleOrientation(orientationQuery);

    // ============================================================
    // TOUCH DETECTION
    // ============================================================
    const touchQuery = window.matchMedia("(pointer: coarse)");
    function handleTouch(e) {
        document.body.classList.toggle("touch-device", e.matches);
    }
    touchQuery.addEventListener("change", handleTouch);
    handleTouch(touchQuery);
})();