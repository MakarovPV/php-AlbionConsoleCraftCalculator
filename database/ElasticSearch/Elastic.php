<?php

namespace Database\ElasticSearch;

use Elastic\Elasticsearch\ClientBuilder;

/**
 * Базовый класс для подключения и работы с ElasticSearch.
 */
abstract class Elastic
{
    public $client;
    private string $indexName;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(['localhost:9200'])->build();
    }

    protected function setIndex(string $indexName)
    {
        $this->indexName = $indexName;
    }

    protected function getIndex()
    {
        return $this->indexName;
    }

    /**
     * Заполнение индекса эластика документами. Используется в командах и выполняется при первом запуске проекта.
     * @param array $array
     * @return void
     */
    public function insertAllDocuments(array $array)
    {
        foreach ($array as $item) {
            $this->insert($item);
        }
    }

    /**
     * Добавление одного документа в индекс.
     * @param array $array
     * @return void
     */
    abstract public function insert(array $array);

    /**
     * Поиск документа в индексе.
     * @param array $params
     * @return array|null
     */
    abstract public function search(array $params): array|null;
}