<?php

namespace App\Services;

use App\Repositories\PurchaseRepository;

class PurchaseService
{
    private PurchaseRepository $purchaseRepository;

    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    public function get()
    {
        return $this->purchaseRepository->get();
    }

    public function details($id)
    {
        return $this->purchaseRepository->details($id);
    }

    public function store($data)
    {
        return $this->purchaseRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->purchaseRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->purchaseRepository->destroy($id);
    }

}
