@extends('layouts.app')
@section('title', 'Create new board')
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

    {{ Form::open(['route' => 'board.store', 'class' => 'form-horizontal']) }}

        <div class="form-group">
            {{ Form::label('boardId', 'Board id', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::number('boardId', '', ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('slug', 'slug', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::text('slug', '', ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('totalTaskFilter', 'Total Task Filter', ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-10">
                {{ Form::number('totalTaskFilter', '', ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'tasksDoneFilter', 'Tasks Done Filter', ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::number('tasksDoneFilter', '', ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {{ Form::submit('Add new board', ['class' => 'btn btn-primary']) }}
            </div>
        </div>

    {{ Form::close() }}

</div>

@endsection
