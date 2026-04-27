class CustomSelect {
    constructor(container) {
        try {
            this.container = container;
            if (!container) {
                throw new Error("Container is null");
            }

            this.button = container.querySelector(".select-button");
            this.selectedOption = container.querySelector(".selected-option");
            this.searchInput = container.querySelector(".search-input");
            this.options = container.querySelectorAll(".select-option");
            this.dropdown = null;
            this.hasSearch = false;

            // Nouveaux attributs pour la navigation
            this.currentFocusIndex = -1;
            this.isDropdownOpen = false;

            // Vérifier les éléments critiques
            if (!this.button) {
                console.warn(
                    "Select button not found in container:",
                    container
                );
                return;
            }
            if (!this.selectedOption) {
                console.warn(
                    "Selected option element not found in container:",
                    container
                );
                return;
            }

            // Gérer le champ hidden
            this.initializeHiddenInput();

            // Configurer aria-describedby
            this.setupAriaDescribedBy();

            this.init();
            this.selectInitialValue();
        } catch (error) {
            console.error("Error in CustomSelect constructor:", error);
        }
    }

    initializeHiddenInput() {
        try {
            // Récupérer le data-name
            this.dataName = this.container.getAttribute("data-name");

            // Chercher TOUS les champs hidden
            const allHiddenInputs = this.container.querySelectorAll(
                'input[type="hidden"]'
            );

            // Chercher un champ hidden existant avec la classe select-value
            this.hiddenInput = this.container.querySelector(
                'input[type="hidden"].select-value'
            );

            // Si pas trouvé, prendre le premier champ hidden disponible
            if (!this.hiddenInput && allHiddenInputs.length > 0) {
                this.hiddenInput = allHiddenInputs[0];
            }

            // Si aucun champ hidden n'existe, le créer
            if (!this.hiddenInput) {
                this.hiddenInput = document.createElement("input");
                this.hiddenInput.type = "hidden";
                this.container.appendChild(this.hiddenInput);
            }

            // Mettre à jour les attributs du champ
            this.hiddenInput.className = "select-value";
            if (this.dataName && !this.hiddenInput.name) {
                this.hiddenInput.name = this.dataName;
            }

            return this.hiddenInput;
        } catch (error) {
            console.error("Error in initializeHiddenInput:", error);
            return null;
        }
    }

    setupAriaDescribedBy() {
        try {
            // Récupérer aria-describedby du container et l'appliquer au button
            const describedBy = this.container.getAttribute("aria-describedby");
            if (describedBy && this.button) {
                this.button.setAttribute("aria-describedby", describedBy);
            }

            // Si le hiddenInput a aria-describedby, le conserver
            const inputDescribedBy =
                this.hiddenInput?.getAttribute("aria-describedby");
            if (inputDescribedBy && this.hiddenInput) {
                this.hiddenInput.setAttribute(
                    "aria-describedby",
                    inputDescribedBy
                );
            }
        } catch (error) {
            console.error("Error in setupAriaDescribedBy:", error);
        }
    }

    selectInitialValue() {
        try {
            // S'assurer que les éléments critiques existent
            if (!this.hiddenInput) {
                console.warn("hiddenInput is null in selectInitialValue");
                return;
            }

            if (!this.selectedOption) {
                console.error("selectedOption is null");
                return;
            }

            // Vérifier d'abord l'attribut "selected" sur les options
            const selectedOption = Array.from(this.options).find(
                (opt) => opt && opt.hasAttribute("selected")
            );

            if (selectedOption) {
                this.selectOption(selectedOption, false);
            } else {
                // Sinon utiliser la valeur du champ caché
                const currentValue = this.hiddenInput.value;
                if (currentValue) {
                    const option = Array.from(this.options).find(
                        (opt) =>
                            opt &&
                            opt.getAttribute("data-value") === currentValue
                    );
                    if (option) {
                        this.selectOption(option, false);
                    }
                } else {
                    // Si aucune valeur n'est définie, sélectionner la première option par défaut
                    const firstOption = this.options[0];
                    if (firstOption) {
                        this.selectOption(firstOption, false);
                    }
                }
            }
        } catch (error) {
            console.error("Error in selectInitialValue:", error);
        }
    }

    init() {
        try {
            // Vérifier si une barre de recherche existe
            this.hasSearch = this.searchInput !== null;

            // Vérifier que le bouton existe avant d'initialiser le dropdown
            if (!this.button) {
                console.error("Button not found, cannot initialize dropdown");
                return;
            }

            // Initialiser le dropdown Bootstrap
            this.dropdown = new bootstrap.Dropdown(this.button);

            // Événements pour les options
            this.options.forEach((option) => {
                if (option) {
                    option.addEventListener("click", (e) => {
                        e.preventDefault();
                        this.selectOption(option);
                    });

                    // Navigation au clavier sur les options
                    option.addEventListener("keydown", (e) => {
                        this.handleOptionKeydown(e, option);
                    });
                }
            });

            // Initialiser la barre de recherche si elle existe
            if (this.hasSearch && this.searchInput) {
                this.initSearch();
            }

            // Réinitialiser à l'ouverture
            this.button.addEventListener("show.bs.dropdown", () => {
                this.onDropdownShow();
            });

            // Nettoyer à la fermeture
            this.button.addEventListener("hidden.bs.dropdown", () => {
                this.onDropdownHide();
            });

            // Navigation au clavier sur le bouton
            this.button.addEventListener("keydown", (e) => {
                this.handleKeydown(e);
            });

            // Mettre à jour les attributs ARIA du button
            this.updateButtonAriaAttributes();
        } catch (error) {
            console.error("Error in init:", error);
        }
    }

    updateButtonAriaAttributes() {
        try {
            if (!this.button) return;

            // S'assurer que le button a les bons attributs ARIA
            if (!this.button.getAttribute("aria-labelledby")) {
                const label = this.container.querySelector("label");
                if (label && label.id) {
                    this.button.setAttribute("aria-labelledby", label.id);
                }
            }

            // Mettre à jour aria-expanded
            this.button.setAttribute("aria-expanded", "false");

            // Ajouter le rôle de listbox pour l'accessibilité
            this.button.setAttribute("role", "combobox");
            this.button.setAttribute("aria-haspopup", "listbox");
        } catch (error) {
            console.error("Error in updateButtonAriaAttributes:", error);
        }
    }

    initSearch() {
        try {
            if (!this.searchInput) return;

            // Filtrage en temps réel
            this.searchInput.addEventListener("input", () => {
                this.filterOptions();
            });

            // Empêcher la fermeture quand on clique dans la recherche
            this.searchInput.addEventListener("click", (e) => {
                e.stopPropagation();
            });

            // Navigation au clavier dans la recherche
            this.searchInput.addEventListener("keydown", (e) => {
                this.handleSearchKeydown(e);
            });
        } catch (error) {
            console.error("Error in initSearch:", error);
        }
    }

    onDropdownShow() {
        try {
            this.isDropdownOpen = true;

            // Mettre à jour aria-expanded
            if (this.button) {
                this.button.setAttribute("aria-expanded", "true");
            }

            // Réinitialiser l'index de focus
            this.currentFocusIndex = -1;

            // Mettre à jour les attributs ARIA des options
            this.updateOptionsAriaAttributes();

            if (this.hasSearch && this.searchInput) {
                this.resetSearch();
                setTimeout(() => this.searchInput.focus(), 100);
            } else {
                // Focus sur la première option si pas de recherche
                setTimeout(() => this.focusFirstOption(), 100);
            }
        } catch (error) {
            console.error("Error in onDropdownShow:", error);
        }
    }

    onDropdownHide() {
        try {
            this.isDropdownOpen = false;
            this.currentFocusIndex = -1;

            if (this.button) {
                this.button.setAttribute("aria-expanded", "false");
                this.button.focus(); // Retourner le focus au bouton
            }

            this.emitChangeEvent();
        } catch (error) {
            console.error("Error in onDropdownHide:", error);
        }
    }

    selectOption(option, closeDropdown = true) {
        try {
            // Vérifications de sécurité
            if (!option) {
                console.warn("No option provided to selectOption");
                return;
            }

            const value = option.getAttribute("data-value");
            // NETTOYAGE DU TEXTE - Supprimer les espaces superflus et retours à la ligne
            const text = option.textContent.trim().replace(/\s+/g, " ");

            // Mettre à jour l'affichage avec vérification
            if (this.selectedOption) {
                this.selectedOption.textContent = text;
            }

            // Vérifier et mettre à jour le champ hidden
            if (!this.hiddenInput) {
                console.warn("hiddenInput is null in selectOption");
                return;
            }

            // Mettre à jour la valeur
            this.hiddenInput.value = value || "";

            // Mettre à jour les états ARIA et selected
            this.options.forEach((opt) => {
                if (opt) {
                    opt.setAttribute("aria-selected", "false");
                    opt.removeAttribute("selected");
                    opt.classList.remove("focused");
                }
            });
            option.setAttribute("aria-selected", "true");
            option.setAttribute("selected", "");

            // Mettre à jour l'index de focus
            const visibleOptions = this.getVisibleOptions();
            this.currentFocusIndex = visibleOptions.indexOf(option);

            // Fermer le dropdown si demandé
            if (closeDropdown && this.dropdown) {
                this.dropdown.hide();
            }

            // Mettre à jour l'accessibilité
            if (this.button) {
                this.button.setAttribute(
                    "aria-label",
                    `Option sélectionnée: ${text}`
                );
            }
        } catch (error) {
            console.error("Error in selectOption:", error);
        }
    }

    filterOptions() {
    try {
        if (!this.hasSearch || !this.searchInput) return;

        const searchTerm = this.searchInput.value.toLowerCase().trim();
        
        // Si la recherche est vide, montrer tout
        if (!searchTerm) {
            this.options.forEach((option) => {
                if (option) {
                    option.classList.remove("hidden");
                    option.removeAttribute("aria-hidden");
                }
            });
            this.currentFocusIndex = -1;
            return;
        }

        // Fonction simple pour enlever les accents (version simplifiée)
        const normalize = (str) => {
            if (!str) return '';
            return str
                .normalize('NFD')  // Décompose les accents
                .replace(/[\u0300-\u036f]/g, '')  // Supprime les diacritiques
                .toLowerCase()
                .trim();
        };

        const normalizedSearchTerm = normalize(searchTerm);

        this.options.forEach((option) => {
            if (option) {
                const text = option.textContent;
                const normalizedText = normalize(text);
                
                // Plusieurs stratégies de recherche comme SQL LIKE:
                
                // 1. Recherche directe (contient le terme)
                const directMatch = normalizedText.includes(normalizedSearchTerm);
                
                // 2. Recherche par mots séparés (si l'utilisateur tape plusieurs mots)
                const words = normalizedSearchTerm.split(/\s+/);
                const allWordsMatch = words.every(word => 
                    word && normalizedText.includes(word)
                );
                
                // 3. Recherche par initiales (ex: "sa" pour "Sécurité alimentaire")
                const optionWords = normalizedText.split(/\s+/);
                const initials = optionWords.map(w => w.charAt(0)).join('');
                const initialsMatch = initials.includes(normalizedSearchTerm);
                
                // 4. Recherche phonétique simple (première lettre similaire)
                const firstLetterMatch = 
                    normalizedSearchTerm.length === 1 && 
                    normalizedText.charAt(0) === normalizedSearchTerm;
                
                // Montrer si une des conditions est remplie
                if (directMatch || allWordsMatch || initialsMatch || firstLetterMatch) {
                    option.classList.remove("hidden");
                    option.removeAttribute("aria-hidden");
                } else {
                    option.classList.add("hidden");
                    option.setAttribute("aria-hidden", "true");
                }
            }
        });

        this.currentFocusIndex = -1;
    } catch (error) {
        console.error("Error in filterOptions:", error);
    }
}

    resetSearch() {
        try {
            if (!this.hasSearch || !this.searchInput) return;

            this.searchInput.value = "";
            this.options.forEach((option) => {
                if (option) {
                    option.classList.remove("hidden");
                    option.removeAttribute("aria-hidden");
                }
            });

            // Réinitialiser le focus
            this.currentFocusIndex = -1;
            // this.focusFirstOption();
        } catch (error) {
            console.error("Error in resetSearch:", error);
        }
    }

    // NOUVELLES MÉTHODES POUR LA NAVIGATION AU CLAVIER
    handleKeydown(e) {
        try {
            if (!this.button) return;

            switch (e.key) {
                case "Enter":
                case " ":
                case "Spacebar":
                    e.preventDefault();
                    if (this.dropdown) {
                        this.dropdown.toggle();
                    }
                    break;

                case "Escape":
                    if (this.dropdown && this.dropdown._isShown) {
                        e.preventDefault();
                        this.dropdown.hide();
                    }
                    break;

                case "ArrowDown":
                    e.preventDefault();
                    if (this.dropdown && this.dropdown._isShown) {
                        this.focusNextOption();
                    } else {
                        this.dropdown?.show();
                    }
                    break;

                case "ArrowUp":
                    e.preventDefault();
                    if (this.dropdown && this.dropdown._isShown) {
                        this.focusPreviousOption();
                    } else {
                        this.dropdown?.show();
                    }
                    break;

                case "Home":
                    e.preventDefault();
                    if (this.dropdown && this.dropdown._isShown) {
                        this.focusFirstOption();
                    }
                    break;

                case "End":
                    e.preventDefault();
                    if (this.dropdown && this.dropdown._isShown) {
                        this.focusLastOption();
                    }
                    break;

                case "Tab":
                    if (this.dropdown && this.dropdown._isShown) {
                        if (e.shiftKey) {
                            // Tab vers l'arrière - fermer le dropdown
                            this.dropdown.hide();
                        } else {
                            // Tab vers l'avant - sélectionner l'option focus et fermer
                            this.selectFocusedOption();
                            this.dropdown.hide();
                        }
                    }
                    break;
            }
        } catch (error) {
            console.error("Error in handleKeydown:", error);
        }
    }

    handleSearchKeydown(e) {
        try {
            if (!this.searchInput) return;

            switch (e.key) {
                case "ArrowDown":
                    e.preventDefault();
                    this.focusNextOption();
                    break;

                case "ArrowUp":
                    e.preventDefault();
                    this.focusPreviousOption();
                    break;

                case "Enter":
                    e.preventDefault();
                    this.selectFocusedOption();
                    break;

                case "Escape":
                    e.preventDefault();
                    this.dropdown?.hide();
                    break;

                case "Home":
                    e.preventDefault();
                    this.focusFirstOption();
                    break;

                case "End":
                    e.preventDefault();
                    this.focusLastOption();
                    break;
            }
        } catch (error) {
            console.error("Error in handleSearchKeydown:", error);
        }
    }

    handleOptionKeydown(e, option) {
        try {
            switch (e.key) {
                case "Enter":
                case " ":
                case "Spacebar":
                    e.preventDefault();
                    this.selectOption(option);
                    break;

                case "Escape":
                    e.preventDefault();
                    this.dropdown?.hide();
                    break;

                case "ArrowDown":
                    e.preventDefault();
                    this.focusNextOption();
                    break;

                case "ArrowUp":
                    e.preventDefault();
                    this.focusPreviousOption();
                    break;

                case "Home":
                    e.preventDefault();
                    this.focusFirstOption();
                    break;

                case "End":
                    e.preventDefault();
                    this.focusLastOption();
                    break;
            }
        } catch (error) {
            console.error("Error in handleOptionKeydown:", error);
        }
    }

    getVisibleOptions() {
        return Array.from(this.options).filter(
            (opt) =>
                opt &&
                !opt.classList.contains("hidden") &&
                !opt.hasAttribute("aria-hidden")
        );
    }

    focusFirstOption() {
        const visibleOptions = this.getVisibleOptions();
        if (visibleOptions.length > 0) {
            this.currentFocusIndex = 0;
            this.updateFocus();
        }
    }

    focusLastOption() {
        const visibleOptions = this.getVisibleOptions();
        if (visibleOptions.length > 0) {
            this.currentFocusIndex = visibleOptions.length - 1;
            this.updateFocus();
        }
    }

    focusNextOption() {
        const visibleOptions = this.getVisibleOptions();
        if (visibleOptions.length === 0) return;

        this.currentFocusIndex =
            (this.currentFocusIndex + 1) % visibleOptions.length;
        this.updateFocus();
    }

    focusPreviousOption() {
        const visibleOptions = this.getVisibleOptions();
        if (visibleOptions.length === 0) return;

        this.currentFocusIndex =
            (this.currentFocusIndex - 1 + visibleOptions.length) %
            visibleOptions.length;
        this.updateFocus();
    }

    

    selectFocusedOption() {
        const visibleOptions = this.getVisibleOptions();
        if (visibleOptions[this.currentFocusIndex]) {
            this.selectOption(visibleOptions[this.currentFocusIndex]);
        }
    }

    generateOptionId(option) {
        if (!option.id) {
            const randomId =
                "option-" + Math.random().toString(36).substr(2, 9);
            option.id = randomId;
        }
        return option.id;
    }

    updateOptionsAriaAttributes() {
        this.options.forEach((option, index) => {
            if (option) {
                option.setAttribute("role", "option");
                option.setAttribute("tabindex", "-1");
                if (!option.id) {
                    option.id = this.generateOptionId(option);
                }
            }
        });
    }

    emitChangeEvent() {
        try {
            // NETTOYAGE DU TEXTE pour l'événement
            const cleanText = this.selectedOption
                ? this.selectedOption.textContent.trim().replace(/\s+/g, " ")
                : "";

            // Émettre un événement change personnalisé
            const changeEvent = new CustomEvent("change", {
                bubbles: true,
                detail: {
                    value: this.hiddenInput ? this.hiddenInput.value : "",
                    text: cleanText,
                    hasSearch: this.hasSearch,
                },
            });
            console.log('value changed');
            this.container.dispatchEvent(changeEvent);

            // Émettre aussi un événement input pour la compatibilité
            if (this.hiddenInput) {
                const inputEvent = new Event("input", {
                    bubbles: true,
                });
                this.hiddenInput.dispatchEvent(inputEvent);
            }
        } catch (error) {
            console.error("Error in emitChangeEvent:", error);
        }
    }

    // Méthodes publiques
    get value() {
        return this.hiddenInput ? this.hiddenInput.value : "";
    }

    set value(newValue) {
        const option = Array.from(this.options).find(
            (opt) => opt && opt.getAttribute("data-value") === newValue
        );
        if (option) {
            this.selectOption(option);
        }
    }

    get selectedText() {
        return this.selectedOption
            ? this.selectedOption.textContent.trim().replace(/\s+/g, " ")
            : "";
    }

    get selectedOptionElement() {
        return Array.from(this.options).find(
            (opt) => opt && opt.hasAttribute("selected")
        );
    }

    disable() {
        try {
            this.container.classList.add("disabled");
            if (this.button) {
                this.button.setAttribute("disabled", "true");
                this.button.setAttribute("aria-disabled", "true");
            }
            if (this.hiddenInput) {
                this.hiddenInput.setAttribute("disabled", "true");
            }
        } catch (error) {
            console.error("Error in disable:", error);
        }
    }

    enable() {
        try {
            this.container.classList.remove("disabled");
            if (this.button) {
                this.button.removeAttribute("disabled");
                this.button.setAttribute("aria-disabled", "false");
            }
            if (this.hiddenInput) {
                this.hiddenInput.removeAttribute("disabled");
            }
        } catch (error) {
            console.error("Error in enable:", error);
        }
    }

    get hasSearchFeature() {
        return this.hasSearch;
    }

    // Méthode pour mettre à jour aria-describedby dynamiquement
    setAriaDescribedBy(describedById) {
        try {
            if (this.button) {
                this.button.setAttribute("aria-describedby", describedById);
            }
            if (this.hiddenInput) {
                this.hiddenInput.setAttribute(
                    "aria-describedby",
                    describedById
                );
            }
        } catch (error) {
            console.error("Error in setAriaDescribedBy:", error);
        }
    }

    // Méthode pour supprimer aria-describedby
    removeAriaDescribedBy() {
        try {
            if (this.button) {
                this.button.removeAttribute("aria-describedby");
            }
            if (this.hiddenInput) {
                this.hiddenInput.removeAttribute("aria-describedby");
            }
        } catch (error) {
            console.error("Error in removeAriaDescribedBy:", error);
        }
    }


    scrollOptionIntoView(option) {
    if (!option) return;

    const dropdownMenu = option.closest(".dropdown-menu");
    if (!dropdownMenu) return;

    const optionRect = option.getBoundingClientRect();
    const menuRect = dropdownMenu.getBoundingClientRect();

    const isAbove = optionRect.top < menuRect.top;
    const isBelow = optionRect.bottom > menuRect.bottom;

    if (isAbove || isBelow) {
        option.scrollIntoView({
            block: "nearest",
            behavior: "smooth"
        });
    }
}

    // Ajoute cette méthode à ta classe
scrollToSelectedOnOpen() {
    // Trouver l'élément sélectionné
    const selected = Array.from(this.options).find(
        opt => opt && opt.getAttribute("aria-selected") === "true"
    );
    
    // Si trouvé et pas caché, faire scroller vers lui
    if (selected && !selected.classList.contains('hidden')) {
        selected.scrollIntoView({ block: 'nearest' });
    }
}

}

// Initialisation automatique
document.addEventListener("DOMContentLoaded", function () {
    try {
        const customSelects = document.querySelectorAll(".custom-select");
        customSelects.forEach((container) => {
            try {
                const select = new CustomSelect(container);
            } catch (error) {
                console.error(
                    "Error initializing custom select:",
                    error,
                    container
                );
            }
        });
    } catch (error) {
        console.error("Error in DOMContentLoaded handler:", error);
    }
});

window.CustomSelect = CustomSelect;