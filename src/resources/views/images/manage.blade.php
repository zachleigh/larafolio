@extends('larafolio::master')

@section('title')
    Images
@stop

@section('content')
    <div class="project-manager content">
        <div class="top">
            <div class="top__title-block top__section">
                @include('larafolio::layout.lines')
                <h1 class="top__title">Manage Images</h1>
            </div>
        </div>
        <div class="project__main image-tile__container">
            <image-manager
                action="{{ route('store-image', ['project' => $project]) }}"
                fetch-action="{{ route('show-images', ['project' => $project]) }}"
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