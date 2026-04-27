<x-guest-layout>
    <div class="main-content">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Modifications pour Bootstrap 5.3</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><code>mr-*</code> → <code>me-*</code></li>
                    <li class="list-group-item"><code>ml-auto</code> → <code>ms-auto</code></li>
                    <li class="list-group-item"><code>pr-2</code> → <code>pe-2</code></li>
                    <li class="list-group-item"><code>data-toggle</code> → <code>data-bs-toggle</code></li>
                    <li class="list-group-item"><code>dropdown-menu-right</code> → <code>dropdown-menu-end</code></li>
                    <li class="list-group-item"><code>badge-*</code> → <code>bg-*</code></li>
                    <li class="list-group-item">Utilisation des icônes Bootstrap Icons</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Select SANS barre de recherche -->
<div class="custom-select">
    <button type="button" 
            class="select-button btn border w-100 text-start position-relative"
            data-bs-toggle="dropdown" 
            aria-expanded="false" 
            aria-haspopup="listbox"
            aria-labelledby="select-label-2"
            style="padding-right: 2.5rem;">
        <span class="selected-option" id="select-label-2">Sélectionnez une option</span>
        <span class="dropdown-arrow position-absolute end-0 me-3"
              style="top: 50%; transform: translateY(-50%);">
            <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
        </span>
    </button>
    
    <ul class="dropdown-menu w-100" role="listbox" aria-labelledby="select-label-2">
    <li class="dropdown-item select-option" role="option" data-value="cat" aria-selected="false">Chat</li>
    <li class="dropdown-item select-option" role="option" data-value="dog" aria-selected="false">Chien</li>
    <li class="dropdown-item select-option" role="option" data-value="bird" aria-selected="false">Oiseau</li>
    <li class="dropdown-item select-option" role="option" data-value="fish" aria-selected="false">Poisson</li>
    <li class="dropdown-divider"></li>
    <li class="dropdown-item select-option" role="option" data-value="lion" aria-selected="false">Lion</li>
    <li class="dropdown-item select-option" role="option" data-value="tiger" aria-selected="false">Tigre</li>
    <li class="dropdown-item select-option" role="option" data-value="elephant" aria-selected="false">Éléphant</li>
    <li class="dropdown-item select-option" role="option" data-value="giraffe" aria-selected="false">Girafe</li>
    <li class="dropdown-item select-option" role="option" data-value="zebra" aria-selected="false">Zèbre</li>
    <li class="dropdown-item select-option" role="option" data-value="kangaroo" aria-selected="false">Kangourou</li>
    <li class="dropdown-item select-option" role="option" data-value="panda" aria-selected="false">Panda</li>
    <li class="dropdown-item select-option" role="option" data-value="koala" aria-selected="false">Koala</li>
    <li class="dropdown-item select-option" role="option" data-value="monkey" aria-selected="false">Singe</li>
    <li class="dropdown-item select-option" role="option" data-value="snake" aria-selected="false">Serpent</li>
    <li class="dropdown-item select-option" role="option" data-value="eagle" aria-selected="false">Aigle</li>
    <li class="dropdown-item select-option" role="option" data-value="shark" aria-selected="false">Requin</li>
    <li class="dropdown-item select-option" role="option" data-value="dolphin" aria-selected="false">Dauphin</li>
    <li class="dropdown-divider"></li>
    <li class="dropdown-item select-option" role="option" data-value="frog" aria-selected="false">Grenouille</li>
    <li class="dropdown-item select-option" role="option" data-value="butterfly" aria-selected="false">Papillon</li>
    <li class="dropdown-item select-option" role="option" data-value="bee" aria-selected="false">Abeille</li>
    <li class="dropdown-item select-option" role="option" data-value="ant" aria-selected="false">Fourmi</li>
</ul>
    
    <input type="hidden" name="custom_select_2" class="select-value" value="">
</div>

<div class="custom-select">
    <button type="button" 
            class="select-button btn border w-100 text-start position-relative"
            data-bs-toggle="dropdown" 
            aria-expanded="false" 
            aria-haspopup="listbox"
            aria-labelledby="select-label-1"
            style="padding-right: 2.5rem;">
        <span class="selected-option" id="select-label-1">Sélectionnez une option</span>
        <span class="dropdown-arrow position-absolute end-0 me-3"
              style="top: 50%; transform: translateY(-50%);">
            <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
        </span>
    </button>
    
    <ul class="dropdown-menu w-100" role="listbox" aria-labelledby="select-label-1">
        <!-- Barre de recherche optionnelle -->
        <li class="mb-2">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control border-start-0 search-input"
                       placeholder="Rechercher..." style="box-shadow: none;"
                       aria-label="Rechercher dans la liste">
            </div>
        </li>
        <!-- Liste des options -->
        <li class="dropdown-item select-option" role="option" data-value="france" aria-selected="false">France</li>
        <li class="dropdown-item select-option" role="option" data-value="senegal" aria-selected="false">Sénégal</li>
        <li class="dropdown-item select-option" role="option" data-value="cotedivoire" aria-selected="false">Côte d'Ivoire</li>
        <li class="dropdown-item select-option" role="option" data-value="cameroun" aria-selected="false">Cameroun</li>
        <li class="dropdown-item select-option" role="option" data-value="maroc" aria-selected="false">Maroc</li>
        <li class="dropdown-item select-option" role="option" data-value="algerie" aria-selected="false">Algérie</li>
        <li class="dropdown-item select-option" role="option" data-value="tunisie" aria-selected="false">Tunisie</li>
        <li class="dropdown-item select-option" role="option" data-value="mali" aria-selected="false">Mali</li>
        <li class="dropdown-item select-option" role="option" data-value="burkina" aria-selected="false">Burkina Faso</li>
        <li class="dropdown-item select-option" role="option" data-value="niger" aria-selected="false">Niger</li>
        <li class="dropdown-item select-option" role="option" data-value="guinee" aria-selected="false">Guinée</li>
        <li class="dropdown-item select-option" role="option" data-value="rdc" aria-selected="false">République Démocratique du Congo</li>
        <li class="dropdown-item select-option" role="option" data-value="benin" aria-selected="false">Bénin</li>
        <li class="dropdown-item select-option" role="option" data-value="togo" aria-selected="false">Togo</li>
        <li class="dropdown-item select-option" role="option" data-value="rwanda" aria-selected="false">Rwanda</li>
        <li class="dropdown-item select-option" role="option" data-value="madagascar" aria-selected="false">Madagascar</li>
        <li class="dropdown-item select-option" role="option" data-value="canada" aria-selected="false">Canada</li>
        <li class="dropdown-item select-option" role="option" data-value="belgique" aria-selected="false">Belgique</li>
        <li class="dropdown-item select-option" role="option" data-value="suisse" aria-selected="false">Suisse</li>
        <li class="dropdown-item select-option" role="option" data-value="luxembourg" aria-selected="false">Luxembourg</li>
        <li class="dropdown-item select-option" role="option" data-value="monaco" aria-selected="false">Monaco</li>
    </ul>
    
    <input type="hidden" name="custom_select_1" class="select-value" value="">
</div>
</x-guest-layout>