<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Services\ItemService;

class ItemController extends Controller
{
    public function __construct(private ItemService $itemService)
    {
    }

    // get all
    public function get()
    {
        $items = $this->itemService->get();

        return response()->json($items);
    }

    // store
    public function store(ItemStoreRequest $request)
    {
        $item = $this->itemService->store($request->validated());

        return response()->json([
            'message' => 'Item created successfully',
            'item' => $item,
        ], 201);
    }

    // details
    public function details($id)
    {
        $item = $this->itemService->details($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json($item);
    }

    // update
    public function update(ItemUpdateRequest $request, $id)
    {
        $item = $this->itemService->update($request->validated(), $id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json([
            'message' => 'Item updated successfully',
            'item' => $item,
        ]);
    }

    // destroy
    public function destroy($id)
    {
        $deleted = $this->itemService->destroy($id);

        if (!$deleted) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json(null, 204);
    }
}
