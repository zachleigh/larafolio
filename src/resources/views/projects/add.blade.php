@extends('larafolio::master')

@section('title')
create a project
@stop

@section('content')
    <project-form
        action="{{ route('store-project') }}"
        :icons="{{ json_encode([
            'down' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
            'remove' => file_get_contents(public_path('vendor/larafolio/zondicons/close.svg')),
            'up' => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-up.svg'))
        ]) }}"
        title="Add a New Project"
        type="add"
    ></project-form>
@stop
