<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/items', [App\Http\Controllers\ItemController::class, 'get']);
// Route::post('/items', [App\Http\Controllers\ItemController::class, 'store']);
// Route::get('/items/{id}', [App\Http\Controllers\ItemController::class, 'details']);
// Route::patch('/items/{id}', [App\Http\Controllers\ItemController::class, 'update']);
// Route::delete('/items/:id', [App\Http\Controllers\ItemController::class, 'destroy']);

Route::controller(App\Http\Controllers\ItemController::class)->group(function () {
    Route::get('/items', 'get');
    Route::post('/items', 'store');
    Route::get('/items/{id}', 'details');
    Route::patch('/items/{id}', 'update');
    Route::delete('/items/{id}', 'destroy');
});
