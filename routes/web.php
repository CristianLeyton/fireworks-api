<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});


use Illuminate\Http\Request;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
Route::get('/api/categories', [CategoryController::class, 'index']);
Route::get('/api/products', [ProductController::class, 'index']);