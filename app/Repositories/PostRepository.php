<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function getAll($filters = [])
    {
        $query = $this->model->with(['author', 'category', 'tags'])
                            ->orderBy('created_at', 'desc');

        // Filtro por categoria
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filtro por autor
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Filtro por status de publicação
        if (isset($filters['published'])) {
            if ($filters['published']) {
                $query->whereNotNull('published_at');
            } else {
                $query->whereNull('published_at');
            }
        }

        return $query->paginate(15);
    }

    public function findById($id)
    {
        return $this->model->with(['author', 'category', 'tags', 'comments.author'])
                          ->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $post = $this->findById($id);
        $post->update($data);
        return $post;
    }

    public function delete($id)
    {
        $post = $this->findById($id);
        return $post->delete();
    }

    public function getByUser($userId)
    {
        return $this->model->with(['category', 'tags'])
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function getPublished()
    {
        return $this->model->with(['author', 'category', 'tags'])
                          ->whereNotNull('published_at')
                          ->orderBy('published_at', 'desc')
                          ->paginate(15);
    }
}