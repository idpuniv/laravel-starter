<x-guest-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <!-- Header -->
                <div class="mb-4">
                    <h2 class="fw-bold text-primary">{{ __('Profile') }}</h2>
                    <p class="text-secondary">{{ __('Manage your account settings') }}</p>
                </div>

                <!-- Profile Information -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                @can('delete', $user)
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</x-guest-layout>