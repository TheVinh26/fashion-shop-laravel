<?php

namespace App\Services;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    /**
     * Create a new class instance.
     */
    protected $client;
    
    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->build();        
    }

     public function indexProduct(array $data)
    {
        return $this->client->index([
            'index' => 'products',
            'id'    => $data['id'],
            'body'  => $data,
        ]);
    }

    public function client()
    {
        return $this->client;
    }

    public function search(string $keyword, int $size = 200): array
    {
        $response = $this->client->search([
            'index' => 'products',
            'size' => $size,
            '_source' => ['id'],
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'multi_match' => [
                                    'query' => $keyword,
                                    'fields' => ['name^3', 'description'],
                                ],
                            ],
                        ],
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
    public function createProductIndex()
    {
        // Delete the old index if it exists (to avoid errors).
        if ($this->client->indices()->exists(['index' => 'products'])->asBool()) {
            $this->client->indices()->delete(['index' => 'products']);
        }

        $params = [
            'index' => 'products',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'name' => ['type' => 'text'],
                        'slug' => ['type' => 'keyword'],
                        'description' => ['type' => 'text'],
                        'price' => ['type' => 'float'],
                        'is_active' => ['type' => 'boolean'],
                        'created_at' => [
                            'type' => 'date',
                            'format' => 'yyyy-MM-dd HH:mm:ss||strict_date_optional_time'
                        ],
                    ]
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }
}
