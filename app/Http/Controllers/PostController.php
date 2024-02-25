<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Hashtag;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    // Obtener los IDs de los usuarios seguidos por el usuario autenticado
    $followingIds = $user->following()->pluck('users.id')->toArray();

    // Agregar el propio ID del usuario para incluir también sus publicaciones
    $followingIds[] = $user->id;

    // Recuperar publicaciones de los usuarios seguidos
    $posts = Post::whereIn('user_id', $followingIds)
        ->with([
            'user', // Cargar información del usuario que publicó
            'media',
            'comments.reactions', // Asegúrate de que esto refleje la relación correcta
            'comments.user', // Cargar usuarios de cada comentario
            'reactions' // Cargar reacciones al post
        ])
        ->orderByDesc('created_at') // Ordenar las publicaciones por fecha de creación
        ->paginate(10); // Paginar los resultados
 
    return PostResource::collection($posts);
}

    public function store(Request $request)
{
    DB::beginTransaction();
    try {
        if ($request->hasFile('file')) {
            // Almacenar el archivo y obtener la URL
            $path = $request->file('file')->store('uploads', 'public');
            $url = Storage::url($path);

            // Establecer valores predeterminados para life_time y permanent
            $lifeTime = $request->input('life_time', 24); // Por defecto en horas
            $permanent = $request->input('permanent', false);

            // Crear el Post
            $post = Post::create([
                'user_id' => $request->user()->id,
                'description' => $request->input('description'),
                'publish_date' => now(),
                'life_time' => $lifeTime,
                'permanent' => $permanent,
            ]);

            // Crear el registro Media asociado al Post
            $media = Media::create([
                'user_id' => $request->user()->id,
                'type' => $request->input('type', 'photo'),
                'url' => $url,
                'upload_date' => now(),
                'post_id' => $post->id,
            ]);

            // Procesamiento de hashtags (si es aplicable)
            preg_match_all('/#(\w+)/', $request->input('description'), $matches);
            $hashtags = array_slice($matches[1], 0, 5);
            foreach ($hashtags as $hashtagName) {
                $hashtag = Hashtag::firstOrCreate(['name' => $hashtagName]);
                $post->hashtags()->attach($hashtag->id);
            }

            DB::commit();
            
            return new PostResource(Post::with(['user', 'media', 'reactions'])->find($post->id));

        } else {
            return response()->json(['error' => 'Archivo no proporcionado.'], 422);
        }
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error durante la creación del post: ' . $e->getMessage());
        return response()->json(['error' => 'Ha ocurrido un error durante el proceso de creación del post.'], 500);
    }
}

public function postComments(Post $post)
    {
        // Asegúrate de tener una relación de comentarios en tu modelo Post
        $comments = $post->comments()->with('user')->get();

        return response()->json($comments);
    }
    public function explore(Request $request)
    {
        $user = Auth::user();

        // Obtén los IDs de los usuarios bloqueados y que han bloqueado al usuario actual
        $blockedUsers = $user ? $user->blockedUsers()->pluck('blocked_user_id')->toArray() : [];
        $blockingUsers = $user ? $user->blockingUsers()->pluck('user_id')->toArray() : [];
        $excludeUsers = array_merge($blockedUsers, $blockingUsers);

        $posts = Post::whereHas('user', function ($query) use ($excludeUsers) {
            $query->where('is_private', false) // Filtra usuarios con perfiles públicos
                ->whereNotIn('id', $excludeUsers); // Excluye usuarios bloqueados y que han bloqueado
        })->with(['user', 'media', 'comments', 'reactions'])->paginate(10); // Ajusta la paginación según necesites

        return PostResource::collection($posts);
    }
    public function recommended(Request $request)
    {
        $user = $request->user();

        // Obtén los IDs de los usuarios bloqueados y que han bloqueado al usuario actual
        $blockedUsers = $user->blockedUsers()->pluck('blocked_user_id')->toArray();
        $blockingUsers = $user->blockingUsers()->pluck('user_id')->toArray();
        $excludeUsers = array_merge($blockedUsers, $blockingUsers);

        // Obtener los IDs de los posts que el usuario ha likeado
        $likedPostIds = $user->reactions()->pluck('reactable_id');

        // Obtener los hashtags de esos posts
        $likedHashtags = Hashtag::whereHas('posts', function ($query) use ($likedPostIds) {
            $query->whereIn('id', $likedPostIds);
        })->pluck('name');

        $recommendedPosts = Post::whereHas('hashtags', function ($query) use ($likedHashtags) {
            $query->whereIn('name', $likedHashtags);
        })->whereDoesntHave('user', function ($query) use ($excludeUsers) {
            $query->whereIn('id', $excludeUsers);
        })->with(['user', 'media', 'comments', 'reactions'])->paginate(10);

        return PostResource::collection($recommendedPosts);
    }

    public function show(Request $request, Post $post)
    {
        return new PostResource($post);
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $post->update($request->validated());

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        

        $post->delete();

        return response()->noContent();
    }

    public function pin(Request $request, Post $post)
    {
        $user = auth()->user();

        if ($user->available_pines > 0) {
            // Actualiza el post para hacerlo permanente
            $post->update(['permanent' => true]);
            $user->decrement('available_pines');
            $post->load('media');
          
            
            return new PostResource($post);
        } else {
            return response()->json(['error' => 'No tienes pines disponibles.'], 403);
        }
    }
    

}
