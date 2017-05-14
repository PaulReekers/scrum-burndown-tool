@extends('layouts.app')
@section('title', 'Boards')
@section('description','')

@section('content')

    <div class="container">

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Board id</td>
                    <td>Slug</td>
                    <td>Task Total Filter</td>
                    <td>Tasks Done Filter</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
            @foreach($boards as $key => $value)
                <tr>
                    <td>{{ $value->boardId }}</td>
                    <td>{{ $value->slug }}</td>
                    <td>{{ $value->totalTaskFilter }}</td>
                    <td>{{ $value->tasksDoneFilter }}</td>
                    <td>
                    @if (!Auth::guest())
                        <a class="btn btn-small btn-info mr-3 mb-3" href="{{ URL::to('board/' . $value->id . '/edit') }}">Edit board</a>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (!Auth::guest())
            <div><a class="btn btn-small btn-info" href="{{ URL::to('board/create') }}">Add new board</a></div>
        @endif
    </div>

@endsection
