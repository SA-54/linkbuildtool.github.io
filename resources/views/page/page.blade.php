@section('site_title', formatTitle([e($page['title']), config('settings.title')]))

@extends('layouts.app')

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container py-3 my-3">
            @include('shared.breadcrumbs', ['breadcrumbs' => [
                ['url' => route('home'), 'title' => __('Home')],
                ['title' => __('Page')]
            ]])

            <div class="d-flex">
                <div class="flex-grow-1">
                    <h2 class="mb-3 d-inline-block">{{ $page->title }}</h2>
                </div>
                <div>
                    @if(Auth::user() && Auth::user()->role == 1)
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary mb-3">{{ __('Edit') }}</a>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@include('shared.sidebars.user')