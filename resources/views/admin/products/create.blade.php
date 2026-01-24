@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            <div class="flex items-center gap-2 font-semibold">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <div class="flex items-center gap-2 font-semibold">
                <span>❌</span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex text-sm text-slate-500 mb-2">
                    <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600">Products</a>
                    <span class="mx-2">/</span>
                    <span class="text-slate-900 font-medium">Add New Product</span>
                </nav>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create Product</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-[1.02]">
                    Publish Product
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Basic Information
                    </h2>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" onkeyup="generateSlug()" value="{{ old('name') }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                                   placeholder="e.g. Slim Fit Linen Shirt">
                                   @error('name')
                                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-bold text-slate-700 mb-1 tracking-tight">Product Slug (URL)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm italic">shopfashion.com/products/</span>
                                <input type="text" name="slug" id="slug" 
                                       class="w-full pl-44 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed text-sm"
                                       readonly>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="8" 
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                                      placeholder="Describe the material, fit, and style...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Product Media
                    </h2>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-10 flex flex-col items-center justify-center text-slate-400 hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer group">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <p class="font-bold text-slate-700">Drop files here to upload</p>
                        <p class="text-xs mt-1 italic">The first image will be set as Main Image automatically.</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="">
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Organization</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="category_id" class="block text-sm font-bold text-slate-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-sm font-bold text-slate-700">Active Status</p>
                                <p class="text-xs text-slate-500">Enable for public view</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Pricing & Inventory</h2>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="price" class="block text-sm font-bold text-slate-700 mb-1 text-blue-600">Base Price ($)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">$</span>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" 
                                       class="w-full pl-8 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-lg"
                                       placeholder="0.00">
                                @error('price')
                                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror           
                            </div>
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-bold text-slate-700 mb-1">Inventory (Stock)</label>
                            <input type="number" name="stock" id="stock" 
                                   value="{{ old('stock') }}"
                                   class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                                   placeholder="0">
                            @error('stock')
                                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Function to automatically generate a slug from the product name.
    function generateSlug() {
        let name = document.getElementById('name').value;
        let slug = name.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    }

    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(el => el.remove());
    }, 4000);
</script>
@endsection