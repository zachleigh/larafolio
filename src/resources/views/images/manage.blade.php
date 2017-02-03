@extends('larafolio::master')

@section('title')
    Images
@stop

@section('content')
    <div class="page">
        <div class="page__top">
            <div class="page__top-block">
                @include('larafolio::layout.lines')
                <h1 class="page__top-title">
                    Manage Images
                </h1>
            </div>
        </div>
        <div class="project__main image-tile__container">
            <image-manager
                action="{{ route('store-project-image', ['project' => $project]) }}"
                fetch-action="{{ route('show-project-images', ['project' => $project]) }}"
                :icons="{{ json_encode([
                    'down' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
                    'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
                    'up' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-up.svg'))
                ]) }}"
                :images="{{ json_encode($images) }}"
                token="{{ csrf_token() }}"
            ></image-manager>
        </div>
    </div>
@stop
