@extends('larafolio::master')

@section('title')
    Project Settings - Larafolio
@stop

@section('content')
    <div class="project-manager content settings">
        <div class="top">
            <div class="top__title-block top__section">
                @include('larafolio::layout.lines')
                <h1 class="top__title">
                    @yield('page_header')
                </h1>
            </div>
        </div>
        <div class="page__content">
            @yield('body')
        </div>
    </div>
@stop