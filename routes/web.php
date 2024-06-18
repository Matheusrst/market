<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

//rotas de registro
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('users.showRegistrationForm');
Route::post('/register', [UserController::class, 'register'])->name('users.register');

//rotas de login
Route::get('/login', [UserController::class, 'showLoginForm'])->name('users.showLoginForm');
Route::post('/login', [UserController::class, 'login'])->name('users.login');

//rotas do menu do usuario
Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users{user}', [UserController::class, 'update'])->name('users.update');

//rotas da carteira
Route::post('/users/{user}/add-funds', [UserController::class, 'addFunds'])->name('users.addFunds');
Route::post('/users/{user}/withdraw-funds', [UserController::class, 'withdrawFunds'])->name('users.withdrawFunds');

//rotas dos produtos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

//rotas de cadastro de produtos
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

//rotas para edição de produto
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

//rota de exclusão de produtos
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

//rotas de compra de produto
Route::post('/products/{id}/purchase', [ProductController::class, 'purchase'])->name('products.purchase')->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});
