<?php

namespace App\Http\Controllers;

use App\Http\Requests\ViewStoreRequest;
use App\Http\Requests\ViewUpdateRequest;
use App\Http\Resources\ViewCollection;
use App\Http\Resources\ViewResource;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ViewController extends Controller
{
    public function index(Request $request): Response
    {
        $views = View::all();

        return new ViewCollection($views);
    }

    public function store(ViewStoreRequest $request): Response
    {
        $view = View::create($request->validated());

        return new ViewResource($view);
    }

    public function show(Request $request, View $view): Response
    {
        return new ViewResource($view);
    }

    public function update(ViewUpdateRequest $request, View $view): Response
    {
        $view->update($request->validated());

        return new ViewResource($view);
    }

    public function destroy(Request $request, View $view): Response
    {
        $view->delete();

        return response()->noContent();
    }
}
