<section>
    <div class="mb-4">
        <h3 class="fw-bold fs-5 mb-2 text-danger">{{ __('Delete Account') }}</h3>
        <p class="text-secondary small mb-0">{{ __('Permanently delete your account and all data.') }}</p>
    </div>

    <div class="alert alert-warning mb-4">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ __('Once your account is deleted, all data will be permanently removed.') }}
    </div>

    <!-- Delete Button -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
    </button>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-semibold" id="confirmDeleteLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Confirm Account Deletion') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-body">
                        <p class="mb-3">{{ __('Are you sure you want to delete your account?') }}</p>
                        <p class="text-secondary small mb-4">{{ __('This action cannot be undone. All your data will be permanently deleted.') }}</p>

                        <div class="mb-3">
                            <label for="delete_password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" 
                                   id="delete_password" 
                                   name="password" 
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                   placeholder="{{ __('Enter your password to confirm') }}" 
                                   required>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>{{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>{{ __('Delete Permanently') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>