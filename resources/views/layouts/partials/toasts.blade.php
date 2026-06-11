@if(session('success'))
    <div class="toast position-fixed top-0 end-0 m-3 show" role="alert" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-body bg-success text-white">
            <div class="d-flex align-items-center">
                <span class="me-2">✓</span>
                <span class="flex-grow-1">{{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="toast position-fixed top-0 end-0 m-3 show" role="alert" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-body bg-warning text-dark">
            <div class="d-flex align-items-center">
                <span class="me-2">⚠</span>
                <span class="flex-grow-1">{{ session('warning') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif

@if(session('danger'))
    <div class="toast position-fixed top-0 end-0 m-3 show" role="alert" data-bs-autohide="true" data-bs-delay="3000">
        <div class="toast-body bg-danger text-white">
            <div class="d-flex align-items-center">
                <span class="me-2">✗</span>
                <span class="flex-grow-1">{{ session('danger') }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif