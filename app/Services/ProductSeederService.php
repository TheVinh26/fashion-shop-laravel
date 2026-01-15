<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\ProductCodeService;
use Symfony\Component\Console\Helper\ProgressBar;


class ProductSeederService
{
    public function __construct( 
        protected ProductCodeService $codeService
    ) {}
    
    public function seed(
        int $total = 1_000_000,
        int $batchSize = 1000,
        int $categoryId = 1,
        ?callable $progress = null
    ): void {
        DB::disableQueryLog();

        // $counter = DB::table('products')->count() + 1;;
        $counter = (DB::table('products')->max('id') ?? 0) + 1;

        echo "Start inserting";

        for ($i = 1; $i <= $total; $i += $batchSize) {

            $data = [];

            for ($j = 0; $j < $batchSize && ($i + $j) <= $total; $j++) {

                $name = "Product {$counter}";
                $productCode = $this->codeService->generate($name, $counter);

                $data[] = [
                    'product_code' => $productCode,
                    'name' => $name,
                    'slug' => \Str::slug($name) . '-' . $counter,
                    'description' => 'Auto generated product',
                    'price' => rand(10000, 500000),
                    'stock' => rand(1, 100),
                    'is_active' => 1,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $counter++;
            }

            DB::table('products')->insert($data);
            if ($progress) {
                $progress(count($data));
            }
        }
        echo "Done";
    }
}

// run tinker: app(\App\Services\ProductSeederService::class)->seed(1000000);