<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Exceptions\InsufficientStockException;
class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        $cartItems = $cart->items;

        $totalAmount = $cartItems->sum(fn ($item) =>
            $item->quantity * $item->product->price
        );

        return view('checkout.index', compact('cartItems', 'totalAmount'));
    }

    public function store(Request $request)
    {
        try {
            $order = Order::placeOrder(
                userId: auth()->id(),
                phone: $request->phone,
                shippingAddress: $request->shipping_address,
                paymentMethod: $request->payment_method
            );

            // If you select VNPay → redirect to VNPay
            if ($request->payment_method === 'vnpay') {
                return redirect()->route('vnpay.confirm', $order);
            }

            return redirect()
                ->route('checkout.index')
                ->with('success', 'Order placed successfully');

        } catch (InsufficientStockException $e) {

            return back()
            ->with('stock_errors', $e->products)
            ->withErrors([
                'stock' => 'Some products are out of stock.'
            ])
            ->withInput();

        } catch (Exception $e) {

            return back()
                ->withErrors([
                    'error' => $e->getMessage()
                ])
                ->withInput();
        }
    }
}
