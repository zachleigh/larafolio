@extends('larafolio::master')

@section('title')
    Add Page - Larafolio
@stop

@section('content')
    <page-form
        action="{{ route('store-page') }}"
        :icons="{{ json_encode([
            'down' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
            'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
            'up' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-up.svg'))
        ]) }}"
        title="Add a New Page"
        type="add"
    ></page-form>
@stop
