@extends('layouts.redirect')

@section('site_title', __('Link disabled'))

@section('content')
<div class="bg-base-1 d-flex align-items-center flex-fill">
    <div class="container">
        <div class="row h-100 justify-content-center align-items-center py-3">
            <div class="col-lg-6">
                <h2 class="mb-3 text-center">{{ __('Link disabled') }}</h2>
                <p class="mb-5 text-center text-muted">{{ __('This link has been disabled.') }}</p>

                <div class="text-center">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">{{ __('Go back') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection