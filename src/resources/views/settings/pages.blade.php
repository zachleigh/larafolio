@extends('larafolio::layout.settings')

@section('title')
    Page Settings - Larafolio
@stop

@section('page_header')
    Page Settings
@stop

@section('body')
    <div class="page__half">
        <section class="section">
            <h3 class="section__header">Deleted Pages</h3>
            <deleted-resources
                :passed-resources="{{ $deletedPages }}"
                :icons="{{ json_encode([
                    'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg'))
                ]) }}"
                resource-type="page"
            ></deleted-resources>
        </section>
    </div>
    <div class="page__half">

    </div>
@stop
