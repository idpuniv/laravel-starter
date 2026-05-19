<x-guest-layout>
    <div class="container d-flex flex-column flex-grow-1">
        <div class="row container-fluid d-flex flex-column flex-grow-1 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-none shadow-md-lg rounded-4 border-0">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 icon-circle-lg mb-3 mx-auto">
                                <i class="bi bi-shield-lock text-primary"></i>
                            </div>
                            <h4 class="mb-2">{{ __('Vérification 2FA') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('Veuillez entrer le code de vérification envoyé à votre adresse e-mail.') }}
                            </p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- 2FA Verification Form -->
                        <form method="POST" action="{{ route('2fa.verify.post') }}">
                            @csrf

                            <!-- Code Field -->
                            <div class="mb-4">
                                <label for="code" class="form-label fw-medium">
                                    {{ __('Code de vérification') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-secondary border-end-0">
                                        <i class="bi bi-digit text-secondary"></i>
                                    </span>
                                    <input type="text" id="code"
                                        class="form-control border-start-0"
                                        name="code" 
                                        placeholder="xxxxxx"
                                        required autofocus>
                                </div>
                                <div class="form-text small mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('Un code à 6 chiffres a été envoyé à votre adresse e-mail.') }}
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold w-100 w-sm-auto">
                                    <i class="bi bi-check-lg me-2"></i>
                                    {{ __('Valider le code') }}
                                </button>
                            </div>
                        </form>

                        <!-- Resend Code Form -->
                        <form id="resend-form" method="POST" action="{{ route('2fa.resend') }}" class="mt-4 text-center">
                            @csrf
                            <button type="submit" id="resend-button" class="btn btn-link text-decoration-none small" disabled>
                                <i class="bi bi-arrow-repeat me-1"></i>
                                {{ __('Renvoyer le code') }} (<span id="timer">--:--</span>)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let expiresAt = new Date("{{ $expiresAt }}").getTime();
        let resendButton = document.getElementById('resend-button');
        let timerSpan = document.getElementById('timer');

        function updateTimer() {
            let now = new Date().getTime();
            let distance = expiresAt - now;

            if (distance <= 0) {
                timerSpan.innerText = '00:00';
                resendButton.disabled = false;
                clearInterval(interval);
            } else {
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerSpan.innerText = (minutes < 10 ? '0' : '') + minutes + ':' + 
                                      (seconds < 10 ? '0' : '') + seconds;
            }
        }

        let interval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</x-guest-layout>