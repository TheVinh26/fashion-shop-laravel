<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // Apply authentication to all methods

        $this->middleware('auth');
    }

    public function index()
    {
        return Order::with('items.product')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = Order::checkoutForCurrentUser($request->all());

        return response()->json($order->load('items.product'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $order->load('items.product');
    }   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }

    public function checkout()
    {
        $user = auth()->user(); // It always exists because middleware ensures its existence.
    }
}
