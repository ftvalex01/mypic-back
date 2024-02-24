<?php

namespace App\Http\Controllers;


use App\Http\Requests\MediumStoreRequest;
use App\Http\Requests\MediumUpdateRequest;
use App\Http\Resources\MediumCollection;
use App\Http\Resources\MediumResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $media = Media::all();

        return new MediumCollection($media);
    }

    public function store(MediumStoreRequest $request)
    {
        try {
            if ($request->hasFile('file')) {
                // Almacenar el archivo en el disco público y obtener la ruta
                $path = $request->file('file')->store('uploads', 'public');
                $url = Storage::url($path);

                // Crear un nuevo registro en la base de datos con la URL del archivo
                $medium = Media::create([
                    'user_id' => $request->user_id,
                    'type' => $request->type,
                    'url' => $url,
                    'upload_date' => now(), // Aquí usas la URL del archivo almacenado
                ]);

                

                return new MediumResource($medium);
            } else {
                return response()->json(['error' => 'No file provided.'], 422);
            }
        } catch (\Exception $e) {
            Log::error('Error durante el registro: ' . $e->getMessage());
            

            return response()->json(['error' => 'Ha ocurrido un error durante el proceso de registro.'], 500);
        }
    }

    public function show(Request $request, Media $medium)
    {
        return new MediumResource($medium);
    }

    public function update(MediumUpdateRequest $request, Media $medium)
    {
        $medium->update($request->validated());

        return new MediumResource($medium);
    }

    public function destroy(Request $request, Media $medium): Response
    {
        $medium->delete();

        return response()->noContent();
    }

    public function getUserImages($userId)
    {
        $user = User::with('followers')->findOrFail($userId);
        $requestingUser = auth()->user();
    
        if ($user->is_private) {
            if (!$user->followers->contains($requestingUser->id) && $user->id != $requestingUser->id) {
                return response()->json(['message' => 'No autorizado para ver las imágenes.'], 403);
            }
        }
    
        $images = $user->media()->where('type', 'photo')->get();
        $images->load(['post' => function ($query) {
            $query->select('id', 'user_id', 'description', 'publish_date', 'life_time', 'permanent', 'created_at', 'updated_at');
            // No incluyas 'likesCount' aquí ya que es un atributo calculado
        }]);
    
        
    
        // Clasifica las imágenes en vivas o permanentes según tu lógica
        $liveImages = $images->filter(function ($image) {
            return !$image->post || !$image->post->permanent; // Ajusta esta condición según sea necesario
        });
    
        $permanentImages = $images->filter(function ($image) {
            return $image->post && $image->post->permanent;
        });
    
        return response()->json([
            'liveImages' => MediumResource::collection($liveImages),
            'permanentImages' => MediumResource::collection($permanentImages),
        ]);
    }

}
