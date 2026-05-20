<?php

use Livewire\Component;
use Livewire\Attributes\Reactive;

new class extends Component {
    #[Reactive] public $currentPage;
    #[Reactive] public $lastPage;
    #[Reactive] public $total;
    #[Reactive] public $firstItem;
    #[Reactive] public $lastItem;
    #[Reactive] public $hasPages;
    #[Reactive] public $onFirstPage;
    #[Reactive] public $hasMorePages;

    public function goToPage($page)
    {
        $this->dispatch('goToPage', page: $page);
    }

    // Renommé pour éviter le conflit
    public function goToFirstPage()
    {
        $this->dispatch('goToPage', page: 1);
    }

    // Renommé pour éviter le conflit avec la propriété $lastPage
    public function goToLastPage()
    {
        $this->dispatch('goToPage', page: $this->lastPage);
    }

    public function previousPage()
    {
        $this->dispatch('previousPage');
    }

    public function nextPage()
    {
        $this->dispatch('nextPage');
    }
};

?>

<div>
    @if($hasPages)
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="text-secondary small px-3 py-2 rounded">
                Affichage de {{ $firstItem }} à {{ $lastItem }}
                sur <strong>{{ $total }}</strong> éléments
            </div>

            <nav aria-label="Pagination">
                <ul class="pagination mb-0 gap-1">
                    {{-- Première page --}}
                    <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
                        <button type="button" class="page-link rounded-3" wire:click="firstPage" {{ $onFirstPage ? 'disabled' : '' }} aria-label="Première page">
                            <i class="bi bi-chevron-double-left"></i>
                        </button>
                    </li>

                    {{-- Précédent --}}
                    <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
                        <button type="button" class="page-link rounded-3" wire:click="previousPage" {{ $onFirstPage ? 'disabled' : '' }} aria-label="Page précédente">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>

                    {{-- Pages avec points de suspension --}}
                    @php
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                        $showLeftDots = $start > 2;
                        $showRightDots = $end < $lastPage - 1;
                    @endphp

                    {{-- Première page --}}
                    @if($start > 1)
                        <li class="page-item">
                            <button type="button" class="page-link rounded-3" wire:click="goToPage(1)">1</button>
                        </li>
                    @endif

                    {{-- Points à gauche --}}
                    @if($showLeftDots)
                        <li class="page-item disabled">
                            <span class="page-link rounded-3 border-0 bg-transparent">...</span>
                        </li>
                    @endif

                    {{-- Pages centrales --}}
                    @for($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <button type="button" class="page-link rounded-3 {{ $currentPage == $i ? 'fw-semibold' : '' }}" wire:click="goToPage({{ $i }})">
                                {{ $i }}
                            </button>
                        </li>
                    @endfor

                    {{-- Points à droite --}}
                    @if($showRightDots)
                        <li class="page-item disabled">
                            <span class="page-link rounded-3 border-0 bg-transparent">...</span>
                        </li>
                    @endif

                    {{-- Dernière page --}}
                    @if($end < $lastPage)
                        <li class="page-item">
                            <button type="button" class="page-link rounded-3" wire:click="goToPage({{ $lastPage }})">
                                {{ $lastPage }}
                            </button>
                        </li>
                    @endif

                    {{-- Suivant --}}
                    <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
                        <button type="button" class="page-link rounded-3" wire:click="nextPage" {{ !$hasMorePages ? 'disabled' : '' }} aria-label="Page suivante">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>

                    {{-- Dernière page --}}
                    <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
                        <button type="button" class="page-link rounded-3" wire:click="lastPage" {{ !$hasMorePages ? 'disabled' : '' }} aria-label="Dernière page">
                            <i class="bi bi-chevron-double-right"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    @endif
</div>