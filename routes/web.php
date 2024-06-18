<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;

//rotas de registro
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('users.showRegistrationForm');
Route::post('/register', [UserController::class, 'register'])->name('users.register');

//rotas de login
Route::get('/login', [UserController::class, 'showLoginForm'])->name('users.showLoginForm');
Route::post('/login', [UserController::class, 'login'])->name('users.login');

Route::middleware('auth.user')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
//rotas do menu do usuario
Route::get('/users', [UserController::class, 'index'])->name('users.index');

//rotas de edição de usuario
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users{user}', [UserController::class, 'update'])->name('users.update');

//rotas da carteira
Route::post('/users/{user}/add-funds', [UserController::class, 'addFunds'])->name('users.addFunds');
Route::post('/users/{user}/withdraw-funds', [UserController::class, 'withdrawFunds'])->name('users.withdrawFunds');

//rotas de exclusão de usuarios
Route::get('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

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

//rota de favoritos
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/products/{product}/add-to-favorites', [ProductController::class, 'addToFavorites'])->name('favorites.add');

//rotas de logout
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

});

Route::get('/', function () {
    return view('welcome');
});
