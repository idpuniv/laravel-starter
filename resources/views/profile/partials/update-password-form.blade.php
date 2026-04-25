<section id="security">
    <div class="mb-4">
        <h3 class="fw-bold fs-5 mb-2">{{ __('Update Password') }}</h3>
        <p class="text-secondary small mb-0">{{ __('Ensure your account is using a secure password.') }}</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="mb-3">
            <label for="current_password" class="form-label fw-semibold">{{ __('Current Password') }}</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       class="form-control border-start-0 @error('current_password', 'updatePassword') is-invalid @enderror" 
                       autocomplete="current-password">
            </div>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">{{ __('New Password') }}</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-key"></i>
                </span>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control border-start-0 @error('password', 'updatePassword') is-invalid @enderror" 
                       autocomplete="new-password">
            </div>
            @error('password', 'updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="form-text small text-muted">
                {{ __('Password must be at least 8 characters.') }}
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-check-circle"></i>
                </span>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       class="form-control border-start-0" 
                       autocomplete="new-password">
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>{{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div class="text-success small">
                    <i class="bi bi-check-circle me-1"></i>{{ __('Saved!') }}
                </div>
            @endif
        </div>
    </form>
</section>