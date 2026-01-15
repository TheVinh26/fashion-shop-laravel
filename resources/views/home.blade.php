@extends('layouts.app')

@section('title', 'Shop Fashion - Home')

@section('content')
<main>
    <section class="bg-gray-100 py-20 md:py-32">
        <div class="container mx-auto px-4 md:flex items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight">
                    Style Your Story
                </h1>
                <p class="mt-4 text-xl text-gray-600">
                    Explore the latest trends and timeless classics. New arrivals every week!
                </p>
                <a href="{{route('products.index')}}" class="mt-8 inline-block bg-blue-600 text-white text-lg font-semibold px-8 py-3 rounded-full hover:bg-blue-700 transition duration-300 shadow-lg transform hover:scale-105">
                    Shop New Arrivals
                </a>
            </div>
            <div class="md:w-1/2 flex justify-center">
                {{-- Replace with your actual hero image using Blade asset() helper --}}
                <img src="{{ asset('images/hero-model.jpg') }}" alt="Fashion Model" class="rounded-lg shadow-2xl max-h-96 object-cover">
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Shop By Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="" class="group relative overflow-hidden rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('images/category-men.jpg') }}" alt="Men's Fashion" class="w-full h-80 object-cover transform group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <span class="text-white text-3xl font-extrabold tracking-wider border-b-4 border-blue-400 pb-1">MEN</span>
                    </div>
                </a>
                <a href="" class="group relative overflow-hidden rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('images/category-women.jpg') }}" alt="Women's Fashion" class="w-full h-80 object-cover transform group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <span class="text-white text-3xl font-extrabold tracking-wider border-b-4 border-pink-400 pb-1">WOMEN</span>
                    </div>
                </a>
                <a href="" class="group relative overflow-hidden rounded-lg shadow-xl hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('images/category-accessories.jpg') }}" alt="Accessories" class="w-full h-80 object-cover transform group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <span class="text-white text-3xl font-extrabold tracking-wider border-b-4 border-yellow-400 pb-1">ACCESSORIES</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">New Arrivals</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Loop 4 new products here --}}
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                    class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 group">

                        <img 
                            {{-- src="{{ asset($product->mainImage->image_path ?? 'images/default.jpg') }}" --}}
                            src="{{ $product->mainImage
                                ? asset('storage/' . $product->mainImage->image_path)
                                : asset('images/default.jpg') }}"
                            alt="{{ $product->name }}"
                            class="w-full h-64 object-cover">

                        <div class="p-4 text-center">
                            <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 transition">
                                {{ $product->name }}
                            </h3>

                            <p class="text-gray-500 mt-1">
                                {{ $product->category->name }}
                            </p>

                            <p class="text-xl font-bold text-blue-600 mt-2">
                                ${{ number_format($product->price, 2) }}
                            </p>
                        </div>
                    </a>
                @endforeach

            </div>
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" class="inline-block border border-gray-300 text-gray-800 font-semibold px-8 py-3 rounded-full hover:bg-gray-100 transition duration-300">
                    View All Products
                </a>
            </div>
        </div>
    </section>
</main>
@endsection