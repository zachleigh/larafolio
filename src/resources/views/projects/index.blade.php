@extends('larafolio::master')

@section('title')
    Dashboard - Larafolio
@stop

@section('content')
    <div class="page dashboard">
        <div class="page__top">
            <div class="page__top-block">
                @include('larafolio::layout.lines')
                <h1 class="page__top-title">Dashboard</h1>
            </div>
        </div>
        @if ($projects->isEmpty())
            <h2>You have not added any projects yet.</h2>
            <a
                class="button button--primary"
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
