<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFollowerStoreRequest;
use App\Http\Requests\UserFollowerUpdateRequest;
use App\Http\Resources\UserFollowerCollection;
use App\Http\Resources\UserFollowerResource;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserFollowerController extends Controller
{
    public function index(Request $request): Response
    {
        $userFollowers = UserFollower::all();

        return new UserFollowerCollection($userFollowers);
    }

    public function store(UserFollowerStoreRequest $request): Response
    {
        $userFollower = UserFollower::create($request->validated());

        return new UserFollowerResource($userFollower);
    }

    public function show(Request $request, UserFollower $userFollower): Response
    {
        return new UserFollowerResource($userFollower);
    }

    public function update(UserFollowerUpdateRequest $request, UserFollower $userFollower): Response
    {
        $userFollower->update($request->validated());

        return new UserFollowerResource($userFollower);
    }

    public function destroy(Request $request, UserFollower $userFollower): Response
    {
        $userFollower->delete();

        return response()->noContent();
    }
}
