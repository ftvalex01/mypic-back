<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserFollower; // Asegúrate de tener este modelo si vas a registrar los seguidores en una tabla separada
use App\Models\Notification;

class FollowsController extends Controller
{
    public function followUser(Request $request, $userIdToFollow)
    {
        $followerId = auth()->id(); // ID del usuario que sigue
        $followedUser = User::findOrFail($userIdToFollow); // Usuario seguido

        // Aquí iría tu lógica para seguir al usuario...
        // Por ejemplo, creando un registro en una tabla UserFollower

        // Crear notificación para el usuario seguido
        Notification::create([
            'user_id' => $userIdToFollow, // El usuario que recibe la notificación
            'type' => 'follow',
            'related_id' => $followerId, // El ID del usuario que sigue
            'notification_date' => now(),
        ]);

        return response()->json(['message' => 'Usuario seguido con éxito y notificación creada']);
    }
}
