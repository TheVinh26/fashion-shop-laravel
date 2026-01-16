<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    public static function getUserCart()
    {
        return self::with([
            'items.product.mainImage',
            'items.product.category',
        ])->where('user_id', Auth::id())->first();
    }

    public static function getOrCreateForUser(): self
    {
        return self::firstOrCreate([
            'user_id' => Auth::id(),
        ]);
    }

    public static function addProductForCurrentUser(Product $product, array $data): void
    {
        $validator = Validator::make($data, [
            'quantity' => ['required', 'integer', 'min:1'],
            'size'     => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $cart = self::getOrCreateForUser();

        $cart->addProduct(
            $product,
            $data['size'],
            $data['quantity']
        );
    }

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

    public function subtotal(): float
    {
        return $this->items->sum(fn ($item) =>
            $item->product->price * $item->quantity
        );
    }

    public static function clearForCurrentUser(): void
    {
        $cart = self::getOrCreateForUser();
        $cart->items()->delete();
    }
}
