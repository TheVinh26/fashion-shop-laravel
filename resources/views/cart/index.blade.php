@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-10 border-b pb-4">
        Shopping Cart
    </h1>

    @if($cartItems->isEmpty())
        <p class="text-gray-600">Your cart is empty.</p>
        <a href="{{ route('products.index') }}" class="text-blue-600 mt-4 inline-block">
            Continue Shopping
        </a>
        @return
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- CART ITEMS --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="hidden md:grid grid-cols-12 text-sm font-semibold text-gray-600 border-b pb-2">
                {{-- <div class="col-span-5">Product</div>
                <div class="col-span-2 text-center">Price</div>
                <div class="col-span-3 text-center">Quantity</div>
                <div class="col-span-2 text-right">Action</div> --}}
                <div class="col-span-5">Product</div>
                <div class="col-span-1 text-center">Size</div>
                <div class="col-span-1 text-center">Price</div>
                <div class="col-span-3 text-center">Quantity</div>
                <div class="col-span-1 text-right">Action</div>
            </div>

            @foreach($cartItems as $item)
            <div class="bg-white p-6 rounded-xl shadow-md grid grid-cols-12 items-center gap-4">

                {{-- PRODUCT --}}
                <div class="col-span-5 flex items-center">
                    <img
                        {{-- src="{{ asset($item->product->mainImage->image_path ?? 'images/default.jpg') }}" --}}
                        {{-- src="{{ $product->mainImage
                            ? asset('storage/' . $product->mainImage->image_path)
                            : asset('images/default.jpg') }}" --}}
                            src="{{ $item->product->main_image_url }}"
                            alt="{{ $item->product->name }}"
                        class="w-20 h-20 rounded-lg object-cover border mr-4"
                    >
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            {{ $item->product->name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $item->product->category->name }}
                        </p>
                    </div>
                </div>

                {{-- SIZE --}}
                <div class="col-span-1 text-center">
                    <span class="inline-block px-3 py-1 text-sm font-semibold bg-gray-100 rounded-full">
                        {{ $item->size ?? '-' }}
                    </span>
                </div>

                {{-- PRICE --}}
                <div class="col-span-2 text-center font-medium">
                    ${{ number_format($item->product->price, 2) }}
                </div>

                {{-- QUANTITY --}}
                <div class="col-span-3 flex justify-center">
                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center">
                        @csrf
                        @method('PATCH')

                        <input type="number"
                               name="quantity"
                               value="{{ $item->quantity }}"
                               min="1"
                               class="w-16 text-center border rounded-lg"
                        >

                        <button class="ml-2 text-blue-600 text-sm">
                            Update
                        </button>
                    </form>
                </div>

                {{-- REMOVE --}}
                <div class="col-span-2 text-right">
                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-700">
                            Remove
                        </button>   
                    </form>
                </div>
            </div>
            @endforeach

            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 font-medium">
                ← Continue Shopping
            </a>
        </div>

        {{-- ORDER SUMMARY --}}
        <div class="lg:col-span-1 sticky top-20">
            <div class="bg-white p-6 rounded-xl shadow-lg border">
                <h2 class="text-2xl font-bold mb-6">Order Summary</h2>

                <div class="flex justify-between mb-4">
                    <span>Subtotal</span>
                    <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                </div>

                <div class="flex justify-between mb-4">
                    <span>Shipping</span>
                    <span>$10.00</span>
                </div>

                <hr class="my-4">

                <div class="flex justify-between text-xl font-bold">
                    <span>Total</span>
                    <span>${{ number_format($subtotal + 10, 2) }}</span>
                </div>

                {{-- <button
                    class="mt-6 w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700"
                >
                    Proceed to Checkout
                </button> --}}

                {{-- <a href="{{ route('checkout.index') }}"
                class="mt-6 block w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 text-center">
                    Proceed to Checkout
                </a> --}}
                @if($cartItems->isEmpty())
                    <button disabled
                        class="mt-6 w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed">
                        Cart is empty
                    </button>
                @else
                    <a href="{{ route('checkout.index') }}"
                    class="mt-6 block w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 text-center">
                        Proceed to Checkout
                    </a>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
