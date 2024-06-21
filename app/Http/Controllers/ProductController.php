<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * exibição dos produtos
     *
     * @return void
     */
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    /**
     * visualização para criar produtos
     *
     * @return void
     */
    public function create()
    {
        return view('create');
    }

    /**
     * criação de novos produtos 
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    /**
     * função de compra
     *
     * @return void
     */
    public function purchase()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        if ($user->wallet >= $total) {
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                // salvar compra no banco de dados
                Purchase::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $product->price * $cartItem->quantity,
                ]);

                // atualizar estoque
                $product->stock -= $cartItem->quantity;
                $product->save();
            }

            // verificação dos fundos da carteira
            $user->wallet -= $total;
            $user->save();

            // limpar o carrinho
            $user->cartItems()->delete();

            return redirect()->route('products.index')->with('success', 'Purchase completed successfully.');
        }

        return redirect()->route('products.index')->with('error', 'Insufficient funds in wallet.');
    }

    /**
     * visualizaçõa para a edição de produtos
     *
     * @param Product $product
     * @return void
     */
    public function edit(Product $product)
    {
        return view('edit', compact('product'));
    }

    /**
     * edição de produtos cadastrados no banco de dados
     *
     * @param Request $request
     * @param Product $product
     * @return void
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * adição de produtos a lista de favoritos
     *
     * @param Product $product
     * @return void
     */
    public function addToFavorites(Product $product)
    {
        $user = Auth::user();

        // Verifica se o produto já está nos favoritos do usuário
        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            return redirect()->back()->with('error', 'Product is already in favorites.');
        }

        // Adiciona o produto aos favoritos do usuário
        $user->favorites()->create([
            'product_id' => $product->id,
        ]);

        return redirect()->route('favorites.index')->with('success', 'Product added to favorites.');
    }

    /**
     * remoção de produtos dos favoritos
     *
     * @param Product $product
     * @return void
     */
    public function removeFromFavorites(Product $product)
    {
    $user = Auth::user();

    // Verifica se o produto está nos favoritos do usuário
    $favorite = $user->favorites()->where('product_id', $product->id)->first();

    if (!$favorite) {
        return redirect()->back()->with('error', 'Product not found in favorites.');
    }

    // Remove o produto dos favoritos do usuário
    $favorite->delete();

    return redirect()->route('favorites.index')->with('success', 'Product removed from favorites.');
    }

/**
 * apagar produtos
 *
 * @param Product $product
 * @return void
 */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}

