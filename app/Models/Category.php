<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

     /* =====================
     | BUSINESS LOGIC
     ===================== */

    /**
     * Get all categories with children
     */
    public static function getTree()
    {
        return self::with('children')->get();
    }

    /**
     * Create new category
     */
    public static function createCategory(array $data): self
    {
        return self::create($data);
    }

    /**
     * Update category
     */
    public function updateCategory(array $data): bool
    {
        return $this->update($data);
    }

    /**
     * Get category detail
     */
    public function loadDetail(): self
    {
        return $this->load(['products', 'children']);
    }
}
