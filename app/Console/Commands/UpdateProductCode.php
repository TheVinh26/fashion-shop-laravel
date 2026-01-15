<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\ProductCodeService;
use Illuminate\Support\Facades\DB;

class UpdateProductCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:update-product-code';
    protected $signature = 'products:update-code';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Update product_code for existing products';

    /**
     * Execute the console command.
     */
    public function __construct(ProductCodeService $service)
    {
        parent::__construct();
        $this->service = $service;
    }
    
    public function handle(ProductCodeService $service): int
    {
        $this->info('Start updating product_code');

        $counter = Product::whereNotNull('product_code')
            ->where('product_code', '!=', '')
            ->count() + 1;

        Product::where(function ($q) {
                $q->whereNull('product_code')
                ->orWhere('product_code', '');
            })
            ->orderBy('id')
            ->chunkById(1000, function ($products) use (&$counter, $service) {

                DB::transaction(function () use ($products, &$counter, $service) {

                    foreach ($products as $product) {
                        Product::where('id', $product->id)->update([
                            'product_code' => $service->generate(
                                $product->name,
                                $counter
                            ),
                        ]);

                        $counter++;
                    }

                });

                // Log to see if it's running
                $this->line("Updated up to product ID: {$products->last()->id}");

            });

        $this->info('Done!');
        return Command::SUCCESS;
    }
}
