<?php

namespace App\Http\Controllers;

use App\Http\Requests\InteractionHistoryStoreRequest;
use App\Http\Requests\InteractionHistoryUpdateRequest;
use App\Http\Resources\InteractionHistoryCollection;
use App\Http\Resources\InteractionHistoryResource;
use App\Models\InteractionHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InteractionHistoryController extends Controller
{
    public function index(Request $request): Response
    {
        $interactionHistories = InteractionHistory::all();

        return new InteractionHistoryCollection($interactionHistories);
    }

    public function store(InteractionHistoryStoreRequest $request): Response
    {
        $interactionHistory = InteractionHistory::create($request->validated());

        return new InteractionHistoryResource($interactionHistory);
    }

    public function show(Request $request, InteractionHistory $interactionHistory): Response
    {
        return new InteractionHistoryResource($interactionHistory);
    }

    public function update(InteractionHistoryUpdateRequest $request, InteractionHistory $interactionHistory): Response
    {
        $interactionHistory->update($request->validated());

        return new InteractionHistoryResource($interactionHistory);
    }

    public function destroy(Request $request, InteractionHistory $interactionHistory): Response
    {
        $interactionHistory->delete();

        return response()->noContent();
    }
}
