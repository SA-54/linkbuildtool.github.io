@if(count($breadcrumbs))
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb px-0 bg-transparent font-weight-medium mb-0">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$loop->last)
                    <li class="breadcrumb-item d-flex align-items-center">
                        @if(isset($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}" class="text-muted">{{ $breadcrumb['title'] }}</a>
                        @else
                            <div class="text-muted">{{ $breadcrumb['title'] }}</div>
                        @endif
                        @include((__('lang_dir') == 'rtl' ? 'icons.chevron_left' : 'icons.chevron_right'), ['class' => 'fill-current icon-chevron mx-3'])</li>
                @else
                    <li class="breadcrumb-item active text-dark">{{ $breadcrumb['title'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif