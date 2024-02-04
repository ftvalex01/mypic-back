<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFollowingStoreRequest;
use App\Http\Requests\UserFollowingUpdateRequest;
use App\Http\Resources\UserFollowingCollection;
use App\Http\Resources\UserFollowingResource;
use App\Models\UserFollowing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserFollowingController extends Controller
{
    public function index(Request $request): Response
    {
        $userFollowings = UserFollowing::all();

        return new UserFollowingCollection($userFollowings);
    }

    public function store(UserFollowingStoreRequest $request): Response
    {
        $userFollowing = UserFollowing::create($request->validated());

        return new UserFollowingResource($userFollowing);
    }

    public function show(Request $request, UserFollowing $userFollowing): Response
    {
        return new UserFollowingResource($userFollowing);
    }

    public function update(UserFollowingUpdateRequest $request, UserFollowing $userFollowing): Response
    {
        $userFollowing->update($request->validated());

        return new UserFollowingResource($userFollowing);
    }

    public function destroy(Request $request, UserFollowing $userFollowing): Response
    {
        $userFollowing->delete();

        return response()->noContent();
    }
}
