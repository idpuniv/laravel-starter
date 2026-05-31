// resources/js/tom-select.js
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

window.TomSelect = TomSelect;


document.addEventListener('DOMContentLoaded', function () {
    new TomSelect(".tom-select", {
        plugins: ['remove_button'],
        maxItems: null,
        persist: false,
        create: false
    });
});