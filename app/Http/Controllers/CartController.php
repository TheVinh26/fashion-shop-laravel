<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::getUserCart();

        return view('cart.index', [
            'cartItems' => $cart?->items ?? collect(),
            'subtotal'  => $cart?->subtotal() ?? 0,
        ]);      
    }

    public function add(Request $request,string $slug)
    {        
        $product = Product::where('slug', $slug)->firstOrFail();

        Cart::addProductForCurrentUser(
            $product,
            $request->all()
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart successfully!');
    }

    public function store(Request $request)
    {
        //
    }

    public function show()
    {
        $cart = Cart::with('items.product')
        ->where('user_id', Auth::id())
        ->firstOrFail();

        return $cart;
    }

    public function edit(Cart $cart)
    {
        //
    }

    public function update(Request $request, CartItem $item)
    {
        $item->changeQuantity($request->all());

        return back();
    }

    public function remove(CartItem $item)
    {
        $item->remove();    

        return back();
    }

    public function clear($userId)
    {
        Cart::clearForCurrentUser();

        return response()->json(['message' => 'Cart cleared']);
    }
}