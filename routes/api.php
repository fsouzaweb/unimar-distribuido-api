<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotas de Autenticação
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    // CRUD de Categorias
    Route::apiResource('categories', CategoryController::class);
    
    // CRUD de Posts
    Route::apiResource('posts', PostController::class);
    
    // Rotas adicionais para gerenciamento de tags em posts
    Route::post('posts/{id}/tags/attach', [PostController::class, 'attachTags']);
    Route::post('posts/{id}/tags/detach', [PostController::class, 'detachTags']);
    Route::post('posts/{id}/tags/sync', [PostController::class, 'syncTags']);
    
    // CRUD de Comentários
    Route::apiResource('comments', CommentController::class);
    
    // CRUD de Tags
    Route::apiResource('tags', TagController::class);
});