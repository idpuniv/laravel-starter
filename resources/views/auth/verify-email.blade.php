<x-guest-layout>
    <div class="container d-flex flex-column flex-grow-1">
        <div class="row d-flex flex-column flex-grow-1 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-none shadow-md-lg rounded-4 border-0">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 icon-circle-lg">
                                <i class="bi bi-envelope-check text-primary"></i>
                            </div>
                            <h4 class="mb-2">{{ __('Vérifiez votre adresse e-mail') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('Merci pour votre inscription !') }}
                            </p>
                        </div>

                        <!-- Info Message -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ __("Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.") }}
                        </div>

                        <!-- Success Message -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __("Un nouveau lien de vérification a été envoyé à votre adresse e-mail.") }}
                            </div>
                        @endif

                        <p class="text-muted small mb-4">
                            {{ __("Si vous n'avez pas reçu l'e-mail, cliquez sur le bouton ci-dessous pour en demander un autre.") }}
                        </p>

                        <!-- Buttons -->
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="bi bi-envelope-paper me-2"></i>
                                    {{ __('Renvoyer') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ __('Se déconnecter') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>