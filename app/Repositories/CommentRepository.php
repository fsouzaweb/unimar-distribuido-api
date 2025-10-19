<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function getByPost($postId)
    {
        return $this->model->with(['author'])
                          ->where('post_id', $postId)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function findById($id)
    {
        return $this->model->with(['author', 'post'])
                          ->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $comment = $this->findById($id);
        $comment->update($data);
        return $comment;
    }

    public function delete($id)
    {
        $comment = $this->findById($id);
        return $comment->delete();
    }

    public function getByUser($userId)
    {
        return $this->model->with(['post'])
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    public function getAll()
    {
        return $this->model->with(['author', 'post'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);
    }
}