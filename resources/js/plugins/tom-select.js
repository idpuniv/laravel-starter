import 'tom-select/dist/css/tom-select.bootstrap5.css';
import TomSelect from 'tom-select';

document.querySelectorAll(".ts-select").forEach((el) => {
    new TomSelect(el, {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        },
        render: {
            option_create: function(data, escape) {
                return '<div class="create">' + window.translations.add + ' <strong>' + escape(data.input) + '</strong>&hellip;</div>';
            },
            no_results: function(data, escape) {
                return '<div class="no-results">' + window.translations.no_results + '</div>';
            }
        }
    });
});



// document.querySelectorAll(".my-tom-select").forEach((el) => {
//     new TomSelect(el, {
//         create: true,
//         sortField: {
//             field: "text",
//             direction: "asc"
//         },
//         render: {
//             option_create: function(data, escape) {
//                 return '<div class="create">Ajouter <strong>' + escape(data.input) + '</strong>&hellip;</div>';
//             },
//             no_results: function(data, escape) {
//                 return '<div class="no-results">Aucun résultat</div>';
//             }
//         },
//         onCreate: function(input, callback) {
//             fetch('/categories', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//                     'Accept': 'application/json',
//                 },
//                 body: JSON.stringify({ name: input })
//             })
//             .then(res => res.json())
//             .then(data => {
//                 // Ajoute et sélectionne la nouvelle option avec l'ID retourné par le serveur
//                 callback({ value: data.id, text: data.name });
//             })
//             .catch(() => {
//                 // En cas d'erreur, on annule la création
//                 callback();
//             });
//         }
//     });
// });