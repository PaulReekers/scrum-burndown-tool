
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
            <button v-on:click="play" type="button" class="btn btn-small btn-info" data-toggle="modal" data-target="#goCoostoModal">Go Coosto!</button>
            <audio preload="auto" ref="goCoosto" src="{{ url('sound/GoCoostoAudio.mp3') }}"></audio>

        </div>
        <h6><small><samp>Last update: {{ $chart->updated_at }}</samp></small></h6>
    @endif

    <!-- Modal -->
    <div id="goCoostoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="goCoostoModal" aria-hidden="true">

            <div class="modal-dialog modal-notify modal-danger" role="document">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <img class="goCoostoImage" :src="selectedImage" alt="Go Coosto!">
                    </div>
                </div>
            </div>

    </div>


</div>

</div>

@endsection
