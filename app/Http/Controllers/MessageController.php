<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    public function index(Request $request): Response
    {
        $messages = Message::all();

        return new MessageCollection($messages);
    }

    public function store(MessageStoreRequest $request): Response
    {
        $message = Message::create($request->validated());

        return new MessageResource($message);
    }

    public function show(Request $request, Message $message): Response
    {
        return new MessageResource($message);
    }

    public function update(MessageUpdateRequest $request, Message $message): Response
    {
        $message->update($request->validated());

        return new MessageResource($message);
    }

    public function destroy(Request $request, Message $message): Response
    {
        $message->delete();

        return response()->noContent();
    }
}
