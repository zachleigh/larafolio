@extends('larafolio::master')

@section('title')
    Projects - Larafolio
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
    </div>
@stop
