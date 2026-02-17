@extends('layouts.main')

@section('content')
    <section class="container mx-auto w-[80%]">
        <h1 class="text-2xl"> Artist List</h1>
        @can('create', \App\Models\Artist::class)
        <div class="my-2">
            <a href="{{ route('artists.create') }}" class="px-4 py-2 border bg-blue-300">
                New Artist
            </a>
            @endcan
        </div>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
            </tr>
            </thead >
            <tbody>
            @foreach($artists as $artist)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $artist->image_path }}</td>
                    <td>
                        <a href="{{ route('artists.show', ['artist' => $artist->id]) }}">
                            {{ $artist->name }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </section>
@endsection
