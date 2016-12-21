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
        @if ($projects->isEmpty())
            <h2>You have not added any projects yet.</h2>
            <a
                class="button button--blue"
                href="{{ route('add-project') }}"
            >
                Add a Project
            </a>
        @endif
        <dashboard
            action={{ route('update-portfolio')}}
            :blocks="{{ json_encode($blocks) }}"
            :icons="{{ json_encode([
                'down' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
                'up' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-up.svg')),
                'hidden' => file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')),
                'visible' => file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg'))
            ]) }}"
            :images="{{ json_encode($images) }}"
            :projects="{{ json_encode($projects) }}"
        ></dashboard>
    </div>
@stop