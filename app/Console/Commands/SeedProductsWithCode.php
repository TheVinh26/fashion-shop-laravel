<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\ProductCodeService;

class SeedProductsWithCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:seed-products-with-code';
    protected $signature = 'products:seed-with-code {total=1000000}';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Seed products with product_code in one run';


    /**
     * Execute the console command.
     */
    public function handle(ProductCodeService $service): int
    {
        DB::disableQueryLog();

        $total = (int) $this->argument('total');
        $batchSize = 1000;
        $categoryId = 1;

        // $counter = 1;
        $counter = (DB::table('products')->max('id') ?? 0) + 1;

        $this->info("Start inserting {$total} products");

        for ($i = 1; $i <= $total; $i += $batchSize) {

            $data = [];

            for ($j = 0; $j < $batchSize && ($i + $j) <= $total; $j++) {

                $index = $i + $j;
                $name = "Product {$index}";

                $data[] = [
                    'product_code' => $service->generate($name, $counter),
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . $counter,
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
            $this->line("Inserted {$counter} products");
        }

        $this->info('Done!');
        return Command::SUCCESS;
    }
}
