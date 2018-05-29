@php
/**
* @var \App\Lib\InteractiveJobs\Models\Job[]|\Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator $jobs
**/
@endphp
@extends('jobs.master')
@section('jobs_content')
    <div class="card">
        <div class="card-header">Jobs</div>
        <div class="card-body">
            <table class="table table-sm table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Finished</th>
                     <th>Owner</th>
                     <th>Queue</th>
                     <th></th>
                </tr>
                </thead>
                @foreach($jobs as $job)
                    <tr>
                        <td>{{$job->id}}</td>
                        <td>{!!$job->jobState()->label()!!} {{$job->command}} </td>
                        <td>{{$job->created_at}}</td>
                        <td>
                            @if($job->finished_at)
                                {{$job->finished_at}}
                            @else
                                @if($job->progress)
                                    {{$job->progress}}%
                                @else
                                    -
                                @endif
                            @endif
                        </td>
                        <td>{{$job->owner->name}}</td>
                        <td>{{$job->queue}}</td>
                        <td>
                            <a href="{{route('jobs.show', ['job'=>$job])}}" class="btn btn-primary">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="card-footer">
            {{$jobs->links()}}
        </div>
    </div>
@endsection

