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
    }

    /**
     * @OA\Get(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="Listar todos os posts",
     *     description="Retorna uma lista de posts com filtros opcionais",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtrar por categoria",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filtrar por autor",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="published",
     *         in="query",
     *         description="Filtrar por status de publicação",
     *
     *         @OA\Schema(type="boolean")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de posts obtida com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Meu Primeiro Post"),
     *                     @OA\Property(property="content", type="string", example="Conteúdo do post..."),
     *                     @OA\Property(property="published_at", type="string", format="date-time"),
     *                     @OA\Property(property="author", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Fabiano Souza")
     *                     ),
     *                     @OA\Property(property="category", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Laravel")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
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
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="Criar novo post",
     *     description="Cria um novo post no blog",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title", "content", "category_id"},
     *
     *             @OA\Property(property="title", type="string", example="Meu Novo Post"),
     *             @OA\Property(property="content", type="string", example="Este é o conteúdo do meu post..."),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="published_at", type="string", format="date-time", example="2024-01-15T10:00:00Z"),
     *             @OA\Property(property="tag_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Post criado com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Meu Novo Post"),
     *                 @OA\Property(property="content", type="string", example="Este é o conteúdo do meu post..."),
     *                 @OA\Property(property="published_at", type="string", format="date-time"),
     *                 @OA\Property(property="author", type="object"),
     *                 @OA\Property(property="category", type="object"),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="object"))
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
    public function store(PostRequest $request)
    {
        try {
            $post = $this->postService->createPost($request->validated());

            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar post',
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
            $post = $this->postService->getPostById($id);

            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Post não encontrado',
                'error' => $e->getMessage(),
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
            $this->postService->deletePost($id);

            return response()->json([
                'message' => 'Post excluído com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir post',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Attach tags to post.
     */
    public function attachTags(Request $request, $id)
    {
        try {
            $request->validate([
                'tag_ids' => 'required|array',
                'tag_ids.*' => 'exists:tags,id',
            ]);

            $post = $this->postService->attachTags($id, $request->tag_ids);

            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao associar tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detach tags from post.
     */
    public function detachTags(Request $request, $id)
    {
        try {
            $request->validate([
                'tag_ids' => 'required|array',
                'tag_ids.*' => 'exists:tags,id',
            ]);

            $post = $this->postService->detachTags($id, $request->tag_ids);

            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao desassociar tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync tags with post.
     */
    public function syncTags(Request $request, $id)
    {
        try {
            $request->validate([
                'tag_ids' => 'required|array',
                'tag_ids.*' => 'exists:tags,id',
            ]);

            $post = $this->postService->syncTags($id, $request->tag_ids);

            return new PostResource($post);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao sincronizar tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
