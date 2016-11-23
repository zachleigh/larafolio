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
                    <div class="dashboard__info">
                        <div>
                            <h2 class="dashboard__name">
                                {{ $project->name() }}
                            </h2>
                            @if ($project->visible)
                                <div class="project-controls__section">
                                    <span class="nav__icon green-icon">
                                        {!! file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg')) !!}
                                    </span>
                                    Visible
                                </div>
                            @else
                                <div class="project-controls__section">
                                    <span class="nav__icon red-icon">
                                        {!! file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')) !!}                   
                                    </span>
                                    Hidden
                                </div>
                            @endif
                        </div>
                        <div>
                            <a
                                class="button button--blue"
                                href="{{ route('show-project', ['project' => $project]) }}"
                            >
                                Manage
                            </a>
                        </div>
                    </div>
                    <div class="dashboard__photo">
                        <img src="{{ $project->getProjectImage() }}">
                    </div>
                    <div class="dashboard__description">
                        {!! $project->getProjectBlock() !!}
                    </div>
                </section>
            @endforeach
        </div>
    </div>
@stop