<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    protected static function validateData(array $data): array
    {
        $validator = Validator::make($data, [
            'order_id'   => ['required', 'exists:orders,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'price'      => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /* =====================
     | BUSINESS LOGIC
     ===================== */

    /**
     * Create order item safely
     */
    public static function createItem(array $data): self
    {
        $data = self::validateData($data);

        return self::create($data);
    }

    /**
     * Delete order item (hook for future logic)
     */
    public function deleteItem(): void
    {
        $this->delete();
    }
}
