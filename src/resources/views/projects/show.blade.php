@extends('larafolio::master')

@section('title')

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
                <section class="project__display-section">
                    <h3 class="project__display-header">Type</h3>
                    <div class="project__display-item">
                        {{ $project->type() }}
                    </div>
                </section>
                <section class="project__display-section">
                    <h3 class="project__display-header">Link</h3>
                    <a href="{{ $project->link() }}" class="project__display-item">
                        {{ $project->link() }}
                    </a>
                </section>
                @foreach ($project->blocks as $block)
                    <section class="project__display-section">
                        <h3 class="project__display-header">
                            Block: {{ $block->name() }}
                        </h3>
                        <div class="project__display-item">
                            {!! $block->formattedText() !!}
                        </div>
                    </section>
                @endforeach
                <section class="project__display-section">
                    <a
                        class="button button--blue"
                        href="{{ route('edit-project', ['project' => $project]) }}"
                    >
                        Edit Project
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