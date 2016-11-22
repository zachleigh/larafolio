@extends('larafolio::master')

@section('title')
    Images
@stop

@section('content')
    <div>
        <h1>Current Project Images</h1>
        @if ($project->images->isEmpty())
            <h3>No Current Images</h3>
        @else
            @foreach($project->images as $image)
                <img src="{{ $image->small() }}">
                <form
                    method="POST"
                    action="{{ route('update-image', ['image' => $image]) }}"
                >
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div>
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{ $image->name }}">
                    </div>
                    <div>
                        <label for="caption">Caption</label>
                        <input type="text" name="caption" value="{{ $image->caption }}">
                    </div>
                    <input type="submit" value="Submit">
                </form>
                <form
                    method="POST"
                    action="{{ route('remove-image', ['image' => $image]) }}"
                >
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="submit" value="Remove Image">
                </form>
            @endforeach
        @endif
    </div>
    <form
        action="{{ route('store-image', ['project' => $project]) }}"
        method="POST"
        class="dropzone"
        id="my-awesome-dropzone"
    >
        {{ csrf_field() }}
    </form>
@stop