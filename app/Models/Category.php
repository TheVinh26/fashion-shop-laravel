<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
     | VALIDATION
     ===================== */

    protected static function validator(array $data, ?int $id = null)
    {
        return Validator::make($data, [
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
        ]);
    }
     /* =====================
     | BUSINESS LOGIC
     ===================== */

    public static function getTree()
    {
        return self::with('children')->get();
    }

    public static function createSafe(array $data): self
    {
        $validator = self::validator($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return self::create($validator->validated());
    }

    public function updateSafe(array $data): bool
    {
        $validator = self::validator($data, $this->id);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->update($validator->validated());
    }

    public function safeDelete(): void
    {
        if ($this->children()->exists()) {
            abort(400, 'Cannot delete category with children');
        }

        $this->delete();
    }

    /**
     * Get category detail 
     */
    public function loadDetail(): self
    {
        return $this->load(['products', 'children']);
    }
}
