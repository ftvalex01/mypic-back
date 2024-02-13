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
        $notifications = Notification::all();
        return new NotificationCollection($notifications);
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

    public function update(NotificationUpdateRequest $request, Notification $notification): Response
    {
        $notification->update($request->validated());

        return new NotificationResource($notification);
    }

    public function destroy(Request $request, Notification $notification): Response
    {
        $notification->delete();

        return response()->noContent();
    }
}
