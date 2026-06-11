<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ setting('user.theme') }}">

<head>
    <script src="{{ asset('js/color-modes.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <title>Inscription · Sommet de l'Innovation 2025</title>
    <script src="{{ asset('js/color-modes.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600;9..144,700;9..144,800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')
</head>

<body {{ $attributes ?? collect()->merge(['class' => 'default']) }}>

<div class="container py-5 my-4">
    <div class="row g-0 justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden fade-up stagger-1">
                <div class="row g-0">
                    
                    {{-- BANDEAU GRADIENT (utilise la classe utility gradient-hero) --}}
                    <div class="col-md-5 gradient-primary text-white p-4 p-xl-5 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill mb-4 fw-semibold">
                                <i class="bi bi-calendar3 me-2"></i>15-17 OCT 2025
                            </span>
                            <h1 class="display-5 fw-bold mb-3" style="font-family: 'Fraunces', serif;">Sommet de<br>l'Innovation</h1>
                            <p class="text-white-50 mb-4">Rejoignez 650+ leaders, visionnaires et créateurs qui façonnent l'avenir. 3 jours d'immersion, de réseaux réels et de découvertes radicales.</p>
                        </div>
                        
                        <div class="row g-3 mt-4">
                            <div class="col-4">
                                <div class="text-center">
                                    <div class="h3 fw-bold mb-0">45+</div>
                                    <div class="small text-white-50">Speakers</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <div class="h3 fw-bold mb-0">80+</div>
                                    <div class="small text-white-50">Investisseurs</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <div class="h3 fw-bold mb-0">12</div>
                                    <div class="small text-white-50">Ateliers</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-flex align-items-center gap-2 small text-white-50">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Palais des Congrès, Paris</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- PARTIE FORMULAIRE --}}
                    <div class="col-md-7 bg-body p-4 p-xl-5">
                        <div class="mb-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-semibold">
                                <i class="bi bi-ticket-perforated me-2"></i>Réservez votre place
                            </span>
                            <h2 class="h3 fw-bold mb-1" style="font-family: 'Fraunces', serif;">Créez votre <span class="text-primary">pass</span></h2>
                            <p class="text-muted small">Remplissez ce formulaire pour confirmer votre participation</p>
                        </div>
                        
                        <form id="inscriptionForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg rounded-3" id="fullname" placeholder="Sophie Delacroix" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Email professionnel <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg rounded-3" id="email" placeholder="sophie@entreprise.com" required>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Entreprise / Organisation</label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="company" placeholder="Nom de votre structure">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Fonction</label>
                                    <select class="form-select form-select-lg rounded-3" id="role">
                                        <option value="">Sélectionnez</option>
                                        <option>Dirigeant / Founder</option>
                                        <option>Directeur·ice innovation</option>
                                        <option>Chef de projet tech</option>
                                        <option>Étudiant / Chercheur</option>
                                        <option>Autre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <label class="form-label fw-semibold small mb-2">Choisissez votre formule</label>
                            <div class="mb-4" id="ticketGroup">
                                <label class="ticket-radio-card d-flex align-items-center gap-3 p-3 border rounded-3 mb-2">
                                    <input type="radio" name="ticket" value="standard" class="form-check-input m-0" style="width: 1.2rem; height: 1.2rem;" checked>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Pass Standard <span class="text-primary ms-2">— 249€</span></div>
                                        <div class="small text-muted">Accès conférences + afterworks + networking</div>
                                    </div>
                                </label>
                                <label class="ticket-radio-card d-flex align-items-center gap-3 p-3 border rounded-3 mb-2">
                                    <input type="radio" name="ticket" value="premium" class="form-check-input m-0" style="width: 1.2rem; height: 1.2rem;">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Pass Premium <span class="text-primary ms-2">— 399€</span></div>
                                        <div class="small text-muted">Ateliers VIP, déjeuners inclus, goodies exclusifs</div>
                                    </div>
                                </label>
                                <label class="ticket-radio-card d-flex align-items-center gap-3 p-3 border rounded-3">
                                    <input type="radio" name="ticket" value="early" class="form-check-input m-0" style="width: 1.2rem; height: 1.2rem;">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Early Bird <span class="text-warning ms-2">— 149€</span></div>
                                        <div class="small text-muted">Tarif préférentiel jusqu'au 31 août</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Message / besoin spécifique (optionnel)</label>
                                <textarea class="form-control rounded-3" rows="2" id="message" placeholder="Accessibilité, demande particulière..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 py-3 fw-semibold">
                                <i class="bi bi-ticket-perforated me-2"></i>Valider mon inscription
                            </button>
                            
                            <div class="d-flex align-items-center gap-3 my-4">
                                <hr class="flex-grow-1">
                                <span class="small text-muted">Paiement sécurisé</span>
                                <hr class="flex-grow-1">
                            </div>
                            
                            <div class="text-center">
                                <span class="small text-muted">Déjà un compte ?</span>
                                <a href="#" class="small fw-semibold text-decoration-none text-success">Se connecter</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Témoignage --}}
            <div class="row mt-5 fade-up stagger-6">
                <div class="col-12">
                    <div class="card border-0 bg-light rounded-4 p-4 shadow-sm">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="bi bi-quote display-6 text-warning opacity-75"></i>
                            <div>
                                <p class="fs-5 fst-italic mb-2">"L'innovation ne se décrète pas, elle se provoque. Ce sommet en est le laboratoire."</p>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <i class="bi bi-star-fill text-warning small"></i>
                                    <i class="bi bi-star-fill text-warning small"></i>
                                    <i class="bi bi-star-fill text-warning small"></i>
                                    <i class="bi bi-star-fill text-warning small"></i>
                                    <i class="bi bi-star-fill text-warning small"></i>
                                    <span class="ms-2 fw-semibold small text-success">— Sarah K., directrice innovation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('inscriptionForm');
        const radioCards = document.querySelectorAll('.ticket-radio-card');
        
        radioCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            card.addEventListener('click', (e) => {
                if (e.target !== radio) radio.checked = true;
                radioCards.forEach(c => c.classList.remove('border-primary', 'bg-primary', 'bg-opacity-5'));
                card.classList.add('border-primary', 'bg-primary', 'bg-opacity-5');
            });
            if (radio.checked) card.classList.add('border-primary', 'bg-primary', 'bg-opacity-5');
        });
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!fullname) { alert('✏️ Veuillez indiquer votre nom complet'); document.getElementById('fullname').focus(); return; }
            if (!email || !email.includes('@')) { alert('📧 Email professionnel requis'); document.getElementById('email').focus(); return; }
            
            let selectedTicket = 'Standard (249€)';
            const selectedRadio = document.querySelector('input[name="ticket"]:checked');
            if (selectedRadio?.value === 'premium') selectedTicket = 'Premium (399€)';
            else if (selectedRadio?.value === 'early') selectedTicket = 'Early Bird (149€)';
            
            alert(`✅ Merci ${fullname.split(' ')[0]} ! Votre place ${selectedTicket} est réservée.`);
            form.reset();
            radioCards.forEach(c => c.classList.remove('border-primary', 'bg-primary', 'bg-opacity-5'));
            document.querySelector('input[name="ticket"][value="standard"]').checked = true;
            document.querySelector('.ticket-radio-card:first-child').classList.add('border-primary', 'bg-primary', 'bg-opacity-5');
        });
    })();
</script>

@stack('scripts')

</body>
</html>