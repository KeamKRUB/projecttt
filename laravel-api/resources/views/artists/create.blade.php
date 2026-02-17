@extends('layouts.main')

@section('content')

    <form action="{{ route('artists.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h1 class="text-3xl">Create New Artist</h1>
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
                value="{{ old('name', '') }}">

        </div>
        <div class="mb-5">
            <label for="image" class="block mb-2 font-bold text-gray-600">Image</label>
            @error('image')
            <p class="text-red-500 text-sm">
                {{ $message }}
            </p>
            @enderror
            <input type="file" id="image" name="image" autocomplete="off"
                   value="{{ old('image', '') }}"
                   class="border border-gray-300 shadow p-3 w-full rounded
                      @error('image') border-red-400 @enderror" />
        </div>
        <div>
            <button type="submit" class="border-1 rounded-md p-2 bg-blue-300">Add Artist</button>
        </div>
    </form>

@endsection
