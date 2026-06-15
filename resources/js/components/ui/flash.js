import { delay } from '@/core/delay';

export const flash = async (el, duration = 3000) => {
    if (!el) return;

    el.classList.remove('d-none');

    await delay(duration);

    el.classList.add('d-none');
};