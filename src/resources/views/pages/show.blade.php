@extends('larafolio::master')

@section('title')
    {{ $page->name }} - Larafolio
@stop

@section('content')
    <div class="page">
        <div class="page__top">
            <div class="page__top-block">
                @include('larafolio::layout.lines')
                <h1 class="page__top-title">{{ $page->name }}</h1>
            </div>
            <div>
                <resource-controls
                    remove-action="{{ route('remove-page', ['page' => $page]) }}"
                    update-action="{{ route('update-page', ['page' => $page]) }}"
                    :icons="{{ json_encode([
                        'hidden' => file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')),
                        'visible' => file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg')),
                        'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
                    ]) }}"
                    :resource="{{ json_encode($page) }}"
                ></resource-controls>
            </div>
        </div>
        <div class="page__content">
            <div class="page__half">
                <h2 class="page__half-header">
                    Content
                </h2>
                <section class="section">
                    <h3 class="section__header">Page Links</h3>
                    @foreach ($page->links as $link)
                        <div class="section__item">
                            @include('larafolio::components.show-link')
                        </div>
                    @endforeach
                </section>
                <section class="section">
                    <h3 class="section__header">Page Lines</h3>
                    @foreach ($page->lines as $line)
                        @include('larafolio::components.show-line')
                    @endforeach
                </section>
                <section class="section">
                    <h3 class="section__header">Page Blocks</h3>
                    @foreach ($page->blocks as $block)
                        @include('larafolio::components.show-block')
                    @endforeach
                </section>
                <section class="section">
                    <a
                        class="button button--primary"
                        href="{{ route('edit-page', ['page' => $page]) }}"
                    >
                        Edit Page
                    </a>
                    <a
                        class="button button--secondary"
                        href="{{ route('show-page-images', ['page' => $page]) }}"
                        v-show="medium"
                    >
                        Manage Images
                    </a>
                </section>
            </div>
            <div class="page__half">
                <h2 class="page__half-header">
                    Images
                </h2>
                <image-manager
                    action="{{ route('store-page-image', ['page' => $page]) }}"
                    fetch-action="{{ route('show-page-images', ['page' => $page]) }}"
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
    </div>
@stop
