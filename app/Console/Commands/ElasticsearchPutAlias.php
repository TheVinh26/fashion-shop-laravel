<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticsearchService;

class ElasticsearchPutAlias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:put-alias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach alias products to products_v1 index';

    /**
     * Execute the console command.
     */
    public function handle(ElasticsearchService $es)
    {
        $es->client()->indices()->putAlias([
            'index' => 'products_v1',
            'name'  => 'products',
        ]);

        $this->info('Alias products → products_v1 created successfully');
    }
}
