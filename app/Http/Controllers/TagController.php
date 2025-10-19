<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @OA\Get(
     *     path="/tags",
     *     tags={"Tags"},
     *     summary="Listar todas as tags",
     *     description="Retorna uma lista de tags, com opção de listar as mais populares",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="popular",
     *         in="query",
     *         description="Listar apenas tags populares",
     *
     *         @OA\Schema(type="boolean")
     *     ),
     *
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limite de tags populares (padrão: 10)",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tags obtida com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Laravel"),
     *                     @OA\Property(property="slug", type="string", example="laravel"),
     *                     @OA\Property(property="posts_count", type="integer", example=5),
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
            if ($request->has('popular')) {
                $tags = $this->tagService->getPopularTags($request->get('limit', 10));
            } else {
                $tags = $this->tagService->getAllTags();
            }

            return TagResource::collection($tags);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/tags",
     *     tags={"Tags"},
     *     summary="Criar nova tag",
     *     description="Cria uma nova tag no sistema",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Vue.js")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Tag criada com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Vue.js"),
     *                 @OA\Property(property="slug", type="string", example="vue-js"),
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
    public function store(TagRequest $request)
    {
        try {
            $tag = $this->tagService->createTag($request->validated());

            return new TagResource($tag);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar tag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/tags/{id}",
     *     tags={"Tags"},
     *     summary="Exibir tag específica",
     *     description="Retorna os dados de uma tag específica",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tag",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laravel"),
     *                 @OA\Property(property="slug", type="string", example="laravel"),
     *                 @OA\Property(property="posts_count", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag não encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $tag = $this->tagService->getTagById($id);

            return new TagResource($tag);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tag não encontrada',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/tags/{id}",
     *     tags={"Tags"},
     *     summary="Atualizar tag",
     *     description="Atualiza os dados de uma tag existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tag",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="React.js")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="React.js"),
     *                 @OA\Property(property="slug", type="string", example="react-js"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag não encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de validação inválidos"
     *     )
     * )
     */
    public function update(TagRequest $request, $id)
    {
        try {
            $tag = $this->tagService->updateTag($id, $request->validated());

            return new TagResource($tag);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar tag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/tags/{id}",
     *     tags={"Tags"},
     *     summary="Excluir tag",
     *     description="Remove uma tag do sistema",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tag",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tag excluída com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tag excluída com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag não encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->tagService->deleteTag($id);

            return response()->json([
                'message' => 'Tag excluída com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir tag',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
