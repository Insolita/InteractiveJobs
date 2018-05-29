@php
    /**
    * @var \App\Lib\InteractiveJobs\JobDefinition $definition
    */
@endphp
@extends('jobs.master')
@section('jobs_content')
    <div class="card">
        <div class="card-header">{{$definition->title()}} [{{$definition->name()}}]</div>
        <div class="card-body">
            <form method="post" action="{{route('jobs.create', ['job'=>$definition->name()])}}">
                @csrf
                @if($definition->formView())
                    @include($definition->formView())
                @else
                    @include('jobs.forms.confirm')
                @endif

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Run it</button>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-primary float-right">Go back</a>
                </div>
            </form>
        </div>
    </div>
@endsection
