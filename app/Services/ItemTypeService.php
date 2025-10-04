<?php

namespace App\Services;

use App\Repositories\ItemTypeRepository;

class ItemTypeService
{
    private ItemTypeRepository $itemTypeRepository;
    public function __construct(ItemTypeRepository $itemTypeRepository)
    {
        $this->itemTypeRepository = $itemTypeRepository;
    }

    public function get()
    {
        return $this->itemTypeRepository->get();
    }

    public function details($id)
    {
        return $this->itemTypeRepository->details($id);
    }

    public function store($data)
    {
        return $this->itemTypeRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->itemTypeRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->itemTypeRepository->destroy($id);
    }

    public function detailsWithItems($id)
    {
        return $this->itemTypeRepository->detailsWithItems($id);
    }

}
