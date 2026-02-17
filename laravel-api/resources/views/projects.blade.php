@extends('layouts.main')

@section('content')

@if(!isset($title))
    <h1 class="text-4xl text-center text-blue-600">Projects</h1>

    @isset($search)
        <p class="text-center mt-2">Search: {{ $search }}</p>
    @endisset

    <ul class="mt-4 text-center">
        @forelse($projects as $project)
            <li class="text-2xl"><a href="{{ url('projects/' . $project) }}">{{ $project }}</a></li>
        @empty
            <li class="text-red-500 text-2xl">No project found</li>
        @endforelse
    </ul>

@elseif(in_array(strtolower($title), $projects))
    <h1 class="text-4xl text-center text-blue-600">Project found</h1>
    <h2 class="text-3xl text-center mt-4">Title: {{ $title }}</h2>

@else
    <h2 class="text-4xl text-center text-red-600">Project not found</h2>
@endif

@endsection
