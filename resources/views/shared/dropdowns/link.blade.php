@if(isset($options['dropdown']['button']))
    <a href="#" class="btn text-primary btn-sm d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.horizontal_menu', ['class' => 'fill-current icon-button'])&#8203;</a>
@endif

<div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow">
    @if(isset($options['dropdown']['edit']))
        @if(Auth::check() && (Auth::user()->id == $link->user_id || Auth::user()->role == 1))
            <a class="dropdown-item d-flex align-items-center" href="{{ ((isset($admin) || ($link->user_id != Auth::user()->id && Auth::user()->role == 1)) ? route('admin.links.edit', $link->id) : route('links.edit', $link->id)) }}">@include('icons.edit', ['class' => 'text-muted fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Edit') }}</a>
        @endif
    @endif

    @if(isset($options['dropdown']['share']))
        <a class="dropdown-item d-flex align-items-center link-share" href="#" data-toggle="modal" data-target="#shareModal" data-url="{{ (($link->domain->name ?? config('app.url')) . '/' . $link->alias) }}" data-title="@if($link->title){{ $link->title }}@else{{ str_replace(['http://', 'https://'], '', $link->url) }}@endif" data-qr="{{ route('qr', $link->id) }}">@include('icons.share', ['class' => 'text-muted fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Share') }}</a>
    @endif

    @if(isset($options['dropdown']['stats']))
        <a class="dropdown-item d-flex align-items-center" href="{{ route('stats', $link->id) }}">@include('icons.stats', ['class' => 'text-muted fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Stats') }}</a>
    @endif

    @if(isset($options['dropdown']['preview']))
        <a class="dropdown-item d-flex align-items-center" href="{{ (($link->domain->name ?? config('app.url')) . '/' . $link->alias) }}/+" target="_blank">@include('icons.preview', ['class' => 'text-muted fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Preview') }}</a>
    @endif

    @if(isset($options['dropdown']['open']))
        <a class="dropdown-item d-flex align-items-center" href="{{ (($link->domain->name ?? config('app.url')) . '/' . $link->alias) }}" target="_blank">@include('icons.external', ['class' => 'text-muted fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Open') }}</a>
    @endif

    @if(isset($options['dropdown']['delete']))
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-danger d-flex align-items-center" href="#" data-toggle="modal" data-target="#deleteLinkModal" data-action="{{ isset($admin) ? route('admin.links.delete', $link->id) : route('links.delete', $link->id) }}" data-text="{{ __('Are you sure you want to delete :name?', ['name' => (str_replace(['http://', 'https://'], '', (($link->domain->name ?? config('app.url')) . '/' . $link->alias)))]) }}">@include('icons.delete', ['class' => 'fill-current icon-dropdown '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Delete') }}</a>
    @endif
</div>