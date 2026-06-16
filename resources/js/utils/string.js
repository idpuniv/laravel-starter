/**
 * Converts a string to a URL-friendly slug.
 * Handles accents and special characters.
 */
export function slugify(str) {
    return str
        .normalize('NFD')                    // décompose les accents (é → e + ́)
        .replace(/[\u0300-\u036f]/g, '')     // supprime les diacritiques
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')            // supprime les caractères spéciaux
        .replace(/[\s_-]+/g, '-')            // espaces et underscores → tiret
        .replace(/^-+|-+$/g, '');            // trim tirets en début/fin
}