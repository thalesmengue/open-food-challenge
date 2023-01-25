<?php

use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [DatabaseController::class, 'getStatus']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{code}', [ProductController::class, 'show']);
Route::put('/products/{code}', [ProductController::class, 'update']);
Route::delete('/products/{code}', [ProductController::class, 'delete']);
