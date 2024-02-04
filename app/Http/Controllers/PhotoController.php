<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoStoreRequest;
use App\Http\Requests\PhotoUpdateRequest;
use App\Http\Resources\PhotoCollection;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhotoController extends Controller
{
    public function index(Request $request): Response
    {
        $photos = Photo::all();

        return new PhotoCollection($photos);
    }

    public function store(PhotoStoreRequest $request): Response
    {
        $photo = Photo::create($request->validated());

        return new PhotoResource($photo);
    }

    public function show(Request $request, Photo $photo): Response
    {
        return new PhotoResource($photo);
    }

    public function update(PhotoUpdateRequest $request, Photo $photo): Response
    {
        $photo->update($request->validated());

        return new PhotoResource($photo);
    }

    public function destroy(Request $request, Photo $photo): Response
    {
        $photo->delete();

        return response()->noContent();
    }
}
