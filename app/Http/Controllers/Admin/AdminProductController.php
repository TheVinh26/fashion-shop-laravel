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
        // $products = Product::with('category')->paginate(10);
        // return view('admin.products.index', compact('products'));

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
        // Validate
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Transaction For safety
        DB::beginTransaction();

        try {
            // Create slug if not exits
            $slug = $request->slug ?: Str::slug($request->name);

            // Save Product
            $product = Product::create([
                'name'        => $request->name,
                'slug'        => $slug,
                'description' => $request->description,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'category_id' => $request->category_id,
                'is_active'   => $request->has('is_active'),
            ]);

            // Upload image
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {

                    $fileName = $index === 0
                        ? 'main.' . $image->extension()
                        : Str::uuid() . '.' . $image->extension();

                    $path = $image->storeAs(
                        "products/{$product->id}",
                        $fileName,
                        'public'
                    );

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main'    => $index === 0,
                    ]);
                }
            }

            DB::commit();

            $es->indexProduct([
                'id'          => $product->id,
                'name'        => $product->name,
                'description' => $product->description,
                'price'       => $product->price,
                'category_id' => $product->category_id,
            ]);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors('Something went wrong: ' . $e->getMessage());
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