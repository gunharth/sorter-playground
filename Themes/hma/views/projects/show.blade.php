@extends('layouts.master')

@section('title')
    {{ $project->title }} | @parent
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
        <span class="linkBack">
            <a href="{{ URL::route($currentLocale . '.projects') }}"><i class="glyphicon glyphicon-chevron-left"></i> Back to project list</a>
        </span>
        <h1>{{ $project->title }}</h1>
        <span class="date">{{ $project->created_at->format('d-m-Y') }}</span>

        {!! $project->content !!}
        </div>
    </div>
@stop
