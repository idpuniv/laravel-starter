/**
 * Force l'utilisation d'un positionnement 'fixed' pour tous les menus déroulants
 * situés à l'intérieur d'un conteneur `.table-responsive`. Cela évite que le menu 
 * soit tronqué par la propriété CSS `overflow: auto` ou `hidden` du tableau.
 */
document.querySelectorAll('.table-responsive [data-bs-toggle="dropdown"]').forEach(function (el) {
    new bootstrap.Dropdown(el, {
        popperConfig: function (defaultConfig) {
            return {
                ...defaultConfig,
                strategy: 'fixed',
            };
        },
    });
});



(function () {
    "use strict";

    // Fix #6: fail loudly and early rather than throwing a cryptic
    // ReferenceError deep inside instantiate() if this script is ever
    // reused somewhere Bootstrap's bundle hasn't loaded yet.
    if (typeof bootstrap === "undefined") {
        console.error("[offcanvas-demo] Bootstrap JS is not loaded — aborting.");
        return;
    }

    // Standard Bootstrap 5.3 breakpoints (min-width, px) — scss/_variables.scss
    const BREAKPOINTS = { sm: 576, md: 768, lg: 992, xl: 1200, xxl: 1400 };
    const DEFAULT_BREAKPOINT = "md";

    // Currently open panels, for live-switch handling.
    // Map<HTMLElement, { instance, mode, state }>
    // mode  ∈ 'modal' | 'offcanvas' — the real Bootstrap component names,
    //          not a device notion.
    // state ∈ 'showing' | 'shown' | 'hiding' | 'hidden'
    const openPanels = new Map();

    // Fix #3: preserves the original .modal-dialog/.modal-content DOM
    // nodes across a modal→offcanvas→modal round trip, instead of
    // recreating fresh ones. Anything holding a reference to the
    // original dialog node keeps a valid reference after switching back.
    // Map<HTMLElement, { dialog: HTMLElement, content: HTMLElement }>
    const preservedModalShells = new Map();

    /**
     * Resolves an element's current mode from its own breakpoint
     * (data-offcanvas-breakpoint), not a global mobile/desktop notion.
     * Above the breakpoint → 'modal'. Below it → 'offcanvas'.
     */
    function resolveMode(el) {
        const name = el.dataset.offcanvasBreakpoint || DEFAULT_BREAKPOINT;
        const px = BREAKPOINTS[name] ?? BREAKPOINTS[DEFAULT_BREAKPOINT];
        return window.matchMedia(`(min-width: ${px}px)`).matches ? "modal" : "offcanvas";
    }

    function updateViewportIndicator() {
        const indicator = document.getElementById("viewport-indicator");
        if (!indicator) return;
        indicator.textContent = `Largeur actuelle : ${window.innerWidth}px`;
    }

    /**
     * Safety net: purges any leftover backdrop and resets the
     * classes/styles Bootstrap sets on <body>. Called only as a last
     * resort, never as the primary mechanism — the primary mechanism is
     * strictly following Bootstrap's show/shown/hide/hidden events.
     */
    function forceCleanupBackdrops() {
        document.querySelectorAll(".modal-backdrop, .offcanvas-backdrop").forEach((el) => el.remove());
        document.body.classList.remove("modal-open");
        document.body.style.removeProperty("overflow");
        document.body.style.removeProperty("padding-right");
    }

    /**
     * mode already matches Bootstrap's own event namespace
     * (show.bs.modal / show.bs.offcanvas), so no intermediate
     * mobile→offcanvas / desktop→modal mapping is needed.
     */
    function eventNames(mode) {
        return {
            show: `show.bs.${mode}`,
            shown: `shown.bs.${mode}`,
            hide: `hide.bs.${mode}`,
            hidden: `hidden.bs.${mode}`,
        };
    }

    /**
     * Instantiates and opens the native Bootstrap component (Modal or
     * Offcanvas) on an element already transformed into the right mode.
     * All synchronization relies on Bootstrap's 4 lifecycle events —
     * never an arbitrary setTimeout, never a hot dispose().
     *
     * Fix #1: getOrCreateInstance() instead of `new Modal()`/`new
     * Offcanvas()` — a stray double-instantiation on the same element no
     * longer creates two independent component instances.
     */
    function instantiate(el, mode) {
        const evt = eventNames(mode);
        const instance = mode === "offcanvas"
            ? bootstrap.Offcanvas.getOrCreateInstance(el)
            : bootstrap.Modal.getOrCreateInstance(el);

        const panel = { instance, mode, state: "showing" };
        openPanels.set(el, panel);

        el.addEventListener(evt.shown, () => { panel.state = "shown"; });

        el.addEventListener(evt.hide, () => { panel.state = "hiding"; });

        el.addEventListener(evt.hidden, function onHidden() {
            el.removeEventListener(evt.hidden, onHidden);
            panel.state = "hidden";
            instance.dispose();
            openPanels.delete(el);
            forceCleanupBackdrops();

            // If a mode switch was requested WHILE closing, apply it now
            // that everything has properly finished.
            if (el.dataset.pendingMode) {
                const pendingMode = el.dataset.pendingMode;
                delete el.dataset.pendingMode;
                resetInlineState(el);
                applyTransform(el, pendingMode);
                instantiate(el, pendingMode);
            }
        }, { once: true });

        instance.show();
    }

    /**
     * Bootstrap sets inline styles at the end of hide():
     * - Modal     → el.style.display = 'none'
     * - Offcanvas → el.style.visibility = 'hidden'
     * These outrank our CSS classes in specificity. Without this
     * cleanup, the element stays invisible after a first switch cycle
     * (the new backdrop shows, but not the panel) — previously this
     * forced a full page reload to recover a clean state.
     */
    function resetInlineState(el) {
        el.removeAttribute("style");
        el.removeAttribute("aria-hidden");
        el.removeAttribute("aria-modal");
        el.removeAttribute("role");
        // Note: these ARIA attributes are re-applied by Bootstrap itself
        // during show()/hide() — this relies on that (undocumented but
        // stable) internal behavior rather than us re-setting them
        // explicitly, since Bootstrap owns them for the rest of the
        // component's lifecycle anyway.
    }

    /**
     * Detects whether a set of offcanvas classes places the panel on a
     * horizontal edge (start/end, sized by width) or a vertical one
     * (top/bottom, sized by height). Fix #4: data-offcanvas-height was
     * silently ignored for offcanvas-start/-end, since only
     * --bs-offcanvas-height was ever set. Position-aware sizing fixes
     * that instead of leaving it as an undocumented trap.
     */
    function resolveAxis(classes) {
        return classes.includes("offcanvas-start") || classes.includes("offcanvas-end")
            ? "width"
            : "height";
    }

    function applyTransform(el, mode) {
        const isCurrentlyOffcanvas = el.classList.contains("offcanvas");
        if (mode === "offcanvas" && !isCurrentlyOffcanvas) modalToOffcanvas(el);
        if (mode === "modal" && isCurrentlyOffcanvas) offcanvasToModal(el);

        // Always reapplied, even if no structural transform happened
        // (e.g. reopening in the same mode after resetInlineState wiped
        // the whole style attribute). Uses the official local CSS
        // variable Bootstrap 5.3 exposes on .offcanvas for real-time
        // customization.
        if (mode === "offcanvas") {
            const classes = getOffcanvasClasses(el);
            const axis = resolveAxis(classes); // 'width' (start/end) or 'height' (top/bottom)
            const dimensionAttr = axis === "width" ? "offcanvasWidth" : "offcanvasHeight";
            const cssVar = axis === "width" ? "--bs-offcanvas-width" : "--bs-offcanvas-height";
            const fallback = axis === "width" ? "380px" : "85vh";

            const raw = el.dataset[dimensionAttr] || fallback;
            el.style.setProperty(cssVar, raw);

            // "auto" adapts to real content, but a safety ceiling is
            // always applied so long content scrolls instead of pushing
            // past the viewport.
            if (axis === "height") {
                el.style.maxHeight = raw === "auto" ? "85vh" : raw;
                el.style.overflowY = "auto";
            } else {
                el.style.maxWidth = raw === "auto" ? "380px" : raw;
                el.style.overflowY = "auto";
            }
        }
    }

    /**
     * Fix #1 (continued): a click while the panel is already open/opening
     * is a no-op instead of spinning up a second Bootstrap instance on
     * top of the first.
     */
    function openResponsive(selector) {
        const el = document.querySelector(selector);
        if (!el) return;
        if (openPanels.has(el)) return;

        const mode = resolveMode(el);
        resetInlineState(el);
        applyTransform(el, mode);
        instantiate(el, mode);
    }

    // Fix #2: delegated on document instead of binding each
    // [data-offcanvas-trigger] individually — triggers added to the DOM
    // after this script runs (Ajax content, a Blade partial included
    // later) are picked up automatically, no re-binding needed.
    document.addEventListener("click", (event) => {
        const trigger = event.target.closest("[data-offcanvas-trigger]");
        if (!trigger) return;
        openResponsive(trigger.dataset.offcanvasTrigger);
    });

    function closeThenSwitch(el, panel, newMode) {
        el.dataset.pendingMode = newMode;
        panel.instance.hide();
    }

    /**
     * Live-switches a panel if its own breakpoint (data-offcanvas-breakpoint)
     * is crossed while it's open. Each open panel can have a different
     * breakpoint, so the target mode is recalculated per element on every
     * resize rather than derived from one global threshold.
     *
     * Strictly follows Bootstrap's lifecycle:
     * - state 'shown'   → hide() immediately, safe to do so.
     * - state 'showing' → hide() would be ignored by Bootstrap (isShown
     *                     not yet true) → wait for the "shown" event first.
     * - state 'hiding'  → a close is already in flight → just record the
     *                     target mode, applied automatically at "hidden".
     */
    let resizeFrame = null;
    window.addEventListener("resize", () => {
        if (resizeFrame) return;
        resizeFrame = requestAnimationFrame(() => {
            resizeFrame = null;
            updateViewportIndicator();

            openPanels.forEach((panel, el) => {
                const newMode = resolveMode(el);
                if (panel.mode === newMode) return;

                if (panel.state === "shown") {
                    closeThenSwitch(el, panel, newMode);
                } else if (panel.state === "showing") {
                    const evt = eventNames(panel.mode);
                    el.addEventListener(evt.shown, function onShown() {
                        el.removeEventListener(evt.shown, onShown);
                        closeThenSwitch(el, panel, newMode);
                    }, { once: true });
                } else if (panel.state === "hiding") {
                    el.dataset.pendingMode = newMode;
                }
            });
        });
    });

    /**
     * Reads the classes to apply when transforming into an offcanvas,
     * from data-offcanvas (space-separated: position + free-form custom
     * classes, e.g. "offcanvas-bottom rounded-top-4 shadow-lg").
     * Falls back to "offcanvas-bottom" if absent or empty.
     */
    function getOffcanvasClasses(el) {
        const raw = (el.dataset.offcanvas || "").trim();
        return raw ? raw.split(/\s+/) : ["offcanvas-bottom"];
    }

    /**
     * Transforms a standard modal into an offcanvas in place. Mutates
     * the DOM without discarding anything permanently — offcanvasToModal()
     * knows how to restore the original state.
     *
     * Fix #3: the original .modal-dialog/.modal-content nodes are kept
     * (detached, in preservedModalShells) instead of being discarded,
     * so offcanvasToModal() can reuse the exact same nodes rather than
     * creating new ones.
     */
    function modalToOffcanvas(modalEl) {
        modalEl.classList.remove("modal", "fade");

        const extraClasses = getOffcanvasClasses(modalEl);
        modalEl.classList.add("offcanvas", ...extraClasses);
        // Recorded for an exact symmetric removal in offcanvasToModal,
        // even if data-offcanvas changes dynamically between two openings.
        modalEl.dataset._appliedOffcanvasClasses = extraClasses.join(" ");

        const dialog = modalEl.querySelector(".modal-dialog");
        const content = modalEl.querySelector(".modal-content");
        const header = modalEl.querySelector(".modal-header");
        const title = modalEl.querySelector(".modal-title");
        const body = modalEl.querySelector(".modal-body");
        const footer = modalEl.querySelector(".modal-footer");

        header?.classList.replace("modal-header", "offcanvas-header");
        title?.classList.replace("modal-title", "offcanvas-title");
        body?.classList.replace("modal-body", "offcanvas-body");

        // data-bs-dismiss="modal" buttons must target "offcanvas" instead
        modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach((btn) => {
            btn.setAttribute("data-bs-dismiss", "offcanvas");
        });

        // Lift header + body (+ footer merged into it) to the root level,
        // detach (but keep, see below) the modal's dialog/content wrapper.
        if (header) modalEl.appendChild(header);
        if (body) {
            if (footer) body.appendChild(footer); // visual merge, see .offcanvas-body .modal-footer CSS
            modalEl.appendChild(body);
        }
        if (dialog && content) {
            dialog.remove();
            preservedModalShells.set(modalEl, { dialog, content });
        }
    }

    /**
     * Restores the original modal structure from a transformed offcanvas.
     */
    function offcanvasToModal(offcanvasEl) {
        const appliedClasses = (offcanvasEl.dataset._appliedOffcanvasClasses || "").split(/\s+/).filter(Boolean);
        offcanvasEl.classList.remove("offcanvas", "show", ...appliedClasses);
        delete offcanvasEl.dataset._appliedOffcanvasClasses;

        offcanvasEl.classList.add("modal", "fade");
        offcanvasEl.removeAttribute("style");

        const header = offcanvasEl.querySelector(".offcanvas-header");
        const title = offcanvasEl.querySelector(".offcanvas-title");
        const body = offcanvasEl.querySelector(".offcanvas-body");
        const footer = body?.querySelector(".modal-footer");

        header?.classList.replace("offcanvas-header", "modal-header");
        title?.classList.replace("offcanvas-title", "modal-title");
        body?.classList.replace("offcanvas-body", "modal-body");

        offcanvasEl.querySelectorAll('[data-bs-dismiss="offcanvas"]').forEach((btn) => {
            btn.setAttribute("data-bs-dismiss", "modal");
        });

        if (footer) footer.remove(); // extracted from body before placing it back as a proper footer

        // Reuse the original dialog/content nodes (Fix #3) instead of
        // creating fresh ones — any external reference to them stays valid.
        const preserved = preservedModalShells.get(offcanvasEl);
        const dialog = preserved?.dialog ?? document.createElement("div");
        const content = preserved?.content ?? document.createElement("div");
        if (!preserved) {
            // Defensive fallback only — should not normally happen, since
            // modalToOffcanvas always records the shell before this runs.
            dialog.className = "modal-dialog modal-dialog-centered";
            content.className = "modal-content";
        }

        // content may have been left with stale children from before the
        // transform (header/body were moved out) — clear before rebuilding.
        content.innerHTML = "";
        if (header) content.appendChild(header);
        if (body) content.appendChild(body);
        if (footer) content.appendChild(footer);

        if (!dialog.contains(content)) dialog.appendChild(content);
        offcanvasEl.appendChild(dialog);
        preservedModalShells.delete(offcanvasEl);
    }

    updateViewportIndicator();
})();
