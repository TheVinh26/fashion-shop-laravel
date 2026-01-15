@extends('layouts.app')
  
@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-6">Product List</h1>
    
    <form method="GET" action="{{ route('products.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search product..."
            class="border rounded px-4 py-2"
        >

        <select name="category" class="border rounded px-4 py-2">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <select name="sort" class="border rounded px-4 py-2">
            <option value="">Default</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                Price: Low → High
            </option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                Price: High → Low
            </option>
        </select>

        <button class="bg-blue-600 text-white rounded px-4 py-2">
            Filter
        </button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 ">

        @foreach ($products as $product)
            <div class="border rounded-lg shadow p-4 bg-white">
                {{-- <img src="{{ $product->images->first()->url ?? '/default.jpg' }}"
                     class="w-full h-40 object-cover rounded"> --}}
                <img
                    src="{{ $product->main_image_url }}"
                            alt="{{ $product->name }}"
                    class="w-full h-40 object-cover rounded"
                />


                <h2 class="text-lg font-semibold mt-2">{{ $product->name }}</h2>

                <p class="text-gray-600 text-sm mt-1">
                    {{ Str::limit($product->description, 50) }}
                </p>

                <p class="font-bold text-blue-600 mt-2">
                    {{ number_format($product->price, 0, ',', '.') }} USD
                </p>
 
                <a href="{{ route('products.show', $product->slug) }}"
                   class="block mt-3 w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    View Detail
                </a>
            </div>
        @endforeach

    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>
@endsection