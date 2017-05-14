@extends('layouts.app')
@section('title', 'Edit ' . $boards->slug)
@section('description','')

@section('content')
<div class="container">

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success">{!! Session::get('success') !!}</div>
    @endif

    @if (Session::has('failure'))
        <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
    @endif


    <table class="table table-striped table-bordered edit-table">
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

            <tr>
                {{ Form::open(['method' => 'PUT', 'url' => 'board/' . $boards->id, 'class' => 'pull-left']) }}
                <td>
                    <input class="form-control" disabled value="{{ $boards->boardId }}">
                </td>
                <td>
                    {{ Form::text('slug', $boards->slug,
                        ['class' => 'form-control'])  }}</td>
                </td>
                <td>
                    {{ Form::number('totalTaskFilter', $boards->totalTaskFilter,
                        ['class' => 'form-control']) }}
                </td>
                <td>
                    {{ Form::number('tasksDoneFilter', $boards->tasksDoneFilter,
                        ['class' => 'form-control']) }}
                </td>
                <td>
                    {{ Form::submit('Update',
                        ['class' => 'btn btn-small btn-info mr-3 mb-3']) }}
                {{ Form::close() }}

                {{ Form::open(['method' => 'DELETE', 'url' => 'board/' . $boards->id, 'class' => 'pull-right']) }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
                </td>
            </tr>

        </tbody>
    </table>
    @if (!Auth::guest())
        <div><a class="btn btn-small btn-info" href="{{ URL::to('board/') }}">Back to boards</a></div>
    @endif
</div>

@endsection
