@extends('larafolio::master')

@section('title')
    {{ $project->name() }} - Larafolio
@stop

@section('content')
    <div class="project-manager content">
        <div class="top">
            <div class="top__title-block top__section">
                @include('larafolio::layout.lines')
                <h1 class="top__title">{{ $project->name() }}</h1>
            </div>
            <div class="top__section">
                <project-controls
                    remove-action="{{ route('remove-project', ['project' => $project]) }}"
                    update-action="{{ route('update-project', ['project' => $project]) }}"
                    :icons="{{ json_encode([
                        'hidden' => file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')),
                        'visible' => file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg')),
                        'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
                    ]) }}"
                    :project="{{ json_encode($project) }}"
                ></project-controls>
            </div>
        </div>
        <div class="project__main">
            <div class="project__left project__half">
                <h2 class="project__half-header">
                    Content
                </h2>
                <section class="section">
                    <h3 class="section__header">Project Type</h3>
                    <div class="section__indented">
                        <b>{{ $project->type() }}</b>
                    </div>
                </section>
                <section class="section">
                    <h3 class="section__header">Project Links</h3>
                    @foreach ($project->links as $link)
                        <div class="section__item">
                            <div class="">
                                Name: <b>{{ $link->name() }}</b>
                            </div>
                            <div class="section__indented">
                                Text: {{ $link->text() }}
                            </div>
                            <div class="section__indented">
                                URL: 
                                <a href="{{ $link->url() }}">
                                    {{ $link->url() }}
                                </a>
                            </div>
                            <link-status
                                url="{{ $link->url() }}"
                                :check="{{ json_encode(config('larafolio.url_validation')) }}"
                            >
                            </link-status>
                        </div>
                    @endforeach
                </section>
                <section class="section">
                    <h3 class="section__header">Project Blocks</h3>
                    @foreach ($project->blocks as $block)
                        <div class="section__item">
                            <div class="">
                                Name: <b>{{ $block->name() }}</b>
                            </div>
                            <div class="section__indented">
                                {!! $block->formattedText() !!}
                            </div>
                        </div>
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
                        href="{{ route('show-images', ['project' => $project]) }}"
                        v-show="medium"
                    >
                        Manage Images
                    </a>
                </section>
            </div>
            <div class="project__right project__half">
                <h2 class="project__half-header">
                    Images
                </h2>
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
    </div>
@stop