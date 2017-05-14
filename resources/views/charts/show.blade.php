@extends('layouts.app')
@section('title', 'Burndown ' . $chart->sprintname)
@section('description','Get better insights of the progress of the sprint')

@section('content')

<div class="container vue">

    @if (Session::has('success'))
        <div class="alert alert-success">{!! Session::get('success') !!}</div>
    @endif

    @if (Session::has('failure'))
        <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
    @endif

    <div class="jumbotron text-center">
        <div class="panel-body" >
            <burndown url="/api/burndown/{{ $chart->slug }}"></burndown>
        </div>
    </div>
    @if ($chart->boardId)
        <div>
            <a class="btn btn-small btn-primary" href="{{
                URL::to('/chart/' . $chart->boardId
                . '/update')
            }}">Update</a>
        </div>
    @endif
</div>

</div>

@endsection
