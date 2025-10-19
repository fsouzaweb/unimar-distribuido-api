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
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
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
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        try {
            $tag = $this->tagService->createTag($request->validated());
            return new TagResource($tag);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar tag',
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
            $tag = $this->tagService->getTagById($id);
            return new TagResource($tag);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Tag não encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, $id)
    {
        try {
            $tag = $this->tagService->updateTag($id, $request->validated());
            return new TagResource($tag);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar tag',
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
            $this->tagService->deleteTag($id);
            return response()->json([
                'message' => 'Tag excluída com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir tag',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
