<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categorias"},
     *     summary="Listar todas as categorias",
     *     description="Retorna uma lista paginada de todas as categorias",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias obtida com sucesso",
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
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado"
     *     )
     * )
     */
    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();

            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar categorias',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categorias"},
     *     summary="Criar nova categoria",
     *     description="Cria uma nova categoria no sistema",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Laravel 11")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Categoria criada com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laravel 11"),
     *                 @OA\Property(property="slug", type="string", example="laravel-11"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());

            return new CategoryResource($category);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Categorias"},
     *     summary="Exibir categoria específica",
     *     description="Retorna os dados de uma categoria específica",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Categoria encontrada",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laravel"),
     *                 @OA\Property(property="slug", type="string", example="laravel"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            return new CategoryResource($category);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Categoria não encontrada',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Categorias"},
     *     summary="Atualizar categoria",
     *     description="Atualiza os dados de uma categoria existente",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="Laravel 12")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Categoria atualizada com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Laravel 12"),
     *                 @OA\Property(property="slug", type="string", example="laravel-12"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de validação inválidos"
     *     )
     * )
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->validated());

            return new CategoryResource($category);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Categorias"},
     *     summary="Excluir categoria",
     *     description="Remove uma categoria do sistema",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Categoria excluída com sucesso",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Categoria excluída com sucesso")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);

            return response()->json([
                'message' => 'Categoria excluída com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir categoria',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
