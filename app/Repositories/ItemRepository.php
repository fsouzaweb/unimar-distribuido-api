<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository
{
    public function get()
    {
        return Item::with('type')->get();
    }

    public function store($data){
        return Item::create($data);
    }

    public function details($id){
        return Item::findOrFail($id);
    }

    public function update($id,$data){
        $item = $this->details($id);
        $item->update($data);
        return $item;
    }

    public function destroy($id)
    {
        $item = $this->details($id);
        $item->delete();
        return $item;

    }

}
