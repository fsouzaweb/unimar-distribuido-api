<?php

namespace App\Repositories;

use App\Models\ItemType;

class ItemTypeRepository
{
    public function get()
    {
        return ItemType::all();
    }

    public function details($id)
    {
        return ItemType::findOrFail($id);
    }

    public function store($data)
    {
        return ItemType::create($data);
    }

    public function update($id, $data){
        $item_type = $this->details($id);
        $item_type->update($data);
        return $item_type;
    }

    public function destroy($id){
        $item_type = $this->details($id);
        $item_type->delete();
        return $item_type;
    }

    public function detailsWithItems($item_type_id){
        return ItemType::with('items')->findOrFail($item_type_id);
    }

}
