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
    public function index(Request $request)
    {
        $comments = Comment::all();

        return new CommentCollection($comments);
    }

   /*  public function store(CommentStoreRequest $request, $postId) {
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
    } */
    public function store(CommentStoreRequest $request, $post)
    {
        // Asegúrate de que el post_id recibido en la ruta sea válido
        $post = Post::findOrFail($post); // Esto automáticamente lanzará un error 404 si el post no existe

        // Crear el comentario asociado al post
        $comment = new Comment();
        $comment->text = $request->text;
        $comment->comment_date = now(); // O usa $request->comment_date si prefieres enviarlo desde el cliente
        $comment->user_id = auth()->id(); // Asumiendo que quieres asociar el comentario al usuario autenticado
        $post->comments()->save($comment); // Asocia y guarda el comentario en relación al post

        // Otra forma, si prefieres usar la asignación masiva y ya tienes configurado el fillable en el modelo Comment
        // $comment = $post->comments()->create([
        //     'text' => $request->text,
        //     'comment_date' => now(), // O usa $request->comment_date
        //     'user_id' => auth()->id(),
        // ]);

        return new CommentResource($comment); // Suponiendo que tienes un CommentResource para formatear la salida
    }
    public function show(Request $request, Comment $comment)
    {
        return new CommentResource($comment);
    }

    public function update(CommentUpdateRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return new CommentResource($comment);
    }
// Dentro de App\Http\Controllers\CommentController.php

public function like(Request $request, $postId, $commentId) {
    $userId = auth()->id();

    // Asegúrate de ajustar esta parte para que funcione con tu estructura polimórfica
    $comment = Comment::where('id', $commentId)
                        ->where('commentable_id', $postId)
                        ->where('commentable_type', Post::class) // Asume que tus comentarios se relacionan con posts de esta manera
                        ->first();
                        $likesCount = $comment->reactions()->count();
                        $isLiked = $comment->reactions()->where('user_id', $userId)->exists();
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

    return response()->json([
        'message' => $message,
        'likesCount' => $likesCount,
        'isLiked' => $isLiked,
    ]);
}


// Método destroy simplificado para solo usar $commentId
public function destroy($commentId) {
    $userId = auth()->id();
    Log::info('Datos recibidos:', ['commentId' => $commentId]);

    $comment = Comment::find($commentId);

    if (!$comment) {
        return response()->json(['message' => 'Comment not found'], 404);
    }

    // Verifica si el usuario actual tiene permiso para borrar el comentario
    if ($userId != $comment->user_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $comment->delete();
    return response()->json(['message' => 'Comment deleted successfully']);
}


}
