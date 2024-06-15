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
}

