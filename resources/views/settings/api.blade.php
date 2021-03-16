@section('site_title', formatTitle([__('API'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('settings'), 'title' => __('Settings')],
    ['title' => __('API')]
]])

<h2 class="mb-3 d-inline-block">{{ __('API') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('API') }}</div></div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <div class="form-group">
            <label for="i_api_token">{{ __('API key') }}</label>
            <input type="text" id="i_api_token" class="form-control" value="{{ $user->api_token }}" disabled>
        </div>

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#regenerateModal">{{ __('Regenerate') }}</button>
    </div>
</div>

<div class="modal fade" id="regenerateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">{{ __('Regenerate') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                </button>
            </div>
            <div class="modal-body">
                <div>{{ __('Are you sure you want to regenerate your API key?') }}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <form action="{{ route('settings.api.update') }}" method="post" enctype="multipart/form-data">

                    @csrf

                    <button type="submit" class="btn btn-danger">{{ __('Regenerate') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>