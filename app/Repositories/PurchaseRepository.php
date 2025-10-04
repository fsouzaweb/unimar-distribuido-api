<?php

namespace App\Repositories;

use App\Models\Purchase;

class PurchaseRepository
{
    public function get()
    {
        return Purchase::all();
    }

    public function details($id)
    {
        return Purchase::findOrFail($id);
    }

    public function store($data)
    {
        return Purchase::create($data);
    }

    public function update($id, $data)
    {
        $purchase = $this->details($id);
        $purchase->update($data);
        return $purchase;
    }

    public function destroy($id)
    {
        $purchase = $this->details($id);
        $purchase->delete();
        return $purchase;
    }

}
