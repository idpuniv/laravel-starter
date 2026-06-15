import { categoryService } from '@/services/categoryService';
import logger              from '@/core/logger';
import events              from '@/core/events';
import { flash }           from '@/components/ui/flash';

document.addEventListener('DOMContentLoaded', async () => {

    try {
        const categories = await categoryService.getAll();
        logger.log('IDO PAUL');
        events.emit('categories:loaded', categories);
    } catch (error) {
        logger.error('[posts] load categories:', error);
        events.emit('categories:error', error);
    }

    document.querySelectorAll('.btn-delete-category').forEach(button => {
        button.addEventListener('click', async () => {
            const { id } = button.dataset;
            try {
                await categoryService.delete(id);
                events.emit('category:deleted', id);
            } catch (error) {
                logger.error('[posts] delete category:', error);
                events.emit('category:error', error);
            }
        });
    });

    document.getElementById('btn-save-category')?.addEventListener('click', async (e) => {
        e.preventDefault();
        const form = e.target.closest('form');
        if (!form) return;

        try {
            const data     = Object.fromEntries(new FormData(form));
            const category = await categoryService.create(data);
            events.emit('category:created', category);
            form.reset();
        } catch (error) {
            logger.error('[posts] create category:', error);
            events.emit('category:error', error);
        }
    });

    events.on('category:created', () => {
        flash(document.getElementById('category-success'), 3000);
    });
});