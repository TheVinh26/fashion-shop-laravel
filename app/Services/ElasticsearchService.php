<?php

namespace App\Services;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    /**
     * Create a new class instance.
     */
    protected $client;
    protected string $alias = 'products';

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->build();
    }

    public function client()
    {
        return $this->client;
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function search(string $keyword, int $size = 200): array
    {
        $response = $this->client->search([
            'index' => $this->alias(),
            'size' => $size,
            '_source' => ['id'],
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'term' => [
                                    'product_code' => [
                                        'value' => $keyword,
                                        'boost' => 5
                                    ]
                                ]
                            ],
                            [
                                'match' => [
                                    'name' => [
                                        'query' => $keyword,
                                        'boost' => 3
                                    ]
                                ]
                            ],
                            [
                                'match' => [
                                    'description' => [
                                        'query' => $keyword
                                    ]
                                ]
                            ],
                        ],
                        'minimum_should_match' => 1,
                        'filter' => [
                            ['term' => ['is_active' => true]],
                        ],
                    ],
                ],
            ],
        ]);

        return collect($response['hits']['hits'])
            ->pluck('_source.id')
            ->toArray();
    }


    public function createProductIndex(string $indexName)
    {
        if ($this->client->indices()->exists(['index' => $indexName])->asBool()) {
            return;
        }

        $this->client->indices()->create([
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 1,
                    'analysis' => [
                        'analyzer' => [
                            'vi_analyzer' => [
                                'tokenizer' => 'standard',
                                'filter' => ['lowercase'],
                            ],
                        ],
                    ],
                ],
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'long'],
                        'product_code' => ['type' => 'keyword'],

                        'name' => [
                            'type' => 'text',
                            'analyzer' => 'vi_analyzer',
                            'fields' => [
                                'keyword' => ['type' => 'keyword'],
                            ],
                        ],

                        'description' => [
                            'type' => 'text',
                            'analyzer' => 'vi_analyzer',
                        ],

                        'slug' => ['type' => 'keyword'],
                        'price' => ['type' => 'float'],
                        'category_id' => ['type' => 'long'],
                        'is_active' => ['type' => 'boolean'],
                        'created_at' => ['type' => 'date'],
                        'updated_at' => ['type' => 'date'],
                    ],
                ],
            ],
        ]);
    }


}
