<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReactionStoreRequest;
use App\Http\Requests\ReactionUpdateRequest;
use App\Http\Resources\ReactionCollection;
use App\Http\Resources\ReactionResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ReactionController extends Controller
{
    public function index(Request $request): Response
    {
        $reactions = Reaction::all();

        return new ReactionCollection($reactions);
    }
    // En App\Http\Controllers\ReactionController.php

public function store(Request $request) {
    $userId = auth()->id();
    $reactableType = $request->reactable_type; // 'Post' o 'Comment'
    $reactableId = $request->reactable_id;

    // Determina el tipo de modelo reactable
    $model = $reactableType === 'Comment' ? Comment::class : Post::class;

    // Encuentra la entidad a la que se le da like
    $reactable = $model::find($reactableId);
    if (!$reactable) {
        return response()->json(['message' => 'Entity not found'], 404);
    }

    // Verifica si ya existe un like del usuario
    $existingReaction = $reactable->reactions()->where('user_id', $userId)->first();
    if ($existingReaction) {
        $existingReaction->delete();
        return response()->json(['message' => 'Like removed']);
    } else {
        $reactable->reactions()->create(['user_id' => $userId]);
        return response()->json(['message' => 'Like added']);
    }
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
