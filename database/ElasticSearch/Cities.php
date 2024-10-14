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

    public function search(array $cityName)
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