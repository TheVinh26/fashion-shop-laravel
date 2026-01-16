<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Str;


class AdminProductController extends Controller
{
     public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%");
            });
        }

        // Filter category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }   

        // Filter stock
        if ($request->stock === 'out') {
            $query->where('stock', '<=', 0);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $product = Product::createProductByAdmin($request->all());

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully!');
        } catch (\Throwable $e) {
            return back()->withErrors($e->getMessage());
        }
    }
    public function destroy(Product $product)
    {
        $directory = "products/{$product->id}";

        if (Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}