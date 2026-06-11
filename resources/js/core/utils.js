// Delays function execution until after specified delay
export const debounce = (fn, delay = 300) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
    };
};

// Formats date to localized string (default: French)
export const formatDate = (date, locale = 'fr-FR') => {
    return new Date(date).toLocaleDateString(locale);
};