<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();

        if (!$user) {
            Log::error('User is not authenticated.');
            return redirect()->route('products.index')->with('error', 'User is not authenticated.');
        }

        if ($user->wallet < $product->price) {
            return redirect()->route('products.index')->with('error', 'Insufficient wallet balance to purchase this product.');
        }

        if ($product->stock <= 0) {
            return redirect()->route('products.index')->with('error', 'Product is out of stock.');
        }

        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->product_id = $product->id;
        $purchase->save();

        $user->wallet -= $product->price;

        if ($user->save()) {
            Log::info('User wallet updated successfully.');
        } else {
            Log::error('Failed to update user wallet.');
        }

        $product->stock -= 1;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product purchased successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}

