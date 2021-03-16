@section('site_title', formatTitle([__('Edit'), __('Page'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['url' => route('admin.pages'), 'title' => __('Pages')],
    ['title' => __('Edit')],
]])

<h2 class="mb-3 d-inline-block">{{ __('Edit') }}</h2>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Page') }}</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('page', $page->slug) }}" class="btn btn-outline-primary btn-sm">{{ __('View') }}</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('admin.pages.edit', $page->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i_title">{{ __('Title') }}</label>
                <input type="text" name="title" id="i_title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ $page->title }}">
                @if ($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i_slug">{{ __('Slug') }}</label>
                <input type="text" name="slug" id="i_slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" value="{{ $page->slug }}">
                @if ($errors->has('slug'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('slug') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label>{{ __('Visibility') }}</label>
                <div class="row">
                    <div class="col">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="footer" id="i_footer" value="1" @if($page->footer) checked @endif>
                            <label class="custom-control-label" for="i_footer">{{ __('Footer') }}</label>
                            @if ($errors->has('footer'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('footer') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="i_content">{{ __('Content') }}</label>
                <textarea name="content" id="i_content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ $page->content }}</textarea>
                @if ($errors->has('content'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">{{ __('Delete') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">{{ __('Delete') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete :name?', ['name' => $page->title]) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <form action="{{ route('admin.pages.delete', $page->id) }}" method="post" enctype="multipart/form-data">

                    @csrf

                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>