<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function addToCart(Product $product)
    {
        $user = Auth::user();
        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function removeFromCart(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

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
                $product->stock -= $cartItem->quantity;
                $product->save();
            }

            $user->wallet -= $total;
            $user->save();

            $user->cartItems()->delete();

            return redirect()->route('cart.index')->with('success', 'Purchase completed successfully.');
        }

        return redirect()->route('cart.index')->with('error', 'Insufficient funds in wallet.');
    }
}