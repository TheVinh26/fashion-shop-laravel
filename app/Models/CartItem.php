<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'size','quantity'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    public function changeQuantity(array $data): void
    {
        $validator = Validator::make($data, [
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->update([
            'quantity' => $data['quantity'],
        ]);
    }

    public function remove(): void
    {
        $this->delete();
    }

    public static function storeItem(array $data): self
    {
        $validator = Validator::make($data, [
            'cart_id'    => ['required', 'exists:carts,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return self::updateOrCreate(
            [
                'cart_id'    => $data['cart_id'],
                'product_id' => $data['product_id'],
            ],
            [
                'quantity' => $data['quantity'],
            ]
        );
    }
}
