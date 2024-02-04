<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaStoreRequest;
use App\Http\Requests\MediaUpdateRequest;
use App\Http\Resources\MediumCollection;
use App\Http\Resources\MediumResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    public function index(Request $request): Response
    {
        $media = Medium::all();

        return new MediumCollection($media);
    }

    public function store(MediaStoreRequest $request): Response
    {
        $medium = Medium::create($request->validated());

        return new MediumResource($medium);
    }

    public function show(Request $request, Medium $medium): Response
    {
        return new MediumResource($medium);
    }

    public function update(MediaUpdateRequest $request, Medium $medium): Response
    {
        $medium->update($request->validated());

        return new MediumResource($medium);
    }

    public function destroy(Request $request, Medium $medium): Response
    {
        $medium->delete();

        return response()->noContent();
    }
}
