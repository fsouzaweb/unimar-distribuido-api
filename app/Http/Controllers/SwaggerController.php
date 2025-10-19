<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="API de Blog Pessoal",
 *     version="1.0.0",
 *     description="API RESTful completa para um sistema de blog pessoal desenvolvida em Laravel com autenticação JWT como trabalho de conclusão da matéria Sistemas Distribuídos e API da PÓS em FullStack da Unimar.",
 *
 *     @OA\Contact(
 *         email="fabiano@blog.com",
 *         name="Fabiano Souza de Oliveira"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor de Desenvolvimento"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticação JWT. Use: Bearer {seu_token_jwt}"
 * )
 *
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Endpoints para registro, login e gerenciamento de autenticação"
 * )
 * @OA\Tag(
 *     name="Categorias",
 *     description="CRUD completo para gerenciamento de categorias"
 * )
 * @OA\Tag(
 *     name="Posts",
 *     description="CRUD completo para gerenciamento de posts do blog"
 * )
 * @OA\Tag(
 *     name="Comentários",
 *     description="Gerenciamento de comentários em posts"
 * )
 * @OA\Tag(
 *     name="Tags",
 *     description="CRUD completo para gerenciamento de tags"
 * )
 */
class SwaggerController extends Controller
{
    /**
     * Retorna a documentação Swagger em formato JSON.
     */
    public function json()
    {
        $swaggerPath = public_path('swagger.json');

        if (!file_exists($swaggerPath)) {
            return response()->json([
                'error' => 'Documentação Swagger não encontrada',
                'message' => 'Execute php artisan swagger:generate para gerar a documentação',
            ], 404);
        }

        $content = file_get_contents($swaggerPath);

        return response($content, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Retorna a interface Swagger UI.
     */
    public function ui()
    {
        return view('swagger.ui');
    }
}
