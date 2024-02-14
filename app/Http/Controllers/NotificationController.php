<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationStoreRequest;
use App\Http\Requests\NotificationUpdateRequest;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notificationsQuery = Notification::where('user_id', $user->id);
    
        if ($request->has('unread')) {
            $notificationsQuery->where('read', false);
        }
    
        $notifications = $notificationsQuery->with('user') // Asegúrate de que tienes una relación 'user' en el modelo Notification
                                    ->orderBy('notification_date', 'desc')
                                    ->paginate(10);
    
        return NotificationResource::collection($notifications);
    }
    
    
    
    public function store(NotificationStoreRequest $request): Response
    {
        $notification = Notification::create($request->validated());

        return new NotificationResource($notification);
    }

    public function show(Request $request, Notification $notification): Response
    {
        return new NotificationResource($notification);
    }

    public function update(Request $request, Notification $notification)
    {
        // Asegurarse de que el usuario autenticado es el dueño de la notificación
        if ($request->user()->id !== $notification->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Solo permitir actualizar el estado de 'read'
        $notification->update([
            'read' => true,
        ]);
    
        return new NotificationResource($notification);
    }
    
    public function unreadCount(Request $request)
    {
        $userId = $request->user()->id;
        $count = Notification::where('user_id', $userId)->where('read', false)->count();
    
        return response()->json(['unreadCount' => $count]);
    }
    
    
    public function destroy(Request $request, Notification $notification): Response
    {
        $notification->delete();

        return response()->noContent();
    }
}
