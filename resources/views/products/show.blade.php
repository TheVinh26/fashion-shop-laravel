@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="container mx-auto px-4 py-12">

    <div class="bg-white rounded-xl shadow-2xl overflow-hidden p-6 md:p-10">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16">

            <div class="md:sticky md:top-20 h-max">
                
                {{-- Ịmage main (Get from product_images where is_main = true) --}}
                <div class="aspect-square w-full rounded-lg overflow-hidden border border-gray-200 shadow-lg">
                    <img 
                        src="{{ asset('storage/' . ($product->mainImage->image_path ?? 'no-image.png')) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                    />

                </div>
                
                {{-- Gallery Secondary image --}}
                @if($product->images->count() > 1)
                    <div class="mt-4 grid grid-cols-4 gap-3">
                        @foreach ($product->images->where('is_main', false) as $image)
                            <div class="aspect-square rounded-lg overflow-hidden border cursor-pointer">
                                <img 
                                    src="{{ asset('storage/' . $image->image_path) }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            <div class="flex flex-col justify-start">
                
                <p class="text-sm font-semibold text-blue-600 uppercase tracking-wider mb-2">
                    {{ $product->category->name }}
                </p>
                
                <h1 class="text-4xl font-extrabold text-gray-900 mb-3">
                    {{ $product->name ?? 'Luxury Cotton T-Shirt' }}
                </h1>
                
                <p class="text-4xl font-bold text-gray-800 mb-6">
                    {{-- ${{ number_format($product->price ?? 79.99, 2) }} --}}
                    {{ number_format($product->price, 0, ',', '.') }} USD
                </p>

                @if ($product->stock > 0)
                    <p class="text-green-600 font-semibold mb-4">In Stock ({{ $product->stock}} items left)</p>
                @else
                    <p class="text-red-600 font-semibold mb-4">Out of Stock</p>
                @endif
                
                <hr class="my-6">
                
                <form action="{{ route('cart.add', $product->slug) }}" method="POST">
                    @csrf
                    
                    {{-- Size & Color (Add this attribute if needed.) --}}
                    <div class="mb-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800">Select Size</h3>
                        <div class="flex gap-3">
                            @foreach (['S', 'M', 'L', 'XL'] as $size)
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="{{ $size }}" class="peer hidden" required>
                                    <span class="inline-flex items-center justify-center w-10 h-10 border border-gray-300 rounded-full text-sm font-medium hover:border-blue-600 transition duration-200 peer-checked:bg-blue-600 peer-checked:text-white">
                                        {{ $size }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex items-center mb-8 space-x-4">
                        <label for="quantity" class="text-lg font-semibold text-gray-800">Quantity:</label>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $product->stock ?? 10 }}" 
                               class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                    @auth
                        @if ($product->stock > 0)
                            <button type="submit" 
                                    class="w-full md:w-auto px-10 py-3 bg-blue-600 text-white font-bold text-lg rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50"
                            >
                                <span class="flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13v.01"></path></svg>
                                    Add to Cart
                                </span>
                            </button>
                        @else
                            <button type="button" disabled 
                                    class="w-full md:w-auto px-10 py-3 bg-gray-400 text-white font-bold text-lg rounded-lg cursor-not-allowed"
                            >
                                Out of Stock
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                        class="inline-block px-10 py-3 bg-gray-800 text-white rounded-lg">
                            Login to Buy
                        </a>
                    @endauth
                </form>

            </div>
        </div>
        
        <div class="mt-16 pt-10 border-t border-gray-200">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Product Details</h2>
            <div class="prose max-w-none text-gray-700">
                <p>
                    {{ $product->description ?? 'This t-shirt is crafted from premium, long-staple cotton for an incredibly soft feel and a perfect drape. It features a modern, slightly tailored fit, making it an essential piece for both casual and semi-formal wear. Guaranteed to maintain its shape and color after multiple washes.' }}
                </p>
                <ul class="list-disc list-inside mt-4 space-y-2">
                    <li>Material: 100% Organic Cotton</li>
                    <li>Fit: Modern Slim Fit</li>
                    <li>Care: Machine Wash Cold</li>
                    <li>Origin: Made in Portugal</li>
                </ul>
                
                {{-- Thể hiện thuộc tính is_active --}}
                <p class="mt-6 text-sm text-gray-500">
                    Status: <span class="font-medium @if($product->is_active ?? true) text-green-500 @else text-red-500 @endif">{{ $product->is_active ?? true ? 'Available' : 'Inactive' }}</span>
                </p>
            </div>
        </div>
    </div>

    <section class="mt-20">
        <h2 class="text-4xl font-extrabold text-gray-900 mb-10 text-center">
            More Like This
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

            @forelse ($relatedProducts as $related)
                <a href="{{ route('products.show', $related->slug) }}"
                class="block bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:scale-[1.03]">

                    {{-- Image --}}
                    <div class="aspect-square overflow-hidden">
                        <img 
                            {{-- src="{{ $related->mainImage 
                                    ? asset('storage/' . $related->mainImage->image_path) 
                                    : asset('images/no-image.png') }}"
                            alt="{{ $related->name }}" --}}
                            src="{{ $related->main_image_url }}"
                            alt="{{ $related->name }}"
                            class="w-full h-full object-cover"
                        >
                    </div>

                    {{-- Info --}}
                    <div class="p-4 text-center">
                        <h3 class="text-lg font-semibold text-gray-800 truncate mb-1">
                            {{ $related->name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $related->category->name }}
                        </p>

                        <p class="text-xl font-bold text-blue-600 mt-2">
                            ${{ number_format($related->price, 2) }}
                        </p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">
                    No related products found.
                </p>
            @endforelse

        </div>
    </section>
</div>
@endsection