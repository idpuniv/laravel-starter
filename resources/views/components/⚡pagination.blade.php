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
            <div class="text-secondary small">
                Affichage de {{ $firstItem }} à {{ $lastItem }}
                sur <strong>{{ $total }}</strong> éléments
            </div>

            <nav aria-label="Pagination">
                <ul class="pagination pagination-sm gap-1 mb-0">

                    {{-- Première page --}}
                    <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
                        <button type="button"
                                class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square"
                                wire:click="firstPage"
                                {{ $onFirstPage ? 'disabled' : '' }}
                                aria-label="Première page">
                            <i class="bi bi-chevron-double-left pg-icon"></i>
                        </button>
                    </li>

                    {{-- Précédent --}}
                    <li class="page-item {{ $onFirstPage ? 'disabled' : '' }}">
                        <button type="button"
                                class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square"
                                wire:click="previousPage"
                                {{ $onFirstPage ? 'disabled' : '' }}
                                aria-label="Page précédente">
                            <i class="bi bi-chevron-left pg-icon"></i>
                        </button>
                    </li>

                    @php
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                        $showLeftDots = $start > 2;
                        $showRightDots = $end < $lastPage - 1;
                    @endphp

                    {{-- Première page numérotée --}}
                    @if($start > 1)
                        <li class="page-item">
                            <button type="button" class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square" wire:click="goToPage(1)">1</button>
                        </li>
                    @endif

                    {{-- Points à gauche --}}
                    @if($showLeftDots)
                        <li class="page-item disabled">
                            <span class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square">&hellip;</span>
                        </li>
                    @endif

                    {{-- Pages centrales --}}
                    @for($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <button type="button"
                                    class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square"
                                    wire:click="goToPage({{ $i }})"
                                    @if($currentPage == $i) aria-current="page" @endif>
                                {{ $i }}
                            </button>
                        </li>
                    @endfor

                    {{-- Points à droite --}}
                    @if($showRightDots)
                        <li class="page-item disabled">
                            <span class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square">&hellip;</span>
                        </li>
                    @endif

                    {{-- Dernière page numérotée --}}
                    @if($end < $lastPage)
                        <li class="page-item">
                            <button type="button" class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square" wire:click="goToPage({{ $lastPage }})">
                                {{ $lastPage }}
                            </button>
                        </li>
                    @endif

                    {{-- Suivant --}}
                    <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
                        <button type="button"
                                class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square"
                                wire:click="nextPage"
                                {{ !$hasMorePages ? 'disabled' : '' }}
                                aria-label="Page suivante">
                            <i class="bi bi-chevron-right pg-icon"></i>
                        </button>
                    </li>

                    {{-- Dernière page --}}
                    <li class="page-item {{ !$hasMorePages ? 'disabled' : '' }}">
                        <button type="button"
                                class="page-link rounded-3 d-inline-flex align-items-center justify-content-center pg-square"
                                wire:click="lastPage"
                                {{ !$hasMorePages ? 'disabled' : '' }}
                                aria-label="Dernière page">
                            <i class="bi bi-chevron-double-right pg-icon"></i>
                        </button>
                    </li>

                </ul>
            </nav>
        </div>
    @endif

    <style>
        /* Uniquement du dimensionnement, aucune couleur : Bootstrap gère
           hover / active / disabled nativement via .page-item/.page-link */
        .pg-square {
            width: 2.25rem;
            height: 2.25rem;
            padding: 0;
            line-height: 1;
        }
        .pg-icon {
            font-size: 0.875rem;
            line-height: 1;
        }
    </style>
</div>