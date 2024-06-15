<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('users.showRegistrationForm');
Route::post('/register', [UserController::class, 'register'])->name('users.register');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products/{id}/purchase', [ProductController::class, 'purchase'])->name('products.purchase');


Route::get('/', function () {
    return view('welcome');
});
