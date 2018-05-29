@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-3">
                @include('_menu')
                @include('jobs._menu')
            </div>
            <div class="col-9">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('errors'))
                    <div class="alert alert-danger">
                        {{ session('errors') }}
                    </div>
                @endif
                @yield('jobs_content')
            </div>
        </div>
    </div>
@endsection
