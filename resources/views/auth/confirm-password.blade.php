<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-none shadow-md-lg rounded-4 border-0">
                    <div class="card-body p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 icon-circle-lg">
                                <i class="bi bi-shield-lock text-primary"></i>
                            </div>
                            <h4 class="mb-2">{{ __('Confirm Password') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('This is a secure area. Please confirm your password.') }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">
                                    {{ __('Password') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-key text-secondary"></i>
                                    </span>
                                    <input type="password" 
                                           id="password" 
                                           class="form-control border-start-0 @error('password') is-invalid @enderror"
                                           name="password"
                                           placeholder="Enter your password"
                                           required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ __('Confirm Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>