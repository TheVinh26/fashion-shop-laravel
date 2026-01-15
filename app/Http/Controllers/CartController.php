<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $cart = Cart::with([
        // 'items.product.mainImage',
        // 'items.product.category'
        // ])->where('user_id', Auth::id())->first();

        // $cartItems = $cart?->items ?? collect();

        // // Tính subtotal
        // $subtotal = $cartItems->sum(function ($item) {
        //     return $item->product->price * $item->quantity;
        // });

        // return view('cart.index', compact('cartItems', 'subtotal'));
        $cart = Cart::getUserCart();

        $cartItems = $cart?->items ?? collect();
        $subtotal  = $cart?->subtotal() ?? 0;

        return view('cart.index', compact('cartItems', 'subtotal'));      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add(Request $request, $slug)
    {
        // $request->validate([
        // 'quantity' => 'required|integer|min:1',
        // 'size' => 'required|string',
        // ]);

        // $product = Product::where('slug', $slug)->firstOrFail();

        // $cart = Cart::firstOrCreate([
        //     'user_id' => Auth::id(),
        // ]);

        // $item = CartItem::where('cart_id', $cart->id)
        //     ->where('product_id', $product->id)
        //     ->where('size', $request->size)
        //     ->first();

        // if ($item) {
        //     $item->increment('quantity', $request->quantity);
        // } else {
        //     CartItem::create([
        //         'cart_id' => $cart->id,
        //         'product_id' => $product->id,
        //         'size' => $request->size,
        //         'quantity' => $request->quantity,
        //     ]);
        // }

        // return redirect()
        //     ->route('cart.index')
        //     ->with('success', 'Product added to cart successfully!');
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'size'     => 'required|string',
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();

        $cart = Cart::getOrCreateForUser();
        $cart->addProduct($product, $data['size'], $data['quantity']);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $cart = Cart::with('items.product')
        ->where('user_id', Auth::id())
        ->firstOrFail();

        return $cart;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // update quantity
    public function update(Request $request, CartItem $item)
    {
        // $request->validate([
        // 'quantity' => 'required|integer|min:1',
        // ]);

        // $item->update([
        //     'quantity' => $request->quantity,
        // ]);

        // return back();
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->updateQuantity($data['quantity']);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    // remove product
    public function remove(CartItem $item)
    {
        $item->delete();
        return back();
    }

    public function clear($userId)
    {
        // $cart = Cart::where('user_id', $userId)->firstOrFail();
        // $cart->items()->delete();

        // return response()->json(['message' => 'Cart cleared']);
        $cart = Cart::getOrCreateForUser();
        $cart->clear();

        return response()->json(['message' => 'Cart cleared']);
    }
}