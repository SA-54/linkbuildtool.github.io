@section('site_title', formatTitle([__('Links'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Links')],
]])

<div class="d-flex">
    <div class="flex-grow-1">
        <h2 class="mb-0 d-inline-block">{{ __('Links') }}</h2>
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Links') }}</div></div>
            <div class="col-12 col-md-auto">
                <form method="GET" action="{{ route('admin.links') }}" class="d-md-flex">
                    @include('shared.filter_tags')
                    <div class="input-group input-group-sm">
                        <input class="form-control" name="search" placeholder="{{ __('Search') }}" value="{{ app('request')->input('search') }}">
                        <div class="input-group-append">
                            <button type="button" class="btn {{ request()->input('sort') ? 'btn-primary' : 'btn-outline-primary' }} d-flex align-items-center dropdown-toggle dropdown-toggle-split reset-after" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.filter', ['class' => 'fill-current icon-button-sm'])</button>
                            <div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow filters-dropdown" id="search-filters">
                                <div class="dropdown-header py-1">
                                    <div class="row">
                                        <div class="col"><div class="font-weight-medium m-0 text-dark text-truncate">{{ __('Filters') }}</div></div>
                                        <div class="col-auto">
                                            @if(request()->input('sort'))
                                                <a href="{{ route('admin.links') }}" class="text-secondary">{{ __('Reset') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <div class="form-group px-4">
                                    <label for="i_type" class="small">{{ __('Type') }}</label>
                                    <select name="type" id="i_type" class="custom-select custom-select-sm">
                                        @foreach([0 => __('All'), 1 => __('Active'), 2 => __('Expired')] as $key => $value)
                                            <option value="{{ $key }}" @if(request()->input('type') == $key && request()->input('type') !== null) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="i_by" class="small">{{ __('Search by') }}</label>
                                    <select name="by" id="i_by" class="custom-select custom-select-sm">
                                        @foreach(['title' => __('Title'), 'alias' => __('Alias'), 'url' => __('URL')] as $key => $value)
                                            <option value="{{ $key }}" @if(request()->input('by') == $key || !request()->input('by') && $key == 'name') selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="i_sort" class="small">{{ __('Sort') }}</label>
                                    <select name="sort" id="i_sort" class="custom-select custom-select-sm">
                                        @foreach(['desc' => __('Old'), 'asc' => __('New'), 'max' => __('Best performing'), 'min' => __('Least performing')] as $key => $value)
                                            <option value="{{ $key }}" @if(request()->input('sort') == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group px-4 mb-2">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">{{ __('Search') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if(count($links) == 0)
            {{ __('No results found.') }}
        @else
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0 text-muted">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-12 col-lg-5 d-flex">
                                    {{ __('URL') }}
                                </div>

                                <div class="col-12 col-lg-5 d-flex">
                                    {{ __('User') }}
                                </div>

                                <div class="col-12 col-lg-2 d-flex">
                                    {{ __('Clicks') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="btn btn-outline-primary btn-sm invisible">{{ __('Edit') }}</a>
                        </div>
                    </div>
                </div>

                @foreach($links as $link)
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                <div class="row">
                                    <div class="col-12 col-lg-5 d-flex">
                                        <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} d-flex align-items-center"><img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="icon-label"></div>

                                        <div class="text-truncate" dir="ltr">
                                            <a href="{{ route('admin.links.edit', $link->id) }}" class="{{ ($link->disabled || $link->expiration_clicks && $link->clicks >= $link->expiration_clicks || \Carbon\Carbon::now()->greaterThan($link->ends_at) && $link->ends_at ? 'text-danger' : 'text-primary') }}">{{ str_replace(['http://', 'https://'], '', (($link->domain->name ?? config('app.url')) .'/'.$link->alias)) }}</a>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-5 d-flex align-items-center">
                                        @if(isset($link->user))
                                            <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} list-avatar">
                                                <img src="{{ gravatar(isset($link->user) ? $link->user->email : '', 48) }}" class="rounded-circle">
                                            </div>

                                            <a href="{{ route('admin.users.edit', $link->user->id) }}"@if($link->user->trashed()) class="text-danger" @endif>{{ $link->user->name }}</a>
                                        @else
                                            <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} list-avatar">
                                                <img src="{{ gravatar('', 48, 'mp') }}" class="rounded-circle">
                                            </div>

                                            <div class="text-muted">{{ __('Guest') }}</div>
                                        @endif
                                    </div>

                                    <div class="col-12 col-lg-2 d-flex">
                                        @if(isset($link->user))
                                            <a href="{{ route('stats', ['id' => $link->id]) }}" class="text-dark">{{ $link->clicks }}</a>
                                        @else
                                            {{ $link->clicks }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('admin.links.edit', $link->id) }}" class="btn btn-outline-primary btn-sm">{{ __('Edit') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-3 align-items-center">
                    <div class="row">
                        <div class="col">
                            <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $links->firstItem(), 'to' => $links->lastItem(), 'total' => $links->total()]) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            {{ $links->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>