<?php

namespace Database\ElasticSearch;

use Elastic\Elasticsearch\ClientBuilder;

abstract class Elastic
{
    public $client;
    private string $indexName;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(['localhost:9200'])->build();
    }

    protected function setIndex(string $indexName){
        $this->indexName = $indexName;
    }

    protected function getIndex(){
        return $this->indexName;
    }

    public function insertAllDocuments(array $array)
    {
        foreach ($array as $item) {
            $this->insert($item);
        }
    }

    abstract public function insert(array $array);

    abstract public function search(array $params);
}