@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl">
        {{ session('success') }}
    </div>
@endif

{{-- @if($errors->any())
    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl">
        {{ $errors->first() }}
    </div>
@endif --}}

@if(session()->has('stock_errors') && is_array(session('stock_errors')))
    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl">
        <p class="font-semibold mb-2">Out of stock products:</p>

        <ul class="list-disc ml-5 space-y-1">
            @foreach(session('stock_errors') as $item)
                <li>
                    {{ $item['name'] }}
                    (Requested: {{ $item['requested'] }},
                    Available: {{ $item['available'] }})
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        <nav class="flex mb-8 text-sm text-gray-500 font-medium">
            <a href="/cart" class="hover:text-blue-600 transition">Cart</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Payment</span>
        </nav>

        <form action="{{ route('order.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <div class="lg:col-span-7 space-y-8">
                    
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                            <h2 class="text-xl font-bold text-gray-900">Delivery information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone number <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                       placeholder="Nhập số điện thoại nhận hàng">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery address <span class="text-red-500">*</span></label>
                                <textarea name="shipping_address" rows="3" required
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                                          placeholder="House number, street name, ward/commune, district/county, province/city">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                            <h2 class="text-xl font-bold text-gray-900">Payment methods</h2>
                        </div>

                        <div class="space-y-4">
                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="cod" class="w-4 h-4 text-blue-600" checked>
                                <div class="ml-4 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Cash on delivery (COD)</p>
                                        <p class="text-xs text-gray-500">Payment in cash upon delivery.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="w-4 h-4 text-blue-600">
                                <div class="ml-4 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Bank transfer</p>
                                        <p class="text-xs text-gray-500">Transfer money directly via bank account.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer">
                                <input type="radio" name="payment_method" value="vnpay" class="w-4 h-4">
                                <div class="ml-4">
                                    <p class="font-bold">VNPay</p>
                                    <p class="text-xs">Payment via bank</p>
                                </div>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Your order</h2>

                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 mb-6">
                            @if($cartItems->isEmpty())
                                <p class="text-center text-gray-500 py-8">
                                    Your shopping cart is empty.
                                </p>
                            @else
                                @foreach($cartItems as $item)
                                <div class="flex items-center gap-4">
                                    <div class="relative w-16 h-16 rounded-lg overflow-hidden border flex-shrink-0">
                                        {{-- <img src="{{ asset($item->product->main_image) }}" class="w-full h-full object-cover"> --}}
                                        <img src="{{ asset(optional($item->product->mainImage)->image_path ?? 'images/no-image.png') }}">

                                        <span class="absolute -top-2 -right-2 bg-gray-800 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full font-bold">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-bold text-gray-800 line-clamp-1">{{ $item->product->name }}</h3>
                                        <p class="text-xs text-gray-500"> Quantity: {{ $item->quantity }} x ${{ number_format($item->product->price, 2) }}</p>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900">
                                        ${{ number_format($item->quantity * $item->product->price, 2) }}
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="space-y-3 border-t pt-6 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Estimate</span>
                                <span>${{ number_format($totalAmount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping fee</span>
                                <span class="text-green-600 font-medium">Free</span>
                            </div>
                            <div class="flex justify-between text-lg font-black text-gray-900 pt-3 border-t">
                                <span>Total</span>
                                {{-- <input type="hidden" name="total" value="{{ $totalAmount }}"> --}}
                                <span class="text-blue-600">${{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-8 bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 transform hover:scale-[1.02]">
                            Order now
                        </button>

                        <p class="text-center text-[11px] text-gray-400 mt-4 px-6">
                            By clicking "Place Order," you agree to Shop Fashion's terms of service.
                        </p>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection