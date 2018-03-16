@extends('layouts.master')

@section('title')
    {{ $page->title }} | @parent
@stop
@section('meta')
    <meta name="title" content="{{ $page->meta_title}}" />
    <meta name="description" content="{{ $page->meta_description }}" />
@stop

@section('content')
    <div class="row">
        <h1>{{ $page->title }}</h1>
        {!! $page->body !!}

        @foreach($projects as $project)
            {{ $project->title }} {{ $project->url }}
            <a href="{{ URL::route($currentLocale . '.projects.slug', [$project->slug]) }}">{{ $project->title }}</a>
        @endforeach
    </div>
@stop

@unless (Auth::check())
    You are not signed in.
@endunless
