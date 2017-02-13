@extends('larafolio::master')

@section('title')
    Edit Page - Larafolio
@stop

@section('content')
    <page-form
        action="{{ route('update-page', ['page' => $page]) }}"
        cancel-action="{{ route('show-page', ['page' => $page]) }}"
        :icons="{{ json_encode([
            'down' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
            'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
            'up' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-up.svg'))
        ]) }}"
        :next-block-order="{{ $nextBlock }}"
        :next-link-order="{{ $nextLink }}"
        :page="{{ json_encode($page) }}"
        title="{{ $page->name }}"
        type="update"
    ></page-form>
@stop
