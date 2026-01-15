<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientStockException;
use Exception;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'total', 'shipping_address', 'phone', 'payment_method',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
     /* ================= BUSINESS LOGIC ================= */

    /**
     * Place order + deduct from stock + remove cart items
     */
    public static function placeOrder(
        int $userId,
        string $phone,
        string $shippingAddress,
        string $paymentMethod
    ): self {
        return DB::transaction(function () use (
            $userId,
            $phone,
            $shippingAddress,
            $paymentMethod
        ) {

            $cart = Cart::where('user_id', $userId)
                ->with('items')
                ->lockForUpdate()
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                throw new Exception('Shopping cart is empty.');
            }

            $total = 0;
            $insufficientProducts = [];
            $products = [];

            // Check stock
            foreach ($cart->items as $cartItem) {


                $product = Product::where('id', $cartItem->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $cartItem->quantity) {
                    $insufficientProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'requested' => $cartItem->quantity,
                    'available' => $product->stock,
                    ];
                }

                $total += $cartItem->quantity * $product->price;
                $products[$product->id] = $product;
            }

            if (!empty($insufficientProducts)) {
                throw new InsufficientStockException($insufficientProducts);
            }

            // Create order
            $order = self::create([
                'user_id' => $userId,
                'status' => 'pending',
                'total' => $total,
                'shipping_address' => $shippingAddress,
                'phone' => $phone,
                'payment_method' => $paymentMethod,
            ]);

            // Create order_items + subtract stock
            foreach ($cart->items as $cartItem) {

                $product = Product::where('id', $cartItem->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                ]);

                // Except for inventory
                $product->decrement('stock', $cartItem->quantity);
            }

            // Delete cart items
            $cart->items()->delete();

            return $order;
        });
    }
}
