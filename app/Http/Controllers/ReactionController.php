<?php

namespace App\Http\Controllers;

use App\Events\PostReacted;
use App\Http\Requests\ReactionStoreRequest;
use App\Http\Requests\ReactionUpdateRequest;
use App\Http\Resources\ReactionCollection;
use App\Http\Resources\ReactionResource;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReactionController extends Controller
{
    public function index(Request $request): Response
    {
        $reactions = Reaction::all();

        return new ReactionCollection($reactions);
    }
    // En App\Http\Controllers\ReactionController.php

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reactable_id' => 'required|exists:posts,id',
            'reactable_type' => 'required|in:Post,Comment', // Asegúrate de que este valor se maneje correctamente
        ]);

        $userId = auth()->id();
        $reactableType = $validated['reactable_type'];
        $reactableId = $validated['reactable_id'];

        // Determina el tipo de modelo reactable
        $model = $reactableType === 'Comment' ? Comment::class : Post::class;

        // Encuentra la entidad a la que se le da like
        $reactable = $model::findOrFail($reactableId);

        // Verifica si ya existe un like del usuario
        $existingReaction = $reactable->reactions()->where('user_id', $userId)->first();

        if ($existingReaction) {
            $existingReaction->delete();
            // Considera si debes manejar la eliminación de notificaciones aquí
        } else {
            // Crea la reacción
            $reactable->reactions()->create(['user_id' => $userId]);

            // Añade tiempo de vida al post
            $reactable->increment('life_time', 1); // Por ejemplo, incrementa en 1 unidad el tiempo de vida del post
            
            // Crea una notificación para el dueño del post o comentario
            Notification::create([
                'user_id' => $reactable->user_id, // Asume que tu entidad reactable tiene una relación `user`
                'type' => 'reaction', // Aquí podrías tener una lógica para determinar el tipo si hay varios
                'related_id' => $userId,
                'notification_date' => now(),
            ]);

            // Respuesta al cliente
            return response()->json(['message' => 'Like added and notification created']);
        }

        return response()->json(['message' => 'Like removed']);
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
