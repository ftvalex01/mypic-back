<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumStoreRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Http\Resources\AlbumCollection;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlbumController extends Controller
{
    public function index(Request $request): Response
    {
        $albums = Album::all();

        return new AlbumCollection($albums);
    }

    public function store(AlbumStoreRequest $request): Response
    {
        $album = Album::create($request->validated());

        return new AlbumResource($album);
    }

    public function show(Request $request, Album $album): Response
    {
        return new AlbumResource($album);
    }

    public function update(AlbumUpdateRequest $request, Album $album): Response
    {
        $album->update($request->validated());

        return new AlbumResource($album);
    }

    public function destroy(Request $request, Album $album): Response
    {
        $album->delete();

        return response()->noContent();
    }
}
