<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->has('post_id')) {
                $comments = $this->commentService->getCommentsByPost($request->post_id);
            } else {
                $comments = $this->commentService->getUserComments(auth()->id());
            }
            return CommentResource::collection($comments);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar comentários',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        try {
            $comment = $this->commentService->createComment($request->validated());
            return new CommentResource($comment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar comentário',
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
            $comment = $this->commentService->getCommentById($id);
            return new CommentResource($comment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Comentário não encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'body' => 'required|string|min:3'
            ]);
            
            $comment = $this->commentService->updateComment($id, $request->only('body'));
            return new CommentResource($comment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar comentário',
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
            $this->commentService->deleteComment($id);
            return response()->json([
                'message' => 'Comentário excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir comentário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
