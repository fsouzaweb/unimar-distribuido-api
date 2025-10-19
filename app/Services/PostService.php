<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts($filters = [])
    {
        return $this->postRepository->getAll($filters);
    }

    public function getPostById($id)
    {
        return $this->postRepository->findById($id);
    }

    public function createPost(array $data)
    {
        $data['user_id'] = Auth::id();
        
        $post = $this->postRepository->create($data);
        
        // Associar tags se fornecidas
        if (isset($data['tags']) && is_array($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        return $post->load(['author', 'category', 'tags']);
    }

    public function updatePost($id, array $data)
    {
        $post = $this->postRepository->update($id, $data);
        
        // Atualizar tags se fornecidas
        if (isset($data['tags']) && is_array($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }
        
        return $post->load(['author', 'category', 'tags']);
    }

    public function deletePost($id)
    {
        return $this->postRepository->delete($id);
    }

    public function attachTags($postId, array $tagIds)
    {
        $post = $this->getPostById($postId);
        $post->tags()->attach($tagIds);
        return $post->load('tags');
    }

    public function detachTags($postId, array $tagIds)
    {
        $post = $this->getPostById($postId);
        $post->tags()->detach($tagIds);
        return $post->load('tags');
    }

    public function syncTags($postId, array $tagIds)
    {
        $post = $this->getPostById($postId);
        $post->tags()->sync($tagIds);
        return $post->load('tags');
    }
}