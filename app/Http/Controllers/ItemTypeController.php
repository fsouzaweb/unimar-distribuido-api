<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemTypeStoreRequest;
use App\Http\Requests\ItemTypeUpdateRequest;
use App\Http\Resources\ItemTypeResource;
use App\Services\ItemTypeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    private ItemTypeService $itemTypeService;

    public function __construct(ItemTypeService $itemTypeService)
    {
        $this->itemTypeService = $itemTypeService;
    }

    public function get()
    {
        $item_types = $this->itemTypeService->get();
        return ItemTypeResource::collection($item_types);
    }

    public function details($id)
    {
        try {
            $item_type = $this->itemTypeService->detailsWithItems($id);
            return new ItemTypeResource($item_type);
        }catch (ModelNotFoundException $exception){
            return response()->json(['error' => 'Item Type Not Found'], 404);
        }
    }

    public function store(ItemTypeStoreRequest $request)
    {
        $data = $request->validated();
        $item_type = $this->itemTypeService->store($data);
        return new ItemTypeResource($item_type);
    }

    public function update(ItemTypeUpdateRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $item_type = $this->itemTypeService->update($id, $data);
            return new ItemTypeResource($item_type);
        }catch (ModelNotFoundException $exception){
            return response()->json(['error' => 'Item Type Not Found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $item_type = $this->itemTypeService->destroy($id);
            return new ItemTypeResource($item_type);
        } catch (ModelNotFoundException $exception){
            return response()->json(['error' => 'Item Type Not Found'], 404);
        }
    }
}
