@extends('layouts.app')
@section('title', 'Better Burndown')
@section('description','')

@section('content')

    <div class="container">

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Sprint</td>
                    <td>Start date</td>
                    <td>End date</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
            @foreach($charts as $key => $value)
                <tr>
                    <td>{{ $value->sprintname }}</td>
                    <td>{{ $value->startDate }}</td>
                    <td>{{ $value->endDate }}</td>
                    <td>
                        <a class="btn btn-small btn-primary mr-3 mb-3" href="{{ URL::to('sprint/' . $value->slug) }}">Show Burndown</a>
                    @if (!Auth::guest())
                        <a class="btn btn-small btn-info mr-3 mb-3" href="{{ URL::to('charts/' . $value->slug . '/edit') }}">Edit Sprint</a>
                        <a class="btn btn-small btn-success mr-3 mb-3" href="{{ URL::to('chart/' . $value->slug . '/create') }}">Add day</a>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if (!Auth::guest())
            <div class="mb-10"><a class="btn btn-small btn-primary" href="{{ URL::to('charts/create') }}">Create new Sprint</a></div>
        @endif

        <h3>Previous Sprints</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td>Sprint</td>
                    <td>Start date</td>
                    <td>End date</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
            @foreach($oldCharts as $key => $value)
                <tr>
                    <td>{{ $value->sprintname }}</td>
                    <td>{{ $value->startDate }}</td>
                    <td>{{ $value->endDate }}</td>
                    <td>
                        <a class="btn btn-small btn-primary mr-3 mb-3" href="{{ URL::to('sprint/' . $value->slug) }}">Show Burndown</a>
                    @if (!Auth::guest())
                        <a class="btn btn-small btn-info mr-3 mb-3" href="{{ URL::to('charts/' . $value->slug . '/edit') }}">Edit Sprint</a>
                        <a class="btn btn-small btn-success mr-3 mb-3" href="{{ URL::to('chart/' . $value->slug . '/create') }}">Add day</a>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection
