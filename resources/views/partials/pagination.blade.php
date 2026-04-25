@if($data instanceof \Illuminate\Pagination\LengthAwarePaginator || $data instanceof \Illuminate\Pagination\Paginator)
    <div class="mt-3 d-flex justify-content-between align-items-center">

        {{-- Info nombre d'éléments --}}
        <div class="text-muted small">
            Affichage de {{ $data->firstItem() }} à {{ $data->lastItem() }}
            sur {{ $data->total() }} éléments
        </div>

        {{-- Pagination avec AJAX --}}
        <nav>
            <ul class="pagination pagination-sm mb-0">

                {{-- Previous --}}
                <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link ajax-page" href="#" data-url="{{ $data->previousPageUrl() }}">«</a>
                </li>

                {{-- Pages --}}
                @for ($i = 1; $i <= $data->lastPage(); $i++)
                    <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link ajax-page" href="#" data-url="{{ $data->url($i) }}">
                            {{ $i }}
                        </a>
                    </li>
                @endfor

                {{-- Next --}}
                <li class="page-item {{ !$data->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link ajax-page" href="#" data-url="{{ $data->nextPageUrl() }}">»</a>
                </li>
            </ul>
        </nav>
    </div>
@endif