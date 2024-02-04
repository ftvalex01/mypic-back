<?php

namespace App\Http\Controllers;

use App\Http\Requests\HashtagStoreRequest;
use App\Http\Requests\HashtagUpdateRequest;
use App\Http\Resources\HashtagCollection;
use App\Http\Resources\HashtagResource;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HashtagController extends Controller
{
    public function index(Request $request): Response
    {
        $hashtags = Hashtag::all();

        return new HashtagCollection($hashtags);
    }

    public function store(HashtagStoreRequest $request): Response
    {
        $hashtag = Hashtag::create($request->validated());

        return new HashtagResource($hashtag);
    }

    public function show(Request $request, Hashtag $hashtag): Response
    {
        return new HashtagResource($hashtag);
    }

    public function update(HashtagUpdateRequest $request, Hashtag $hashtag): Response
    {
        $hashtag->update($request->validated());

        return new HashtagResource($hashtag);
    }

    public function destroy(Request $request, Hashtag $hashtag): Response
    {
        $hashtag->delete();

        return response()->noContent();
    }
}
