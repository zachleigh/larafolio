@extends('larafolio::master')

@section('title')
    Pages - Larafolio
@stop

@section('content')
    <div class="page dashboard">
        <dashboard-pages
            action={{ route('update-portfolio')}}
            :icons="{{ json_encode($icons) }}"
            :pages="{{ json_encode($pages) }}"
        ></dashboard-pages>
    </div>
@stop
