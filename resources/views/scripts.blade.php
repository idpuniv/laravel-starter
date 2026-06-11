@once
    @push('scripts')
        @if (file_exists(public_path('vendor/datatable/datatable-manager.js')))
            <script src="{{ asset('vendor/datatable/datatable-manager.js') }}"></script>
        @else
            <script src="{{ route('datatable.script') }}"></script>
        @endif
    @endpush
@endonce