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
                            <h4 class="mb-2">{{ __('Verify Your Email Address') }}</h4>
                            <p class="text-secondary small mb-0">
                                {{ __('Thanks for signing up!') }}
                            </p>
                        </div>

                        <!-- Info Message -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ __("Before getting started, could you verify your email address by clicking on the link we just emailed to you?") }}
                        </div>

                        <!-- Success Message -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p class="text-muted small mb-4">
                            {{ __("If you didn't receive the email, click the button below to request another.") }}
                        </p>

                        <!-- Buttons -->
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                    <i class="bi bi-envelope-paper me-2"></i>
                                    {{ __('Resend Verification Email') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger px-4 py-2 fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>