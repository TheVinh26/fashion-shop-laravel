<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VNPayController extends Controller
{
    public function create(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        if ($order->status !== 'pending') {
            abort(400, 'Invalid order status');
        }

        $amount = $order->total * 100;

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => config('vnpay.tmn_code'),
            "vnp_Amount" => $amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan don hang #{$order->id}",
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => config('vnpay.return_url'),
            "vnp_TxnRef" => $order->id,
        ];

        ksort($inputData);

        $query = http_build_query($inputData);
        $hashData = urldecode($query);

        $secureHash = hash_hmac(
            'sha512',
            $hashData,
            config('vnpay.hash_secret')
        );

        return redirect(
            config('vnpay.url') . "?" . $query . "&vnp_SecureHash=" . $secureHash
        );
    }

    public function return(Request $request)
    {
        if ($this->verifyHash($request)) {
            if ($request->vnp_ResponseCode == '00') {
                // Payment successful
                return redirect('/')->with('success', 'Payment successful!');
            }
        }

        return redirect('/')->withErrors('Payment failed!');
    }

    public function ipn(Request $request)
    {
        if ($request->vnp_ResponseCode === '00') {

            DB::transaction(function () use ($request) {

                $order = Order::findOrFail($request->vnp_TxnRef);

                if ($order->status !== 'paid') {

                    foreach ($order->user->cart->items as $item) {
                        $item->product->decrement('stock', $item->quantity);

                        $order->items()->create([
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'price' => $item->product->price,
                        ]);
                    }

                    $order->user->cart->items()->delete();

                    $order->update(['status' => 'paid']);
                }
            });
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Success']);
    }

    private function verifyHash(Request $request): bool
    {
        $inputData = $request->except('vnp_SecureHash', 'vnp_SecureHashType');
        ksort($inputData);

        $hashData = urldecode(http_build_query($inputData));

        $secureHash = hash_hmac(
            'sha512',
            $hashData,
            config('vnpay.hash_secret')
        );

        return $secureHash === $request->vnp_SecureHash;
    }
}