<?php

namespace App\Services;

use App\Jobs\SendCommentNotificationJob;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getCommentsByPost($postId)
    {
        return $this->commentRepository->getByPost($postId);
    }

    public function getCommentById($id)
    {
        return $this->commentRepository->findById($id);
    }

    public function createComment(array $data)
    {
        $data['user_id'] = Auth::id();
        
        $comment = $this->commentRepository->create($data);
        
        // Despacha o job de notificação em segundo plano
        dispatch(new SendCommentNotificationJob($comment));
        
        return $comment->load(['author', 'post']);
    }

    public function updateComment($id, array $data)
    {
        return $this->commentRepository->update($id, $data);
    }

    public function deleteComment($id)
    {
        return $this->commentRepository->delete($id);
    }

    public function getUserComments($userId)
    {
        return $this->commentRepository->getByUser($userId);
    }
}