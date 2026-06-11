<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">
    @foreach ($sidebarMenus as $menu)
        @php
            $validChildren = array_filter($menu['children'] ?? [], function($child) {
                return isset($child['route']) && Route::has($child['route']);
            });
            
            $hasChildren = !empty($validChildren);
            $hasRoute = isset($menu['route']) && Route::has($menu['route']);
            
            if (!$hasRoute && !$hasChildren) {
                continue;
            }
            
            $isActive = $hasRoute && request()->routeIs($menu['route'] . '*');
            $hasActiveChild = false;
            
            if ($hasChildren) {
                foreach ($validChildren as $child) {
                    if (isset($child['route']) && request()->routeIs($child['route'] . '*')) {
                        $hasActiveChild = true;
                        break;
                    }
                }
            }
        @endphp

        @if (!$hasChildren)
            <li class="nav-item">
                <a href="{{ $hasRoute ? route($menu['route']) : '#' }}" 
                   class="nav-link {{ $isActive ? 'active' : '' }}">
                    <i class="nav-icon {{ $menu['icon'] ?? 'bi bi-circle' }}"></i>
                    <p>{{ $menu['label'] }}</p>
                </a>
            </li>
        @endif

        @if ($hasChildren)
            <li class="nav-item {{ $hasActiveChild ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $hasActiveChild ? 'active' : '' }}">
                    <i class="nav-icon {{ $menu['icon'] ?? 'bi bi-folder' }}"></i>
                    <p>
                        {{ $menu['label'] }}
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach ($validChildren as $child)
                        <li class="nav-item">
                            <a href="{{ route($child['route']) }}"
                                class="nav-link {{ request()->routeIs($child['route'] . '*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>{{ $child['label'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endforeach
</ul>