<x-guest-layout>
    <div class="container d-flex flex-column flex-grow-1">
        <div class="row d-flex flex-column flex-grow-1 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-none shadow-md-lg rounded-4 border-0">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 icon-circle-lg">
                                <i class="bi bi-key text-primary"></i>
                            </div>
                            <h4 class="mb-2">{{ __('Reset Password') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('Enter your new password below.') }}
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ __('Please fix the errors below.') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">
                                    {{ __('Email Address') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-envelope text-secondary"></i>
                                    </span>
                                    <input type="email" 
                                           id="email" 
                                           class="form-control border-start-0 @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email', $request->email) }}"
                                           placeholder="name@example.com"
                                           required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- New Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">
                                    {{ __('New Password') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-lock text-secondary"></i>
                                    </span>
                                    <input type="password" 
                                           id="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="••••••"
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text small text-muted">
                                    {{ __('Password must be at least 8 characters.') }}
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-medium">
                                    {{ __('Confirm Password') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-check-circle text-secondary"></i>
                                    </span>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror"
                                           name="password_confirmation"
                                           placeholder="••••••"
                                           required>
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                    <i class="bi bi-arrow-repeat me-2"></i>
                                    {{ __('Reset Password') }}
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-decoration-none small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    {{ __('Back to Login') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>