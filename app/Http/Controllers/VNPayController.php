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

        abort_if($order->payment_method !== 'vnpay', 404);

        abort_if($order->status !== 'pending', 400);

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
            "vnp_BankCode" => "NCB",
            "vnp_ExpireDate" => now()->addMinutes(15)->format('YmdHis')
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
        config('vnpay.url') .
        '?' . $query .
        '&vnp_SecureHashType=HmacSHA512' .
        '&vnp_SecureHash=' . $secureHash
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
        if (!$this->verifyHash($request)) {
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ]);
        }

        $order = Order::find($request->vnp_TxnRef);

        if (!$order) {
            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found'
            ]);
        }

        if ($order->status === 'paid') {
            return response()->json([
                'RspCode' => '02',
                'Message' => 'Order already confirmed'
            ]);
        }

        if ($request->vnp_ResponseCode === '00') {
            $order->update([
                'status' => 'paid'
            ]);
        } else {
            $order->update([
                'status' => 'failed'
            ]);
        }

        return response()->json([
            'RspCode' => '00',
            'Message' => 'Confirm Success'
        ]);
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