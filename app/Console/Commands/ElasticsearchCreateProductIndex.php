<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticsearchService;

class ElasticsearchCreateProductIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:elasticsearch-create-product-index';
    protected $signature = 'elasticsearch:create-product-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Elasticsearch index for products';

    /**
     * Execute the console command.
     */
    public function handle(ElasticsearchService $elasticsearch)
    {
        $elasticsearch->createProductIndex();
        $this->info('Elasticsearch product index created successfully');
    }
}
