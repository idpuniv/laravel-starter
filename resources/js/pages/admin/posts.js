import { categoryService } from '@/services/categoryService';

const categories = await categoryService.getAll();
console.log(categories);

const newCategory = await categoryService.create({
    name: 'Brevets',
    slug: 'brevets',
    description: 'Articles sur les brevets'
});

const category = await categoryService.getById(5);

await categoryService.update(5, {
    name: 'Nouveau nom'
});

await categoryService.delete(5);