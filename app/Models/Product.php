<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        ->with(['category', 'mainImage'])
        ->active();

        /**
         * SEARCH USING ELASTICSEARCH
         */
        if ($request->filled('search')) {
            $ids = app(ElasticsearchService::class)
                ->search($request->search, 500);

            if (empty($ids)) {
                return self::whereRaw('1=0')->paginate(8);
            }

            $query->whereIn('id', $ids)
                ->orderByRaw('FIELD(id,' . implode(',', $ids) . ')');
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
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate(8)->withQueryString();
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

}
