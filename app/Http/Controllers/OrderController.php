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

        // or apply only to some methods

        // $this->middleware('auth')->only(['checkout', 'placeOrder']);
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
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shipping_address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|string',
            'total' => 'required|numeric',
        ]);

        $order = Order::create($data);

        return response()->json($order, 201);
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
        // ...
    }
}
