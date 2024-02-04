<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostHashtagStoreRequest;
use App\Http\Requests\PostHashtagUpdateRequest;
use App\Http\Resources\PostHashtagCollection;
use App\Http\Resources\PostHashtagResource;
use App\Models\PostHashtag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostHashtagController extends Controller
{
    public function index(Request $request): Response
    {
        $postHashtags = PostHashtag::all();

        return new PostHashtagCollection($postHashtags);
    }

    public function store(PostHashtagStoreRequest $request): Response
    {
        $postHashtag = PostHashtag::create($request->validated());

        return new PostHashtagResource($postHashtag);
    }

    public function show(Request $request, PostHashtag $postHashtag): Response
    {
        return new PostHashtagResource($postHashtag);
    }

    public function update(PostHashtagUpdateRequest $request, PostHashtag $postHashtag): Response
    {
        $postHashtag->update($request->validated());

        return new PostHashtagResource($postHashtag);
    }

    public function destroy(Request $request, PostHashtag $postHashtag): Response
    {
        $postHashtag->delete();

        return response()->noContent();
    }
}
