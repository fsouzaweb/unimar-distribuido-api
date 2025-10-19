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
    }

    /**
     * @OA\Get(
     *     path="/comments",
     *     tags={"Comentários"},
     *     summary="Listar comentários",
     *     description="Lista comentários do usuário ou de um post específico",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         description="ID do post para filtrar comentários",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de comentários obtida com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="body", type="string", example="Ótimo post, parabéns!"),
     *                     @OA\Property(property="author", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Fabiano Souza")
     *                     ),
     *                     @OA\Property(property="post", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Meu Post")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     )
     * )
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
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/comments",
     *     tags={"Comentários"},
     *     summary="Criar novo comentário",
     *     description="Cria um novo comentário em um post (dispara job de notificação)",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"body", "post_id"},
     *
     *             @OA\Property(property="body", type="string", example="Ótimo post, parabéns!"),
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Comentário criado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="body", type="string", example="Ótimo post, parabéns!"),
     *                 @OA\Property(property="author", type="object"),
     *                 @OA\Property(property="post", type="object"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Dados de validação inválidos"
     *     )
     * )
     */
    public function store(CommentRequest $request)
    {
        try {
            $comment = $this->commentService->createComment($request->validated());

            return new CommentResource($comment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar comentário',
                'error' => $e->getMessage(),
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
                'error' => $e->getMessage(),
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
                'body' => 'required|string|min:3',
            ]);

            $comment = $this->commentService->updateComment($id, $request->only('body'));

            return new CommentResource($comment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar comentário',
                'error' => $e->getMessage(),
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
                'message' => 'Comentário excluído com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir comentário',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
