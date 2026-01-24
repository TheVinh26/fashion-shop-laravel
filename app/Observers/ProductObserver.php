<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductCodeService;
use App\Jobs\SyncProductToElasticsearchJob;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function creating(Product $product): void
    {
        if (!$product->product_code) {
            $number = Product::count() + 1;

            $product->product_code = app(ProductCodeService::class)
                ->generate($product->name, $number);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
    public function saved(Product $product)
    {
        dispatch(new SyncProductToElasticsearchJob($product->id));
    }
}
