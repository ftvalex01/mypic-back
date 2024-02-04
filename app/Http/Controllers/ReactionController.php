<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReactionStoreRequest;
use App\Http\Requests\ReactionUpdateRequest;
use App\Http\Resources\ReactionCollection;
use App\Http\Resources\ReactionResource;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReactionController extends Controller
{
    public function index(Request $request): Response
    {
        $reactions = Reaction::all();

        return new ReactionCollection($reactions);
    }

    public function store(ReactionStoreRequest $request): Response
    {
        $reaction = Reaction::create($request->validated());

        return new ReactionResource($reaction);
    }

    public function show(Request $request, Reaction $reaction): Response
    {
        return new ReactionResource($reaction);
    }

    public function update(ReactionUpdateRequest $request, Reaction $reaction): Response
    {
        $reaction->update($request->validated());

        return new ReactionResource($reaction);
    }

    public function destroy(Request $request, Reaction $reaction): Response
    {
        $reaction->delete();

        return response()->noContent();
    }
}
