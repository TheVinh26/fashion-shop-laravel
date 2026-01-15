<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Category::with('children')->get();
        return Category::getTree();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'slug' => 'required|string|max:255|unique:categories,slug',
        //     'description' => 'nullable|string',
        //     'parent_id' => 'nullable|exists:categories,id',
        // ]);

        // $category = Category::create($data);

        // return response()->json($category, 201);
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
        ]);

        $category = Category::createCategory($data);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // return $category->load('products', 'children');
        return $category->loadDetail();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'slug' => 'required|string|max:255|unique:categories,slug,'.$category->id,
        //     'description' => 'nullable|string',
        //     'parent_id' => 'nullable|exists:categories,id',
        // ]);

        // $category->update($data);

        // return response()->json($category);
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
        ]);

        $category->updateCategory($data);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}
