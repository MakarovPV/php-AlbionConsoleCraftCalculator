<?php

namespace Database\ElasticSearch;

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
     * @param array $cityName
     * @return array|null
     */
    public function search(array $cityName): array|null
    {
        $params = [
            'index' => $this->getIndex(),
            'body'  => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'query' => $cityName[0],
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

        return null;
    }
}