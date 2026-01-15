<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    protected $fillable = ['user_id'];
    /* =====================
     | RELATIONSHIPS
     ===================== */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /* =====================
     | BUSINESS LOGIC
     ===================== */

    /**
     * Get current user's cart with full relations
     */
    public static function getUserCart()
    {
        return self::with([
            'items.product.mainImage',
            'items.product.category',
        ])->where('user_id', Auth::id())->first();
    }

    /**
     * Get or create cart for current user
     */
    public static function getOrCreateForUser(): self
    {
        return self::firstOrCreate([
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Add product to cart
     */
    public function addProduct(Product $product, string $size, int $quantity): void
    {
        $item = $this->items()
            ->where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $this->items()->create([
                'product_id' => $product->id,
                'size'       => $size,
                'quantity'   => $quantity,
            ]);
        }
    }

    

    /**
     * Calculate cart subtotal
     */
    public function subtotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Clear all items in cart
     */
    public function clear(): void
    {
        $this->items()->delete();
    }
}
