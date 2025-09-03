<?php

namespace Database\ElasticSearch;

use App\DTO\Cities\CitySearchDTO;
use App\DTO\DTO;
use App\Exceptions\City\CityWrongNameException;

class Cities extends Elastic
{
    public function __construct()
    {
        parent::__construct();
        $this->setIndex('albion_cities');
    }

    public function insert(array $array)
    {
        $this->client->index([
            'index' => $this->getIndex(),
            'id'    => $array['Index'],
            'body'  => [
                'cityName' => [
                    'EN-US' => $array['CityName']['EN-US'],
                    'RU-RUS' => $array['CityName']['RU-RU']
                ]
            ]
        ]);
    }


    /**
     * Поиск города в эластике. Во входящем массиве содержится либо сокращенное русское название города, либо уже полное английское.
     * При нахождении получаем массив с его наименованием на русском и английском языках.
     * Английское название используется для получения по нему данных из API.
     * @param CitySearchDTO $city
     * @return array|null
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function search(DTO $city): array
    {
        if (!$city instanceof CitySearchDTO) {
            throw new \InvalidArgumentException();
        }

        $params = [
            'index' => $this->getIndex(),
            'body'  => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'query' => $city->cityName,
                                    'fields' => [
                                        'cityName.EN-US',
                                        'cityName.RU-RUS'
                                    ],
                                    'type' => 'phrase'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->client->search($params);

        foreach ($response['hits']['hits'] as $hit) {
            if (isset($hit['_source']['cityName'])) {
                $cityNames = $hit['_source']['cityName'];
                return [
                    'rusName' => $cityNames["RU-RUS"],
                    'enName' => $cityNames["EN-US"]
                ];
            }
        }

        throw new CityWrongNameException($city->cityName);
    }

    /**
     * @param string $cityName
     * @return array
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function getEngCityNames(string $cityName): array
    {
        if($cityName == 'all'){
            return $this->getAllCitiesEngNames();
        }

        $result = $this->search(new CitySearchDTO($cityName));

        return [$result['enName']];
    }

    /**
     * Получение всех названий городов на английском.
     * @return array
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function getAllCitiesEngNames(): array
    {
        $params = [
            'index' => $this->getIndex(),
            'body'  => [
                '_source' => ['cityName.EN-US'],
                'query' => [
                    'match_all' => (object)[]
                ]
            ]
        ];

        $response = $this->client->search($params);

        $cities = [];
        foreach ($response['hits']['hits'] as $hit) {
            if (isset($hit['_source']['cityName']['EN-US'])) {
                $cities[] = $hit['_source']['cityName']['EN-US'];
            }
        }

        return $cities;
    }
}