<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
    
        // Obtener los IDs de los usuarios que el usuario actual sigue
        $followingIds = $user->following->pluck('id')->toArray();
    
        // Agregar el propio ID del usuario para incluir sus publicaciones
        $followingIds[] = $user->id;
    
        // Recuperar publicaciones basadas en la configuración de privacidad
        $posts = Post::whereHas('user', function ($query) use ($followingIds, $user) {
            // Incluir publicaciones de usuarios seguidos o de perfiles públicos
            $query->whereIn('id', $followingIds)->orWhere('is_private', false);
        })->with(['user', 'media', 'comments', 'reactions'])->paginate(10);
    
        return PostResource::collection($posts);
    }
    

      
    public function store(Request $request)
    {
        // Iniciar transacción de base de datos
        DB::beginTransaction();
        try {
            if ($request->hasFile('file')) {
                // Almacenar el archivo en el sistema de archivos y obtener la URL
                $path = $request->file('file')->store('uploads', 'public');
                $url = Storage::url($path);

                // Crear el registro Media
                $media = Media::create([
                    'user_id' => $request->user()->id,
                    'type' => $request->input('type', 'photo'), // O considera validar esto en el request
                    'url' => $url,
                    'upload_date' => now(),
                ]);

                // Establecer valores predeterminados para life_time y permanent
                $lifeTime = 24; // Duración de vida por defecto en horas
                $permanent = false; // No es permanente por defecto

                // Verificar si el usuario desea hacer el post permanente y si tiene pines disponibles
                if ($request->input('makePermanent') && $request->user()->available_pines > 0) {
                    $permanent = true;
                    $lifeTime = null; // O manejar como prefieras para posts permanentes
                    // Decrementar un pin del usuario
                    $request->user()->decrement('available_pines');
                }

                // Crear el Post
                $post = Post::create([
                    'user_id' => $request->user()->id,
                    'description' => $request->input('description'),
                    'publish_date' => now(),
                    'life_time' => $lifeTime,
                    'permanent' => $permanent,
                    'media_id' => $media->id,
                ]);

                DB::commit();
                return new PostResource($post);
            } else {
                return response()->json(['error' => 'Archivo no proporcionado.'], 422);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error durante la creación del post: ' . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error durante el proceso de creación del post.'], 500);
        }
    }

    public function explore(Request $request)
{
    $posts = Post::whereHas('user', function ($query) {
        $query->where('is_private', false); // Filtra usuarios con perfiles públicos
    })->with(['user', 'media', 'comments', 'reactions'])->paginate(10); // Ajusta la paginación según necesites

    return PostResource::collection($posts);
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

    public function destroy(Request $request, Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }
}
/* // En tu modelo Post

/**
 * Scope a query to only include active posts.
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
/* public function scopeActive($query)
{
    return $query->where('publish_date', '>=', now()->subHours(24));
}
 */ 