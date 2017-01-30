@extends('larafolio::master')

@section('title')
    Project Settings - Larafolio
@stop

@section('content')
    <div class="page settings">
        <div class="page__top">
            <div class="page__top-block">
                @include('larafolio::layout.lines')
                <h1 class="page__top-title">
                    @yield('page_header')
                </h1>
            </div>
        </div>
        <div class="page__content">
            @yield('body')
        </div>
    </div>
@stop
