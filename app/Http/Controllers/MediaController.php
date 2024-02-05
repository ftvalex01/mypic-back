<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaStoreRequest;
use App\Http\Requests\MediaUpdateRequest;
use App\Http\Requests\MediumStoreRequest;
use App\Http\Requests\MediumUpdateRequest;
use App\Http\Resources\MediumCollection;
use App\Http\Resources\MediumResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::all();

        return new MediumCollection($media);
    }

    public function store(MediumStoreRequest $request)
    {
        $medium = Media::create($request->validated());

        return new MediumResource($medium);
    }

    public function show(Request $request, Media $medium)
    {
        return new MediumResource($medium);
    }

    public function update(MediumUpdateRequest $request, Media $medium)
    {
        $medium->update($request->validated());

        return new MediumResource($medium);
    }

    public function destroy(Request $request, Media $medium): Response
    {
        $medium->delete();

        return response()->noContent();
    }
}
