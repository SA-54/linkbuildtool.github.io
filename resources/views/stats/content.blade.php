@extends('layouts.app')

@section('content')
<div class="bg-base-1 flex-fill">
    @include('stats.header')

    <div class="container py-3 my-3">
        @include('stats.' . $view)

        <div class="small text-muted mt-3">{{ __('Date and time reported in UTC:offset timezone.', ['offset' => \Carbon\CarbonTimeZone::create(Auth::user()->timezone ?? config('app.timezone'))->toOffsetName()]) }}</div>
    </div>
</div>

@include('shared.modals.share_link')
@include('shared.modals.delete_link')
@include('shared.sidebars.user')
@endsection