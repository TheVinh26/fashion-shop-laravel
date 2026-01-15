<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ElasticsearchSyncProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:elasticsearch-sync-products';
    protected $signature = 'elasticsearch:sync-products 
                            {--from-id=0 : Resume from product ID}';

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
        $fromId = (int) $this->option('from-id');
        $chunkSize = 1000;

        $total = Product::where('id', '>', $fromId)->count();

        if ($total === 0) {
            $this->info('No products to sync.');
            return;
        }

        $this->info("Syncing {$total} products to Elasticsearch...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        Product::where('id', '>', $fromId)
            ->orderBy('id')
            ->chunkById($chunkSize, function ($products) use ($es, $bar) {

                $params = ['body' => []];

                foreach ($products as $product) {
                    $params['body'][] = [
                        'index' => [
                            '_index' => 'products',
                            '_id'    => $product->id,
                        ]
                    ];

                    $params['body'][] = [
                        'id'          => $product->id,
                        'name'        => $product->name,
                        'slug'        => $product->slug,
                        'description' => $product->description,
                        'price'       => (float) $product->price,
                        'category_id' => $product->category_id,
                        'is_active'   => (bool) $product->is_active,
                        'created_at'  => $product->created_at->format('Y-m-d H:i:s'),
                    ];
                }

                $es->client()->bulk($params);

                $bar->advance(count($products));
            });

        $bar->finish();
        $this->newLine();
        $this->info('Elasticsearch sync completed.');
    }
}
