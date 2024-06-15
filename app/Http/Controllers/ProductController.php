<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    public function create()
    {
        return view('create');
    }

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

    public function purchase($id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock > 0) {
            // Decrementar o estoque dentro de uma transação para garantir a atomicidade
            DB::transaction(function() use ($product) {
                $product->stock -= 1;
                $product->save();

                // Registrar a compra sem associar a um usuário
                Purchase::create([
                    'product_id' => $product->id,
                    // Outros campos adicionais, se necessário
                ]);
            });

            return redirect()->route('products.index')->with('success', 'Product purchased successfully!');
        } else {
            return redirect()->route('products.index')->with('error', 'Product out of stock!');
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}

