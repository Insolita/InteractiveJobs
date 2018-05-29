@php
    /***
    * @var \App\Lib\InteractiveJobs\Models\JobLog[]|\Illuminate\Pagination\LengthAwarePaginator $logs
    * @var \App\Lib\InteractiveJobs\Models\JobLog $group
    **/
@endphp
@extends('jobs.master')

@section('jobs_content')
    <h4>Logs for {{$group->loggable_type}}: {{$group->loggable_id}}</h4>
    <form action="{{route('logs.destroy', ['log'=>$group])}}"  method="POST">
        @method('DELETE') @csrf
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fa fa-trash-o"></i> Delete
        </button>
    </form>
    @foreach ($logs as $log)
        <div class="card">
            <div class="card-header">
                <div class="badge badge-info">{{$log->level}}</div>
                <div class="badge badge-dark">{{$log->created_at}}</div>
            </div>
            <div class="card-body">
                {{$log->message}}
                {{$log->context ?? ''}}
                {{$log->extra ?? ''}}
            </div>
        </div>
    @endforeach
    <div>
        {{$logs->links()}}
    </div>
@endsection