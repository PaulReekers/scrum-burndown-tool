@extends('layouts.app')
@section('title', 'Create new day')
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

    {{ Form::open([
        'route' => 'charts.store',
        'class' => 'form-horizontal']
    ) }}

        {{ Form::hidden(
            'boardId', isset($chart->boardId) ? $chart->boardId : 0
        ) }}

        <div class="form-group">
            {{ Form::label(
                'sprintname',
                'Sprint',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::text(
                    'sprintname',
                    isset($chart) ? $chart->sprintname : '',
                    ['class' => 'form-control']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'storyPointsTotal',
                'Total StoryPoints',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::number(
                    'storyPointsTotal',
                    isset($chart) ? $chart->storyPointsTotal : '',
                    ['class' => 'form-control', 'step' => '0.5']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'storyPointsDone',
                'StoryPoints Done',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::number(
                    'storyPointsDone',
                    isset($chart) ? $chart->storyPointsDone : '',
                    ['class' => 'form-control', 'step' => '0.5']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'tasksTotal',
                'Total Tasks',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::number(
                    'tasksTotal',
                    isset($chart) ? $chart->tasksTotal : '',
                    ['class' => 'form-control', 'step' => '0.5']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'tasksDone',
                'Tasks Done',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::number(
                    'tasksDone',
                    isset($chart) ? $chart->tasksDone : '',
                    ['class' => 'form-control', 'step' => '0.5']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'sprintDay',
                'Sprint Day',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::date(
                    'sprintDay',
                    isset($chart) ?
                        Carbon\Carbon::parse($chart->sprintDay)->addDay() : Carbon\Carbon::now(),
                    ['class' => 'form-control']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'startDate',
                'Start Date',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::date(
                    'startDate',
                    isset($chart) ? $chart->startDate : Carbon\Carbon::now(),
                    ['class' => 'form-control']
                ) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label(
                'endDate',
                'End Date',
                ['class' => 'col-sm-2 control-label']
            ) }}
            <div class="col-sm-10">
                {{ Form::date(
                    'endDate',
                    isset($chart) ? $chart->endDate : Carbon\Carbon::now()->addDays(14),
                    ['class' => 'form-control']
                ) }}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {{ Form::submit(
                    'Submit day',
                    ['class' => 'btn btn-primary']
                ) }}
            </div>
        </div>

    {{ Form::close() }}

</div>

@endsection
