<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;

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
//Route::post('/products/{id}/purchase', [ProductController::class, 'purchase'])->name('products.purchase')->middleware('auth');

//rotas do carrinho de compras
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/products/purchase', [ProductController::class, 'purchase'])->name('cart.purchase');

//rota de favoritos
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/favorites/add/{product}', [ProductController::class, 'addToFavorites'])->name('favorites.add');
Route::delete('/favorites/remove/{product}', [ProductController::class, 'removeFromFavorites'])->name('favorites.remove');

//rotas de logout
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

});

Route::get('/', function () {
    return view('welcome');
});
