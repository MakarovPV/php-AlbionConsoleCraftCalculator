<?php

namespace Database\ElasticSearch;

use App\DTO\DTO;
use Elastic\Elasticsearch\ClientBuilder;

/**
 * Базовый класс для подключения и работы с ElasticSearch.
 */
abstract class Elastic
{
    protected $client;
    private string $indexName;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();
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
     * Создание индекса в эластике.
     * @return \Elastic\Elasticsearch\Response\Elasticsearch|\Http\Promise\Promise|null
     */
    public function createIndex()
    {
        $params = [
            'index' => $this->getIndex(),
        ];

        try {
            return $this->client->indices()->create($params);
        } catch(\Exception $e) {
            echo 'Ошибка';
            return null;
        }
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
     * Добавление одного документа в индекс по данным из json-файла.
     * @param array $array
     * @return void
     */
    abstract public function insert(array $array);


    /**
     * Поиск документа в индексе.
     * @param DTO $params
     * @return array
     */
    abstract public function search(DTO $params): array;
}