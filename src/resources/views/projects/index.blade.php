@extends('larafolio::master')

@section('title')
    Dashboard - Larafolio
@stop

@section('content')
    <div class="page dashboard">
        <dashboard-projects
            action={{ route('update-portfolio')}}
            :blocks="{{ json_encode($projectBlocks) }}"
            :icons="{{ json_encode($icons) }}"
            :images="{{ json_encode($projectImages) }}"
            :projects="{{ json_encode($projects) }}"
        ></dashboard-projects>
        <dashboard-pages
            action={{ route('update-portfolio')}}
            :icons="{{ json_encode($icons) }}"
            :pages="{{ json_encode($pages) }}"
        ></dashboard-pages>
    </div>
@stop
