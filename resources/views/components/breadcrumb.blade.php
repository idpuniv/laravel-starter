@php
    $crumbs = \App\Support\Breadcrumb::get();
@endphp

@if(count($crumbs) > 0)
    <nav aria-label="breadcrumb">
        <div class="d-flex align-items-center">
            @foreach($crumbs as $index => $crumb)
                @if($crumb['url'] && !$loop->last)
                    <a href="{{ $crumb['url'] }}">{{ $crumb['title'] }}</a>
                    <i class="bi bi-chevron-right mx-1"></i>
                @else
                    <span class="active">{{ $crumb['title'] }}</span>
                @endif
            @endforeach
        </div>
    </nav>
@endif