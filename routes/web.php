<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('users.showRegistrationForm');
Route::post('/register', [UserController::class, 'register'])->name('users.register');

Route::get('/login', [UserController::class, 'showLoginForm'])->name('users.showLoginForm');
Route::post('/login', [UserController::class, 'login'])->name('users.login');

Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::post('/users/{user}/add-funds', [UserController::class, 'addFunds'])->name('users.addFunds');
Route::post('/users/{user}/withdraw-funds', [UserController::class, 'withdrawFunds'])->name('users.withdrawFunds');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products/{id}/purchase', [ProductController::class, 'purchase'])->name('products.purchase')->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});
