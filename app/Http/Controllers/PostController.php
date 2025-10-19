<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['category_id', 'user_id', 'published']);
            $posts = $this->postService->getAllPosts($filters);
            return PostResource::collection($posts);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $post = $this->postService->createPost($request->validated());
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post = $this->postService->getPostById($id);
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post não encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id)
    {
        try {
            $post = $this->postService->updatePost($id, $request->validated());
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->postService->deletePost($id);
            return response()->json([
                'message' => 'Post excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Attach tags to post
     */
    public function attachTags(Request $request, $id)
    {
        try {
            $request->validate([
                'tag_ids' => 'required|array',
                'tag_ids.*' => 'exists:tags,id'
            ]);
            
            $post = $this->postService->attachTags($id, $request->tag_ids);
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao associar tags',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detach tags from post
     */
    public function detachTags(Request $request, $id)
    {
        try {
            $request->validate([
                'tag_ids' => 'required|array',
                'tag_ids.*' => 'exists:tags,id'
            ]);
            
            $post = $this->postService->detachTags($id, $request->tag_ids);
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao desassociar tags',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync tags with post
     */
    public function syncTags(Request $request, $id)
    {
        try {
            $request->validate([
                'tag_ids' => 'required|array',
                'tag_ids.*' => 'exists:tags,id'
            ]);
            
            $post = $this->postService->syncTags($id, $request->tag_ids);
            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao sincronizar tags',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
