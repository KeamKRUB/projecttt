@extends('layouts.main')

@section('content')

    <form action="{{ route('artists.update', ['artist' => $artist]) }}" method="POST">
        @csrf
        @method('PUT')
        <h1 class="text-3xl">Edit Artist</h1>
        <div>
            <label for="name">Name</label>
            @error('name')
            <p class="text-red-500 text-sm">
                {{ $message }}
            </p>
            @enderror
            <input
                class="border-1 border-gray-900 rounded-md p-2 @error('name') border-red-500 @enderror"
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $artist->name) }}"
            >
        </div>
        <div>
            <button type="submit" class="border-1 rounded-md p-2 bg-blue-300">Update Artist</button>
        </div>
    </form>

@endsection
