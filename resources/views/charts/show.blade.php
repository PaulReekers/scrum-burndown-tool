
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

    <div class="text-center">
        <span class="label label-committed">Committed: {{ $chartInfo->storyPointsTotal }}</span>
        <span class="label label-done">Done: {{ $chartInfo->currentStoryPointsDone }}</span>
        <span class="label label-added">Scope change: {{
            $chartInfo->currentStoryPoints - $chartInfo->storyPointsTotal
        }}</span>
    </div>

    @if ($chart->boardId)
        <div>
            <a class="btn btn-small btn-primary" href="{{
                URL::to('/chart/' . $chart->boardId
                . '/update')
            }}">Update</a>
            <button v-on:click="play" type="button" class="btn btn-small btn-info">Go Coosto!</button>
            <audio preload="auto" ref="goCoosto" src="{{ url('sound/GoCoostoAudio.mp3') }}"></audio>
        </div>
        <h6><small><samp>Last update: {{ $chart->updated_at }}</samp></small></h6>
    @endif
</div>

</div>

@endsection
