@extends('larafolio::master')

@section('title')
    {{ $project->name }} - Larafolio
@stop

@section('content')
    <div class="page">
        <div class="page__top">
            <div class="page__top-block">
                @include('larafolio::layout.lines')
                <h1 class="page__top-title">{{ $project->name }}</h1>
            </div>
            <div>
                <resource-controls
                    remove-action="{{ route('remove-project', ['project' => $project]) }}"
                    update-action="{{ route('update-project', ['project' => $project]) }}"
                    :icons="{{ json_encode([
                        'hidden' => file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')),
                        'visible' => file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg')),
                        'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
                    ]) }}"
                    :resource="{{ json_encode($project) }}"
                ></resource-controls>
            </div>
        </div>
        <div class="page__content">
            <div class="page__half">
                <h2 class="page__half-header">
                    Content
                </h2>
                <section class="section">
                    <h3 class="section__header">Project Type</h3>
                    <div class="section__indented">
                        <b>{{ $project->type }}</b>
                    </div>
                </section>
                <section class="section">
                    <h3 class="section__header">Project Links</h3>
                    @foreach ($project->links as $link)
                        @include('larafolio::components.show-link')
                    @endforeach
                </section>
                <section class="section">
                    <h3 class="section__header">Project Lines</h3>
                    @foreach ($project->lines as $line)
                        @include('larafolio::components.show-line')
                    @endforeach
                </section>
                <section class="section">
                    <h3 class="section__header">Project Blocks</h3>
                    @foreach ($project->blocks as $block)
                        @include('larafolio::components.show-block')
                    @endforeach
                </section>
                <section class="section">
                    <a
                        class="button button--primary"
                        href="{{ route('edit-project', ['project' => $project]) }}"
                    >
                        Edit Project
                    </a>
                    <a
                        class="button button--secondary"
                        href="{{ route('show-project-images', ['project' => $project]) }}"
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
                    action="{{ route('store-project-image', ['project' => $project]) }}"
                    fetch-action="{{ route('show-project-images', ['project' => $project]) }}"
                    :icons="{{ json_encode([
                        'down' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
                        'edit' => file_get_contents(public_path('vendor/larafolio/zondicons/edit-pencil.svg')),
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
