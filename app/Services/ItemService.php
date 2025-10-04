<?php

namespace App\Services;

use App\Models\Item;

class ItemService
{
    /**
     * Retorna todos os itens.
     */
    public function get()
    {
        return Item::all();
    }

    /**
     * Cria um novo item.
     */
    public function store(array $data): Item
    {
        return Item::create($data);
    }

    /**
     * Busca um item específico pelo ID.
     * Retorna null se não encontrar.
     */
    public function details(string $id): ?Item
    {
        return Item::find($id);
    }

    /**
     * Atualiza um item existente.
     * Retorna o item atualizado ou null se não encontrar.
     */
    public function update(array $data, string $id): ?Item
    {
        $item = Item::find($id);

        if ($item) {
            $item->update($data);

            return $item;
        }

        return null;
    }

    /**
     * Apaga um item.
     * Retorna true se foi sucesso, false se o item não foi encontrado.
     */
    public function destroy(string $id): bool
    {
        $item = Item::find($id);

        if ($item) {
            return $item->delete();
        }

        return false;
    }
}
