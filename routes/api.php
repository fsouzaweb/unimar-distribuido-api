<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ItemController::class)->group(function () {
    Route::get('/items', 'get');
    Route::post('/items', 'store');
    Route::get('/items/{id}', 'details');
    Route::patch('items/{id}', 'update');
    Route::delete('items/{id}', 'destroy');
});

Route::controller(ItemTypeController::class)->group(function () {
    Route::get('item-types', 'get');
    Route::post('/item-types', 'store');
    Route::get('/item-types/{id}', 'details');
    Route::patch('item-types/{id}', 'update');
    Route::delete('item-types/{id}', 'destroy');
});

Route::controller(PurchaseController::class)->group(function () {
    Route::get('/purchases', 'get');
    Route::post('/purchases', 'store');
    Route::get('/purchases/{id}', 'details');
    Route::patch('purchases/{id}', 'update');
    Route::delete('purchases/{id}', 'destroy');
});
