<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artists = Artist::query()->paginate(10);
        return ArtistResource::collection($artists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', 'unique:artists,name'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048']
        ]);

        $artist = new Artist();
        $artist->name = $request->name;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $artist->image_path = $path;
        }

        $artist->save();

        return new ArtistResource($artist->refresh());
    }


    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        Gate::authorize('view', $artist);
        return new ArtistResource($artist);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        Gate::authorize('update', $artist);
        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('artists', 'name')->ignore($artist)
            ],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048']
        ]);

        $artist->name = $request->name;

        if ($request->hasFile('image')) {

            // ลบรูปเก่า (ถ้ามี)
            if ($artist->image_path && \Storage::disk('public')->exists($artist->image_path)) {
                \Storage::disk('public')->delete($artist->image_path);
            }

            // เก็บรูปใหม่
            $path = $request->file('image')->store('artists', 'public');
            $artist->image_path = $path;
        }

        $artist->save();

        return new ArtistResource($artist->refresh());
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        Gate::authorize('delete', $artist);
        $artist->delete();
        return response()->json(null, 204);
    }
}
