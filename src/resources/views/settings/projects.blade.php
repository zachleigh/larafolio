@extends('larafolio::layout.settings')

@section('title')
    Project Settings - Larafolio
@stop

@section('page_header')
    Project Settings
@stop

@section('body')
    <div class="page__half">
        <section class="section">
            <h3 class="section__header">Deleted Projects</h3>
            <deleted-projects
                :passed-projects="{{ $deletedProjects }}"
                :icons="{{ json_encode([
                    'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg'))
                ]) }}"
            ></deleted-projects>
        </section>
    </div>
    <div class="page__half">

    </div>
@stop