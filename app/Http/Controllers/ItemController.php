<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function get()
    {
        $items = $this->itemService->get();
        return ItemResource::collection($items);
    }

    public function store(ItemStoreRequest $request)
    {
        $data = $request->validated();

        $item = $this->itemService->store($data);

        return new ItemResource($item);

    }

    public function details($id)
    {
        try {

            $item = $this->itemService->details($id);
            return new ItemResource($item);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => "Item Not Found"
            ], 404);
        }
    }

    public function update(ItemUpdateRequest $request, $id)
    {

        $data = $request->validated();
        try {
            $item = $this->itemService->update($id, $data);
            return  new ItemResource($item);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => "Item Not Found"
            ], 404);
        }

    }

    public function destroy($id)
    {
        try {
            $item = $this->itemService->destroy($id);
            return new ItemResource($item);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => "Item Not Found"
            ], 404);
        }
    }
}
