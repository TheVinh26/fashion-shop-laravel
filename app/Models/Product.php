<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


use App\Services\ElasticsearchService;

class Product extends Model
{
    protected $fillable = [
        'product_code','name', 'slug', 'description', 'price', 'stock', 'is_active', 'category_id',
    ];

    protected $appends = ['main_image_url'];

    /* =====================
     | RELATIONSHIPS
     ===================== */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /* =====================
     | ACCESSORS
     ===================== */
    public function getMainImageUrlAttribute()
    {
        return $this->mainImage
            ? asset('storage/' . $this->mainImage->image_path)
            : asset('images/no-image.png');
    }

    /* =====================
    | VALIDATION
    ===================== */

    protected static function validateCreate(array $data, bool $isAdmin = false): array
    {
        $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ];

        if ($isAdmin) {
            $rules['images.*'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    protected static function validateUpdate(array $data, self $product): array
    {
        $validator = Validator::make($data, [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /* =====================
     | SCOPES (BUSINESS QUERY)
     ===================== */
     public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $keyword = null)
    {
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        return $query;
    }

    public function scopeCategoryFilter($query, $categoryId = null)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query;
    }

    public function scopeSortPrice($query, $sort = null)
    {
        return match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };
    }

    /* =====================
     | BUSINESS METHODS
     ===================== */

    public static function getFilteredProducts($request)
    {
        $query = self::query()
        // ->with(['category', 'mainImage'])
        ->with([
            'category:id,name,slug',
            'mainImage:id,product_id,image_path'
        ])
        ->active();

        /**
         * SEARCH USING ELASTICSEARCH
         */

        if ($request->filled('search')) {

            $ids = Cache::remember(
                'es_search_' . md5($request->search),
                now()->addMinutes(5),
                fn () => app(ElasticsearchService::class)
                        ->search($request->search, 200)
            );

            if (empty($ids)) {
                return self::whereRaw('1=0')->simplePaginate(8);
            }

            $query->whereIn('id', $ids);
        }


        /**
         * FILTER CATEGORY (MySQL)
         */
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        /**
         * SORT PRICE (MySQL)
         */
        match ($request->sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default      => $query->latest(),
        };

        // return $query->paginate(8)->withQueryString();
        return $query->simplePaginate(8)->withQueryString();
    }

    public static function getProductDetailBySlug(string $slug)
    {
        return self::with(['category', 'images', 'mainImage'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getRelatedProducts($limit = 5)
    {
        return self::with(['category', 'mainImage'])
            ->active()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->limit($limit)
            ->get();
    }

    //Admin
    public static function createProductByAdmin(array $data): self
    {
        $data = self::validateCreate($data, true);

        return DB::transaction(function () use ($data) {

            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

            $product = self::create([
                'name'        => $data['name'],
                'slug'        => $data['slug'],
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'category_id' => $data['category_id'],
                'is_active'   => isset($data['is_active']),
            ]);

            if (!empty($data['images'])) {
                foreach ($data['images'] as $index => $image) {

                    $fileName = $index === 0
                        ? 'main.' . $image->extension()
                        : Str::uuid() . '.' . $image->extension();

                    $path = $image->storeAs(
                        "products/{$product->id}",
                        $fileName,
                        'public'
                    );

                    $product->images()->create([
                        'image_path' => $path,
                        'is_main'    => $index === 0,
                    ]);
                }
            }

            return $product;
        });
    }


}
