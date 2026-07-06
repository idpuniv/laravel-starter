<x-app-layout>
    @push('styles')
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
    @endpush
    <x-theme-switch />
    {{-- Disabled: real Blade comment, not an HTML comment. HTML comments do
         not stop Blade from compiling and executing directives inside them. --}}
    {{-- @include('layouts.partials.alert-top') --}}
    <a href="#main-content" class="skip-to-content">{{ __('Skip to main content') }}</a>

    <div id="sidebarOverlay" class="sidebar-overlay" aria-hidden="true"></div>

    <div class="app-container">
        <aside id="sidebar" class="sidebar" aria-label="{{ __('Main menu') }}">
            <button id="sidebarHideBtn" class="sidebar-hide-btn" type="button"
                aria-label="{{ __('Hide sidebar') }}">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
            </button>

            <div class="sidebar-content">
                <div class="sidebar-profile text-center">
                    {{-- Decorative: the full name right below already provides
                         the accessible label, avoid a conflicting aria-label. --}}
                    <div class="avatar d-inline-flex mx-auto" aria-hidden="true">TA</div>
                    <div class="profile-name text-muted">Thomas Anderson</div>
                </div>

                <nav class="mt-2" aria-label="{{ __('Main navigation') }}">
                    <ul class="nav sidebar-nav flex-column" role="menubar" data-accordion="false">
                        @foreach ($sidebarMenus as $menu)
                        @php
                        // Only keep children whose route actually exists, so a
                        // renamed/removed route never renders a dead link.
                        $validChildren = array_filter($menu['children'] ?? [], function ($child) {
                        return isset($child['route']) && Route::has($child['route']);
                        });

                        $hasChildren = !empty($validChildren);
                        $hasRoute = isset($menu['route']) && Route::has($menu['route']);

                        // Skip entries with neither a route nor valid children;
                        // nothing useful for the user to click.
                        if (!$hasRoute && !$hasChildren) {
                        continue;
                        }

                        $isParentActive = $hasRoute && request()->routeIs($menu['route'] . '*');

                        // A parent is "active" if its own route matches, or if
                        // any of its children's route matches.
                        $hasActiveChild = false;
                        if ($hasChildren) {
                        foreach ($validChildren as $child) {
                        if (isset($child['route']) && request()->routeIs($child['route'] . '*')) {
                        $hasActiveChild = true;
                        break;
                        }
                        }
                        }

                        $isActive = $isParentActive || $hasActiveChild;
                        @endphp

                        @if (!$hasChildren)
                        {{-- Leaf menu item: plain link --}}
                        <li class="nav-item" role="none">
                            <a href="{{ route($menu['route']) }}"
                                class="nav-link {{ $isActive ? 'active' : '' }}" role="menuitem">
                                <i class="nav-icon {{ $menu['icon'] ?? 'bi bi-circle' }}"
                                    aria-hidden="true"></i>
                                <p>{{ $menu['label'] }}</p>
                            </a>
                        </li>
                        @else
                        {{-- Parent menu item: dropdown toggle --}}
                        <li class="nav-item" role="none">
                            <button class="nav-link {{ $isActive ? 'active' : '' }}" data-treeview-toggle
                                role="menuitem" aria-expanded="{{ $hasActiveChild ? 'true' : 'false' }}"
                                aria-haspopup="true">
                                <i class="nav-icon {{ $menu['icon'] ?? 'bi bi-folder' }}"
                                    aria-hidden="true"></i>
                                <p>
                                    {{ $menu['label'] }}
                                    @if (isset($menu['badge']))
                                    <span class="nav-badge"
                                        aria-label="{{ $menu['badge']['label'] ?? __('notification') }}">
                                        {{ $menu['badge']['value'] ?? $menu['badge'] }}
                                    </span>
                                    @endif
                                    <i class="nav-arrow bi bi-chevron-right" aria-hidden="true"></i>
                                </p>
                            </button>
                            <ul class="nav nav-treeview {{ $hasActiveChild ? 'show' : '' }}" role="menu">
                                @foreach ($validChildren as $child)
                                <li class="nav-item" role="none">
                                    <a href="{{ route($child['route']) }}"
                                        class="nav-link {{ request()->routeIs($child['route'] . '*') ? 'active' : '' }}"
                                        role="menuitem">
                                        <i class="nav-icon bi bi-circle" aria-hidden="true"></i>
                                        <p>{{ $child['label'] }}</p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        @endforeach

                        <li class="nav-item mt-2" role="none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-danger-sidebar" role="menuitem">
                                    <i class="nav-icon bi bi-box-arrow-right" aria-hidden="true"></i>
                                    <p>{{ __('Logout') }}</p>
                                </button>
                            </form>
                        </li>

                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                         <li class="nav-item mt-2" role="none">
                            <a class="nav-link" href="">Menu item 1</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="sidebar-footer">
                v1.0.0
            </div>
        </aside>

        <div class="app-main">
            <nav class="navbar navbar-fixed navbar-expand-lg" aria-label="{{ __('Top navigation bar') }}">
                <div class="container-fluid py-2">
                    <ul class="navbar-nav flex-row align-items-center">
                        <li class="nav-item">
                            <button class="sidebar-toggle-btn nav-link" id="sidebarToggleBtn"
                                aria-label="{{ __('Show or hide the menu') }}" type="button">
                                <i class="bi bi-list fs-4" aria-hidden="true"></i>
                            </button>
                        </li>
                        <li class="nav-item">
                            <span class="navbar-brand fw-semibold ms-2">
                                <i class="bi bi-activity text-primary" aria-hidden="true"></i>
                                Admin<span class="text-primary">Dashboard</span>
                            </span>
                        </li>
                    </ul>

                    <ul class="navbar-nav flex-row align-items-center gap-3 ms-auto">
                        <li class="nav-item dropdown">
                            @include('layouts.partials.notifications', ['prefix' => 'admin.'])
                        </li>

                        <li class="nav-item">
                            @include('layouts.partials.profile')
                        </li>
                    </ul>
                </div>
            </nav>

            <main id="main-content" class="content-scrollable" tabindex="-1">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    @yield('content-header')
                    <div>
                        @include('layouts.partials.toasts')
                    </div>
                </div>

                {{-- Slot support for component-based views --}}
                {{ $slot ?? '' }}

                {{-- Section support for classic Blade views --}}
                @hasSection('content')
                @yield('content')
                @endif
            </main>

            {{-- Single delete-confirmation form. The action is set dynamically
                 via JS (data attribute on the triggering delete button) before
                 the modal is shown. --}}
            <x-modal id="confirmModal"
                title="{{ __('Delete this item?') }}"
                class="modal-dialog-centered modal-sm">

                <div class="text-center py-2">
                    <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:52px;height:52px;font-size:1.3rem;">
                        <i class="bi bi-trash text-danger"></i>
                    </div>
                    <p class="text-body-secondary small mb-0">
                        {{ __('This action cannot be undone. Do you really want to continue?') }}
                    </p>
                </div>

                <x-slot name="footer">
                    <form id="confirmForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash me-1"></i>{{ __('Delete') }}
                        </button>
                    </form>
                </x-slot>
            </x-modal>

            <div class="modal fade" id="alertModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white py-2">
                            <h5 class="modal-title small" id="alertModalTitle">{{ __('Confirmation') }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body small" id="alertModalMessage"></div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                                id="alertModalCancel">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-danger btn-sm"
                                id="alertModalConfirm">{{ __('Confirm') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer-fixed">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-secondary">&copy; {{ date('Y') }} AdminDashboard. {{ __('All rights reserved.') }}</small>
                    <div>
                        <a href="confidentialite.html"
                            class="text-secondary text-decoration-none me-3 small">{{ __('Privacy policy') }}</a>
                        <a href="mentions-legales.html"
                            class="text-secondary text-decoration-none small">{{ __('Legal notice') }}</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @push('scripts')
    @stack('scripts')
    @endpush

</x-app-layout>