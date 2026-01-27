@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
<style>
    .vnpay-font { font-family: 'Inter', sans-serif; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
    }
</style>

<div class="vnpay-font min-h-screen py-12 px-4 bg-slate-50">
    <div class="max-w-[550px] mx-auto">
        
        <!-- <div class="flex items-center justify-center mb-10 space-x-4">
            <div class="flex items-center text-blue-600">
                <span class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">1</span>
                <span class="ml-2 font-semibold">Confirm Order</span>
            </div>
            <div class="w-12 h-px bg-slate-300"></div>
            <div class="flex items-center text-slate-400">
                <span class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm">2</span>
                <span class="ml-2 font-medium">VNPay</span>
            </div>
            <div class="w-12 h-px bg-slate-300"></div>
            <div class="flex items-center text-slate-400">
                <span class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm">3</span>
                <span class="ml-2 font-medium">Complete</span>
            </div>
        </div> -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center font-bold">
                    1
                </div>
                <span class="font-semibold text-green-600">Confirm Order</span>
            </div>

            <div class="flex-1 h-1 bg-gray-200 mx-4">
                <div id="progress-bar" class="h-1 bg-blue-500 w-0 transition-all duration-500"></div>
            </div>

            <div class="flex items-center space-x-3">
                <div id="step-2"
                    class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold">
                    2
                </div>
                <span id="step-2-text" class="font-semibold text-gray-400">
                    VNPay Payment
                </span>
            </div>
        </div>

        <div class="glass-effect rounded-[2.5rem] shadow-2xl shadow-blue-900/10 border border-white overflow-hidden">
            
            {{-- Header --}}
            <div class="p-8 pb-4 flex justify-between items-center">
                <img src="https://sandbox.vnpayment.vn/apis/assets/images/logo.svg" alt="VNPay Logo" class="h-8">
                <div class="flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-100 italic text-[10px] font-black uppercase tracking-tighter">
                    Secure Gateway
                </div>
            </div>
            <!--  -->
            <div id="vnpay-box"
                class="hidden mt-8 bg-white rounded-xl shadow-lg border border-blue-200">

                <div class="bg-blue-600 text-white p-4 rounded-t-xl flex items-center justify-between">
                    <span class="font-bold text-lg">VNPAY</span>
                    <span class="text-sm">Secure Payment</span>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span>Amount</span>
                        <span class="font-bold text-red-600">
                            {{ number_format($order->total) }} VND
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Order Info</span>
                        <span>#{{ $order->id }}</span>
                    </div>

                    <hr>

                    <div class="space-y-3">
                        <label class="block font-semibold">Select Bank</label>
                        <select class="w-full border rounded-lg p-2">
                            <option>NCB - Ngân hàng Quốc Dân</option>
                            <option>Vietcombank</option>
                            <option>BIDV</option>
                            <option>Techcombank</option>
                        </select>
                    </div>

                    <form action="{{ route('vnpay.create') }}" method="GET">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                        <button
                            class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold">
                            Confirm & Pay
                        </button>
                    </form>
                </div>
            </div>
            <!--  -->
            <div class="px-8 pb-8 pt-2">
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-slate-900 leading-tight">Pay for your order</h1>
                    <p class="text-slate-500 text-sm font-medium">Please double-check the amount and information before transferring to the VNPay payment gateway.</p>
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Mã Đơn</span>
                        <span class="font-mono font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">#{{ $order->id }}</span>
                    </div>

                    <div class="bg-slate-900 text-white rounded-3xl p-6 relative overflow-hidden shadow-xl">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-600 rounded-full blur-3xl opacity-20"></div>
                        
                        <div class="relative z-10">
                            <p class="text-blue-400 text-xs font-bold uppercase tracking-widest mb-1">Total amount to be paid</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black">{{ number_format($order->total) }}</span>
                                <span class="text-xl font-bold opacity-70 italic">VND</span>
                            </div>
                            
                            <hr class="my-5 border-white/10">

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-white/40 text-[10px] uppercase font-bold mb-1">Customer</p>
                                    <p class="font-semibold truncate">{{ $order->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-white/40 text-[10px] uppercase font-bold mb-1">Time</p>
                                    <p class="font-semibold">{{ date('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span>Merchant</span>
                        <span class="font-semibold">VNPAY SANDBOX</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Terminal ID</span>
                        <span class="font-mono text-sm">T4WTEQQ4</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Transaction</span>
                        <span>#{{ $order->id }}</span>
                    </div>

                    <div class="flex justify-between text-lg font-bold text-red-600">
                        <span>Amount</span>
                        <span>{{ number_format($order->total) }} VND</span>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-xs text-blue-700">
                        You will be redirected to VNPay sandbox to complete the payment securely.
                    </div>

                    <form action="{{ route('vnpay.create') }}" method="GET">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">
                            Continue to VNPay
                        </button>
                    </form>

                    <a href="{{ route('checkout.index') }}" 
                       class="block text-center w-full py-2 text-slate-400 font-bold text-sm hover:text-slate-600 transition tracking-tight">
                        ← GO BACK TO CHANGE ADDRESS
                    </a>
                </div>
            </div>

            <div class="bg-slate-50/50 p-6 flex flex-col items-center border-t border-slate-100">
                <div class="flex items-center gap-6 opacity-30 grayscale mb-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/cb/JCB_logo.svg" class="h-4">
                </div>
                <p class="text-[10px] text-slate-400 font-medium italic text-center leading-relaxed">
                    The payment system is secured with 256-bit SSL according to the international PCI DSS standard.<br>
                    You will be safely redirected to the VNPay gateway.
                </p>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('pay-now-btn').addEventListener('click', function () {
        const box = document.getElementById('vnpay-box');
        const progress = document.getElementById('progress-bar');
        const step2 = document.getElementById('step-2');
        const step2Text = document.getElementById('step-2-text');

        box.classList.remove('hidden');

        progress.style.width = '100%';

        step2.classList.remove('bg-gray-300', 'text-gray-600');
        step2.classList.add('bg-blue-600', 'text-white');

        step2Text.classList.remove('text-gray-400');
        step2Text.classList.add('text-blue-600');
    });
</script>
@endsection