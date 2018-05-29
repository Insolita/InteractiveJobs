@extends('jobs.master')
@section('jobs_content')
    <active-job job='@json($job)'/>
@endsection
