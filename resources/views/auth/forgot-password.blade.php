<x-guest-layout>
    <div class="container d-flex flex-column flex-grow-1">
        <div class="row d-flex flex-column flex-grow-1 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-none shadow-md-lg rounded-4 border-0">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-warning bg-opacity-10 icon-circle-lg">
                                <i class="bi bi-question-circle text-warning"></i>
                            </div>
                            <h4 class="mb-2">{{ __('Mot de passe oublié ?') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('Pas de problème. Indiquez simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe.') }}
                            </p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium">
                                    {{ __('Adresse e-mail') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-envelope text-secondary"></i>
                                    </span>
                                    <input type="email" 
                                           id="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="nom@exemple.com"
                                           required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                    <i class="bi bi-send me-2"></i>
                                    {{ __('Envoyer le lien de réinitialisation') }}
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-link text-muted">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    {{ __('Retour à la connexion') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>