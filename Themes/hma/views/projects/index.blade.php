@extends('layouts.master')

@section('title')
    Projects | @parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>Projects</h1>
            <?php if (isset($projects)): ?>
            <ul>
                <?php foreach ($projects as $project): ?>
                    <li>
                        <span class="date">{{ $project->created_at->format('d-m-Y') }}</span>
                        <h3><a href="{{ URL::route($currentLocale . '.projects.slug', [$project->slug]) }}">{{ $project->title }}</a></h3>
                    </li>
                    <div class="clearfix"></div>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
@stop
