<a href="#" class="btn btn-sm text-primary d-flex align-items-center link-copy" data-url="{{ (($link->domain->name ?? config('app.url')) . '/' . $link->alias) }}" data-toggle="tooltip-copy" title="{{ __('Copy') }}" data-copy="{{ __('Copy') }}" data-copied="{{ __('Copied') }}">
    @include('icons.copy_link', ['class' => 'fill-current icon-button'])&#8203;
</a>