/**
 * DataTableManager - Gestionnaire universel de tableaux AJAX
 * Centralise le filtrage, le tri, la pagination, l'export et le reset.
 */
class DataTableManager {
    constructor(config) {
        this.url = config.url;
        this.container = document.querySelector(
            config.container || "#table-container",
        );
        this.filterSelector = ".dt-filter";

        // État initial du tri
        this.currentSort = config.defaultSort || "id";
        this.currentDirection = config.defaultDirection || "asc";

        this.init();
    }

    init() {
        // 1. Écouteur global pour les entrées texte (Recherche) avec Debounce
        document.addEventListener("input", (e) => {
            if (e.target.matches(this.filterSelector)) {
                if (e.target.type === "text" || e.target.type === "search") {
                    this.debounce(() => this.fetchTable(), 400)();
                }
            }
        });

        // 2. Écouteur global pour les changements (Select, Checkbox, Radio, Date)
        document.addEventListener("change", (e) => {
            if (e.target.matches(this.filterSelector)) {
                if (e.target.type !== "text" && e.target.type !== "search") {
                    this.fetchTable();
                }
            }
        });

        // 3. Écouteur global pour les clics (Pagination, Tri, Export, Reset)
        document.addEventListener("click", (e) => {
            this.handleClicks(e);
        });

        this.updateSortIcons();
    }

    /**
     * Centralise tous les clics sur les éléments interactifs
     */
    handleClicks(e) {
        // Pagination
        const pageLink = e.target.closest(".ajax-page");
        if (pageLink) {
            e.preventDefault();
            this.fetchTable(pageLink.dataset.url);
            return;
        }

        // Tri des colonnes
        const sortHeader = e.target.closest(".sortable");
        if (sortHeader) {
            const field = sortHeader.dataset.field;
            this.currentDirection =
                this.currentSort === field && this.currentDirection === "asc"
                    ? "desc"
                    : "asc";
            this.currentSort = field;
            this.fetchTable();
            return;
        }

        // Exportation
        const exportBtn = e.target.closest("[data-export]");
        if (exportBtn) {
            e.preventDefault();
            const params = this.getQueryParams({
                export: exportBtn.dataset.export,
                export_scope: exportBtn.dataset.scope || "current",
            });
            window.location.href = `${this.url}?${params.toString()}`;
            return;
        }

        // Reset des champs (Gestionnaire intégré)
        const resetBtn = e.target.closest("[data-reset]");
        if (resetBtn) {
            this.handleReset(resetBtn);
        }
    }

    /**
     * Gère le reset des filtres ciblés par l'attribut data-reset
     */
    handleReset(btn) {
        const targetSelectors = btn.getAttribute("data-reset").split(",");

        targetSelectors.forEach((selector) => {
            const el = document.querySelector(selector.trim());
            if (!el) return;

            if (el.tagName === "SELECT") {
                if (el.multiple) {
                    Array.from(el.options).forEach((o) => (o.selected = false));
                } else {
                    el.selectedIndex = 0;
                }
            } else if (el.type === "checkbox" || el.type === "radio") {
                el.checked = false;
            } else {
                el.value = "";
            }
        });

        // Rafraîchissement après reset
        this.fetchTable();
    }

    /**
     * Collecte tous les paramètres des filtres .dt-filter
     */
    getQueryParams(extra = {}) {
        const params = new URLSearchParams();

        document.querySelectorAll(this.filterSelector).forEach((el) => {
            if (!el.name) return;

            // Gestion Checkbox / Radio
            if ((el.type === "checkbox" || el.type === "radio") && !el.checked)
                return;

            // Gestion Select Multiple
            if (el.tagName === "SELECT" && el.multiple) {
                Array.from(el.selectedOptions).forEach((opt) => {
                    if (opt.value) params.append(el.name, opt.value);
                });
            }
            // Autres (Text, Date, Select simple)
            else if (el.value) {
                params.append(el.name, el.value);
            }
        });

        // Tri
        params.append("sort", this.currentSort);
        params.append("direction", this.currentDirection);

        // Page courante (récupérée depuis la pagination active)
        const activePageLink = document.querySelector(
            ".pagination .active .ajax-page, .pagination .page-item.active a",
        );
        if (activePageLink) {
            try {
                const urlObj = new URL(
                    activePageLink.dataset.url || activePageLink.href,
                );
                params.append("page", urlObj.searchParams.get("page"));
            } catch (e) {}
        }

        // Fusion avec les paramètres extra (ex: export)
        Object.entries(extra).forEach(([k, v]) => params.append(k, v));

        return params;
    }

    /**
     * Exécute la requête AJAX
     */
    /**
     * Exécute la requête AJAX avec gestion d'état visuel et sécurité
     */
    fetchTable(url = null) {
        // Sécurité : Vérifier si le container existe avant de continuer
        if (!this.container) {
            console.error(
                "DataTableManager: Le container spécifié n'existe pas dans le DOM.",
            );
            return;
        }

        const queryUrl =
            url || `${this.url}?${this.getQueryParams().toString()}`;

        // Optionnel : Ajouter un effet visuel de chargement
        this.container.style.opacity = "0.5";
        this.container.style.pointerEvents = "none";

        fetch(queryUrl, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((r) => {
                if (!r.ok) throw new Error(`Erreur serveur: ${r.status}`);
                return r.text();
            })
            .then((html) => {
                // On injecte le HTML
                this.container.innerHTML = html;

                // On relance la mise à jour des icônes de tri
                // On entoure de try/catch car c'est une manipulation de DOM non critique
                try {
                    this.updateSortIcons();
                } catch (e) {
                    console.warn(
                        "DataTableManager: Impossible de mettre à jour les icônes de tri.",
                        e,
                    );
                }
            })
            .catch((err) => {
                // On log l'erreur proprement
                console.error("DataTableManager Error:", err);

                // UX : On prévient l'utilisateur que ça a échoué (facultatif)
                // alert("Impossible de mettre à jour le tableau.");
            })
            .finally(() => {
                // Toujours rétablir l'affichage, même en cas d'erreur
                if (this.container) {
                    this.container.style.opacity = "1";
                    this.container.style.pointerEvents = "auto";
                }
            });
    }

    /**
     * Met à jour visuellement les icônes de tri (Bootstrap Icons)
     */
    updateSortIcons() {
        document.querySelectorAll(".sortable").forEach((th) => {
            const icon = th.querySelector("i");
            if (icon) icon.remove();
            const field = th.dataset.field;
            let cls = "bi bi-arrow-down-up text-muted small";

            if (field === this.currentSort) {
                cls = `bi bi-sort-${this.currentDirection === "asc" ? "up" : "down"} text-primary`;
            }
            th.insertAdjacentHTML("beforeend", ` <i class="${cls} ms-1"></i>`);
        });
    }

    /**
     * Utilitaire de délai pour la recherche
     */
    debounce(func, wait) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
}

window.DataTableManager = DataTableManager;
