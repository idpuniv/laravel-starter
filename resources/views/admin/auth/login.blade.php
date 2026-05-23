<x-guest-layout>
    <div class="container d-flex flex-column flex-grow-1">
        <div class="row container-fluid d-flex flex-column flex-grow-1 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-none shadow-md-lg rounded-4 border-0">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-danger bg-opacity-10 icon-circle-lg mb-3 mx-auto">
                                <i class="bi bi-shield-lock text-danger"></i>
                            </div>
                            <h4 class="mb-2">{{ __('Portail d\'administration') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('Accès réservé aux administrateurs.') }}
                            </p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">
                                    {{ __('Adresse e-mail') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-secondary border-end-0">
                                        <i class="bi bi-envelope text-secondary"></i>
                                    </span>
                                    <input type="email" id="email"
                                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="admin@exemple.com"
                                        required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">
                                    {{ __('Mot de passe') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-secondary border-end-0">
                                        <i class="bi bi-key text-secondary"></i>
                                    </span>
                                    <input type="password" id="password"
                                        class="form-control border-start-0 @error('password') is-invalid @enderror"
                                        name="password" placeholder="••••••" required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">
                                    {{ __('Se souvenir de moi') }}
                                </label>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
                                @if (Route::has('admin.password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                        {{ __('Mot de passe oublié ?') }}
                                    </a>
                                @endif

                                <button type="submit" class="btn btn-danger fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    {{ __('Se connecter') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>