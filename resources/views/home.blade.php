@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-3">
         @include('_menu')
         @include('jobs._menu')
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                     @if($logs)
                            <h5>Last logs:</h5>
                        <ul>
                         @foreach($logs as $log)
                                <li>{{$log->created_at}}:  {{$log->message}}</li>
                            @endforeach
                        @endif
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
