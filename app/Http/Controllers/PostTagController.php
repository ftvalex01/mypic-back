<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostTagStoreRequest;
use App\Http\Requests\PostTagUpdateRequest;
use App\Http\Resources\PostTagCollection;
use App\Http\Resources\PostTagResource;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostTagController extends Controller
{
    public function index(Request $request): Response
    {
        $postTags = PostTag::all();

        return new PostTagCollection($postTags);
    }

    public function store(PostTagStoreRequest $request): Response
    {
        $postTag = PostTag::create($request->validated());

        return new PostTagResource($postTag);
    }

    public function show(Request $request, PostTag $postTag): Response
    {
        return new PostTagResource($postTag);
    }

    public function update(PostTagUpdateRequest $request, PostTag $postTag): Response
    {
        $postTag->update($request->validated());

        return new PostTagResource($postTag);
    }

    public function destroy(Request $request, PostTag $postTag): Response
    {
        $postTag->delete();

        return response()->noContent();
    }
}
