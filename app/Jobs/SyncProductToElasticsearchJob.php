<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\ElasticsearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProductToElasticsearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $productId;
    protected bool $delete;

    /**
     * Create a new job instance.   
     */
    public function __construct(int $productId, bool $delete = false)
    {
        $this->productId = $productId;
        $this->delete = $delete; 
    }

    /**
     * Execute the job.
     */
    public function handle(ElasticsearchService $es): void
    {
        if ($this->delete) {
            $es->client()->delete([
                'index' => 'products',
                'id'    => $this->productId,
            ]);
            return;
        }

        $product = Product::find($this->productId);
        if (!$product) return;

        $es->client()->index([
            'index' => 'products', // alias
            'id'    => $product->id,
            'body'  => [
                'id'           => $product->id,
                'product_code' => $product->product_code,
                'name'         => $product->name,
                'description'  => $product->description,
                'price'        => (float) $product->price,
                'is_active'    => (bool) $product->is_active,
                'category_id'  => $product->category_id,
                'created_at'   => $product->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
