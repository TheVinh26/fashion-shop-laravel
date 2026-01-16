<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $item = CartItem::storeItem($request->all());

        return response()->json($item, 201);
    }

    public function show(CartItem $cartItem)
    {
        //
    }

    public function edit(CartItem $cartItem)
    {
        //
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $cartItem->changeQuantity($request->all());

        return back();
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->remove();

        return response()->json(null, 204);
    }
}
