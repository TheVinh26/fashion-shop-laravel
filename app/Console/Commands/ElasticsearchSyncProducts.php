<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\ElasticsearchService;
class ElasticsearchSyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:elasticsearch-sync-products';
    protected $signature = 'elasticsearch:sync-products {--from-id=0}';

    /** 
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Bulk sync products from MySQL to Elasticsearch';

    /**
     * Execute the console command.
     */
    public function handle(ElasticsearchService $es)
    {
        $indexName = config('elasticsearch.product_index', 'products_v1');
        // $indexName = 'products_v1';
        $fromId = (int) $this->option('from-id');
        $chunkSize = 2000;

        $total = Product::where('id', '>', $fromId)->count();

        $this->info("Syncing {$total} products to Elasticsearch...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        Product::where('id', '>', $fromId)
            ->orderBy('id')
            ->chunkById($chunkSize, function ($products) use ($es, $indexName, $bar) {

                $params = ['body' => []];

                foreach ($products as $product) {
                    $params['body'][] = [
                        'index' => [
                            '_index' => $indexName,
                            '_id'    => $product->id,
                        ]
                    ];

                    $params['body'][] = [
                        'id'           => $product->id,
                        'product_code' => $product->product_code,
                        'name'         => $product->name,
                        'slug'         => $product->slug,
                        'description'  => $product->description,
                        'price'        => (float) $product->price,
                        'category_id'  => $product->category_id,
                        'is_active'    => (bool) $product->is_active,
                        'created_at' => $product->created_at->toISOString(),
                        'updated_at' => optional($product->updated_at)->toISOString(),

                    ];
                }

                $response = $es->client()->bulk($params);
                $responseArray = $response->asArray();

                if (!empty($responseArray['errors'])) {
                    logger()->error('Bulk sync error', [
                        'items' => $responseArray['items'] ?? [],
                    ]);
                    throw new \Exception('Bulk sync failed');
                }

                $bar->advance(count($products));
            });

        $bar->finish();
        $this->newLine();
        $this->info('Elasticsearch bulk sync completed successfully.');
    }

}
