@extends('layouts.main')

@section('content')
<h1 class="text-4xl text-center text-blue-600 mb-6">Labs</h1>

<ul class="list-disc pl-6 ml-9">
    @foreach ($labs as $lab)
        <li>{{ $lab }}</li>
    @endforeach
</ul>

<div class="mt-6 ml-9 flex gap-2">
    @for ($i = 1; $i <= $totalPages; $i++)
        <a href="?p={{ $i }}&pageSize={{ $pageSize }}" class="{{ $i === $page ? 'text-blue-500' : '' }}">
            {{ $i }}
        </a>
    @endfor
</div>
@endsection
