<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\PurchaseUpdateRequest;
use App\Http\Resources\PurchaseCollection;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseController extends Controller
{
    public function index(Request $request): Response
    {
        $purchases = Purchase::all();

        return new PurchaseCollection($purchases);
    }

    public function store(PurchaseStoreRequest $request): Response
    {
        $purchase = Purchase::create($request->validated());

        return new PurchaseResource($purchase);
    }

    public function show(Request $request, Purchase $purchase): Response
    {
        return new PurchaseResource($purchase);
    }

    public function update(PurchaseUpdateRequest $request, Purchase $purchase): Response
    {
        $purchase->update($request->validated());

        return new PurchaseResource($purchase);
    }

    public function destroy(Request $request, Purchase $purchase): Response
    {
        $purchase->delete();

        return response()->noContent();
    }
}
