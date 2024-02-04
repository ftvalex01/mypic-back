<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopItemStoreRequest;
use App\Http\Requests\ShopItemUpdateRequest;
use App\Http\Resources\ShopItemCollection;
use App\Http\Resources\ShopItemResource;
use App\Models\ShopItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopItemController extends Controller
{
    public function index(Request $request): Response
    {
        $shopItems = ShopItem::all();

        return new ShopItemCollection($shopItems);
    }

    public function store(ShopItemStoreRequest $request): Response
    {
        $shopItem = ShopItem::create($request->validated());

        return new ShopItemResource($shopItem);
    }

    public function show(Request $request, ShopItem $shopItem): Response
    {
        return new ShopItemResource($shopItem);
    }

    public function update(ShopItemUpdateRequest $request, ShopItem $shopItem): Response
    {
        $shopItem->update($request->validated());

        return new ShopItemResource($shopItem);
    }

    public function destroy(Request $request, ShopItem $shopItem): Response
    {
        $shopItem->delete();

        return response()->noContent();
    }
}
