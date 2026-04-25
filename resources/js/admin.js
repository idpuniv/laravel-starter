import { 
    Layout, 
    PushMenu, 
    Treeview 
} from 'admin-lte';



document.addEventListener('DOMContentLoaded', function(){
    console.log('adding to body')
    // Correction de la syntaxe : une virgule entre chaque classe
    document.body.classList.add('layout-fixed', 'sidebar-expand-lg', 'bg-body-tertiary');
    
    // Une fois les classes ajoutées, on initialise AdminLTE
    // (Assure-toi d'avoir importé Layout, PushMenu, etc. en haut du fichier)
});