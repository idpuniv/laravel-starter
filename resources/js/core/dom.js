export const $ = (selector, context = document) => context.querySelector(selector);
export const $$ = (selector, context = document) => context.querySelectorAll(selector);

export const addClass = (el, className) => el.classList.add(className);
export const removeClass = (el, className) => el.classList.remove(className);
export const toggleClass = (el, className) => el.classList.toggle(className);
export const hasClass = (el, className) => el.classList.contains(className);

