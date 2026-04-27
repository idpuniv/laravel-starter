<section>
    <div class="mb-4">
        <h3 class="fw-bold fs-5 mb-2">{{ __('Profile Information') }}</h3>
        <p class="text-secondary small mb-0">{{ __("Update your account's profile information.") }}</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">{{ __('Full Name') }}</label>
            <div class="input-group">
                <span class="input-group-text border-end-0">
                    <i class="bi bi-person"></i>
                </span>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control border-start-0 @error('name') is-invalid @enderror" 
                       value="{{ old('name', $user->name) }}" 
                       required autofocus>
            </div>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
            <div class="input-group">
                <span class="input-group-text border-end-0">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control border-start-0 @error('email') is-invalid @enderror" 
                       value="{{ old('email', $user->email) }}" 
                       required>
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3 small">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="btn btn-link btn-sm p-0 ms-2">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </div>
            @endif

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mt-3 small">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ __('A new verification link has been sent to your email address.') }}
                </div>
            @endif
        </div>

        <!-- Buttons -->
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>{{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="text-success small">
                    <i class="bi bi-check-circle me-1"></i>{{ __('Saved!') }}
                </div>
            @endif
        </div>
    </form>
</section>