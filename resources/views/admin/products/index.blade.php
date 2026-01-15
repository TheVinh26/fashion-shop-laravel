@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Inventory Management</h1>
            <p class="text-slate-500 text-sm">Manage your products, categories, and stock levels.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Category
            </a>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all text-sm flex items-center gap-2 shadow-lg shadow-blue-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Product
            </a>
        </div>
    </div>

    <div class="border-b border-slate-200">
        <nav class="flex gap-8">
            <button class="border-b-2 border-blue-600 py-4 px-1 text-sm font-bold text-blue-600">
                Products <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2 rounded-full text-xs">{{ $totalProducts }}</span>
            </button>
            <button class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300">
                Categories <span class="ml-2 bg-slate-100 text-slate-500 py-0.5 px-2 rounded-full text-xs">{{ $totalCategories }}</span>
            </button>
        </nav>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        {{-- <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4"> --}}
            {{-- <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Search by name, slug or SKU..." class="block w-full pl-10 pr-3 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm transition-all">
            </div>
            <select class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <option>All Categories</option>
                <option>Active Only</option>
                <option>Out of Stock</option>
            </select> --}}
            <form method="GET" class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4">  
                <div class="relative flex-1">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name or slug..."
                        class="block w-full pl-10 pr-3 py-2 border rounded-lg text-sm">
                </div>

                <select name="category_id" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="stock" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">All Stock</option>
                    <option value="out" @selected(request('stock') == 'out')>
                        Out of Stock
                    </option>
                </select>

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Filter
                </button>
            </form>
        {{-- </div> --}}

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $product)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0">
                                    {{-- <img src="{{ asset($product->images->where('is_main', true)->first()->image_path ?? 'no-image.jpg') }}" class="w-full h-full object-cover"> --}}
                                    {{-- <img src="{{ asset( optional($product->images->where('is_main', true)->first())->image_path ?? 'images/no-image.png') }}" class="w-full h-full object-cover"> --}}
                                    <img
                                        {{-- src="{{ asset('storage/' . optional($product->mainImage)->image_path ?? 'images/no-image.png') }}" --}}
                                        src="{{ $product->main_image_url }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover"
                                    >

                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600 bg-slate-100 px-2 py-1 rounded-md">{{ $product->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 text-sm">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock <= 5)
                                <span class="text-red-600 font-bold text-sm flex items-center gap-1">
                                    <span class="w-2 h-2 bg-red-600 rounded-full animate-pulse"></span>
                                    {{ $product->stock }} Left
                                </span>
                            @else
                                <span class="text-slate-600 text-sm">{{ $product->stock }} in stock</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            {{ $products->links() }}
            <p class="text-sm text-slate-500">
                Showing {{ $products->firstItem() }}
                to {{ $products->lastItem() }}
                of {{ $products->total() }} products
            </p>
            {{-- <div class="flex gap-2">
                <button class="px-3 py-1 border border-slate-200 rounded bg-white text-slate-600 hover:bg-slate-50 disabled:opacity-50">Prev</button>
                <button class="px-3 py-1 border border-blue-600 rounded bg-blue-600 text-white">1</button>
                <button class="px-3 py-1 border border-slate-200 rounded bg-white text-slate-600 hover:bg-slate-50">2</button>
                <button class="px-3 py-1 border border-slate-200 rounded bg-white text-slate-600 hover:bg-slate-50">Next</button>
            </div>           --}}
        </div>
    </div>
</div>
@endsection