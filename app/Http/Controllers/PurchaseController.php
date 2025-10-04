<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\PurchaseUpdateRequest;
use App\Http\Resources\PurchaseResource;
use App\Services\PurchaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private PurchaseService $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function get()
    {
        $purchases = $this->purchaseService->get();
        return PurchaseResource::collection($purchases);
    }

    public function details($id)
    {
        try {
            $purchase = $this->purchaseService->details($id);
            return new PurchaseResource($purchase);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Purchase Not Found'], 404);
        }
    }

    public function store(PurchaseStoreRequest $request)
    {
        $data = $request->validated();
        $purchase = $this->purchaseService->store($data);
        return new PurchaseResource($purchase);
    }

    public function update(PurchaseUpdateRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $purchase = $this->purchaseService->update($id, $data);
            return new PurchaseResource($purchase);
        }catch (ModelNotFoundException $exception){
            return response()->json(['error' => 'Purchase Not Found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $purchase = $this->purchaseService->destroy($id);
            return new PurchaseResource($purchase);
        } catch (ModelNotFoundException $exception){
            return response()->json(['error' => 'Purchase Not Found'], 404);
        }
    }
}
