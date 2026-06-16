import { slugify } from '@/utils/string';

/**
 * Auto-binds all slug generators on the page.
 * Source: any input with [data-slug-target]
 * Target: input with the ID specified in data-slug-target
 */
export function initSlugGenerators() {
    document.querySelectorAll('[data-slug-target]').forEach(source => {
        const target = document.getElementById(source.dataset.slugTarget);

        if (!target) return;

        let manuallyEdited = false;

        source.addEventListener('input', () => {
            if (!manuallyEdited) {
                target.value = slugify(source.value);
            }
        });

        target.addEventListener('input', () => {
            manuallyEdited = target.value !== slugify(source.value);
        });
    });
}