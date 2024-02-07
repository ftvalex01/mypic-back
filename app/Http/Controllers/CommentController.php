<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Request $request): Response
    {
        $comments = Comment::all();

        return new CommentCollection($comments);
    }

    public function store(CommentStoreRequest $request, $postId) {
        $validated = $request->validated();
        Log::info('Datos recibidos antes de la validación:', $request->all());
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'text' => $validated['text'],
            // 'comment_date' se puede manejar automáticamente si es un timestamp en tu migración de la base de datos
        ]);
        Log::info('Datos recibidos después de la validación:', $request->validated());
        return new CommentResource($comment);
    }
    public function show(Request $request, Comment $comment): Response
    {
        return new CommentResource($comment);
    }

    public function update(CommentUpdateRequest $request, Comment $comment): Response
    {
        $comment->update($request->validated());

        return new CommentResource($comment);
    }
// Dentro de App\Http\Controllers\CommentController.php

public function like(Request $request, $postId, $commentId) {
    $userId = auth()->id();

    // Encuentra el comentario específico
    $comment = Comment::where('id', $commentId)->where('post_id', $postId)->first();
    
    if (!$comment) {
        return response()->json(['message' => 'Comment not found'], 404);
    }

    // Verifica si ya existe un like del usuario para este comentario
    $existingReaction = $comment->reactions()->where('user_id', $userId)->first();
    
    if ($existingReaction) {
        // Si existe, lo elimina (toggle like)
        $existingReaction->delete();
        $message = 'Like removed from comment';
    } else {
        // Si no existe, crea un nuevo like
        $comment->reactions()->create(['user_id' => $userId]);
        $message = 'Like added to comment';
    }

    return response()->json(['message' => $message]);
}


public function destroy($postId, $commentId) {
    $comment = Comment::where('id', $commentId)->where('post_id', $postId)->first();
    if (!$comment) {
        return response()->json(['message' => 'Comment not found'], 404);
    }

    // Verifica si el usuario actual es el autor del comentario o del post
    if (auth()->id() == $comment->user_id || auth()->id() == $comment->post->user_id) {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    } else {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
}
