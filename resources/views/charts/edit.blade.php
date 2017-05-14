@extends('layouts.app')
@section('title', 'Edit ' . $charts[0]->sprintname)
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
                <td>Sprint Name</td>
                <td>Sprint Day</td>
                <td>Total StoryPoints</td>
                <td>StoryPoints Done</td>
                <td>Total Tasks</td>
                <td>Tasks Done</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>

        @foreach($charts as $key => $chart)
            {{ Form::open([
                'method' => 'PUT',
                'url' => 'charts/' . $chart->id,
                'class' => '']
            ) }}
            <tr>
                <td>{{ $chart->sprintname }}</td>
                <td>
                    {{ Form::date(
                        'sprintDay',
                        isset($chart) ? $chart->sprintDay : Carbon\Carbon::now(),
                        ['class' => 'form-control']
                    ) }}
                </td>
                <td>
                    {{ Form::number(
                        'storyPointsTotal',
                        $chart->storyPointsTotal,
                        ['class' => 'form-control', 'step' => '0.5']
                    ) }}
                </td>
                <td>
                    {{ Form::number(
                        'storyPointsDone',
                        $chart->storyPointsDone,
                        ['class' => 'form-control', 'step' => '0.5']
                    ) }}
                </td>
                <td>
                    {{ Form::number(
                        'tasksTotal',
                        $chart->tasksTotal,
                        ['class' => 'form-control', 'step' => '0.5']
                    ) }}
                </td>
                <td>
                    {{ Form::number(
                        'tasksDone',
                        $chart->tasksDone,
                        ['class' => 'form-control', 'step' => '0.5']
                    ) }}
                </td>

                <td>
                {{ Form::submit(
                    'Update',
                    ['class' => 'btn btn-small btn-info mr-3 mb-3']
                ) }}
            {{ Form::close() }}
            {{ Form::open([
                    'method' => 'delete',
                    'route' => ['charts.destroy', $chart->id]
                ]) }}
                {{ Form::submit(
                    'Delete',
                    ['class' => 'btn btn-small btn-danger']
                ) }}
            {{ Form::close() }}
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <div>
        <a class="btn btn-small btn-primary" href="{{
                URL::to('sprint/' . $charts[0]->slug) }}">Show Burndown</a>
        <a class="btn btn-small btn-success" href="{{
                URL::to('chart/' . $charts[0]->slug . '/create') }}">Add Day</a>
    </div>
</div>

@endsection
