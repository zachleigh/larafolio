@extends('larafolio::master')

@section('title')
    Create a Project
@stop

@section('content')
    <div class="project-manager content dashboard">
        <div class="top">
            <div class="top__title-block top__section">
                @include('larafolio::layout.lines')
                <h1 class="top__title">Dashboard</h1>
            </div>
        </div>
        <div class="project__main dashboard__wrapper">
            @foreach ($projects as $project)
                <section class="dashboard__item">
                    <div class="flex">
                        <img src="{{ $project->getProjectImage() }}">
                        <div class="dashboard__item-info">
                            <div>
                                <h2 class="dashboard__item-name">
                                    {{ $project->name() }}
                                </h2>
                                {!! $project->block('description') !!}
                            </div>
                            <a href="{{ $project->link() }}">
                                {{ $project->link() }}
                            </a>
                        </div>
                    </div>
                    <div class="dashboard__item-controls">
                        @if ($project->visible)
                            <div class="project-controls__section flex-end">
                                Visible
                                <span class="project-controls__icon nav__icon green-icon"
                                    {!! file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg')) !!}
                                </span>
                            </div>
                        @else
                            <div class="project-controls__section">
                                Hidden
                                <span class="project-controls__icon nav__icon red-icon">
                                    {!! file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')) !!}                   
                                </span>
                            </div>
                        @endif
                        <a
                            class="button button--blue"
                            href="{{ route('show-project', ['project' => $project]) }}"
                        >
                            Manage
                        </a>
                    </div>
                </section>
            @endforeach
        </div>
    </div>
@stop