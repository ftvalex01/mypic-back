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
                'comments.user', // Cargar usuarios de cada comentario
                'reactions' // Cargar reacciones al post
            ])
            ->orderByDesc('created_at') // Ordenar las publicaciones por fecha de creación
            ->paginate(10); // Paginar los resultados

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
                preg_match_all('/#(\w+)/', $request->input('description'), $matches);
                $hashtags = array_slice($matches[1], 0, 5);

                foreach ($hashtags as $hashtagName) {
                    $hashtag = Hashtag::firstOrCreate(['name' => $hashtagName]);
                    $post->hashtags()->attach($hashtag->id);
                }
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
    public function recommended(Request $request)
    {
        $user = $request->user();

        // Obtener los IDs de los posts que el usuario ha likeado
        $likedPostIds = $user->reactions()->pluck('reactable_id');

        // Obtener los hashtags de esos posts
        $likedHashtags = Hashtag::whereHas('posts', function ($query) use ($likedPostIds) {
            $query->whereIn('id', $likedPostIds);
        })->pluck('name');
        Log::info('Liked Post IDs:', $likedPostIds->toArray());
        Log::info('Liked Hashtags:', $likedHashtags->toArray());
        // Buscar otros posts que contengan esos hashtags
        $recommendedPosts = Post::whereHas('hashtags', function ($query) use ($likedHashtags) {
            $query->whereIn('name', $likedHashtags);
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

    public function destroy(Request $request, Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }
}
