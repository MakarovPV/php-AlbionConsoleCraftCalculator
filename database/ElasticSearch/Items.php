<?php

namespace Database\ElasticSearch;

class Items extends Elastic
{
    public function __construct()
    {
        parent::__construct();
        $this->setIndex('albion_items');
    }

    public function insert(array $array)
    {
        !empty($array['LocalizedNames']['RU-RU']) ? $russianName = $array['LocalizedNames']['RU-RU'] : $russianName = 'описание предмета отсутствует';
        !empty($array['LocalizedNames']['EN-US']) ? $engName = $array['LocalizedNames']['EN-US'] : $engName = 'описание предмета отсутствует';
        $this->client->index([
            'index' => $this->getIndex(),
            'id'    => $array['Index'],
            'body'  => [
                'uniqueName'  => $array['UniqueName'],
                'localizedNames' => [
                    'EN-US' => $engName,
                    'RU-RUS' => $russianName
                ]
            ]
        ]);
    }

    /**
     * Поиск предмета в эластике. Входящий массив содержит в себе название искомого предмета из 1 или нескольких слов и его уровень.
     * При нахождении получаем массив с его наименованием на русском языке и уникальным именем для получения по нему данных из API.
     * @param array $itemNamesAndTier
     * @return array|null
     */
    public function search(array $itemNamesAndTier): array|null
    {
        $itemNames = $itemNamesAndTier['itemNames'];
        $tierItem = $itemNamesAndTier['tier'];

        $params = [
            'index' => $this->getIndex(),
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'bool' => [
                                    'should' => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($itemNames as $itemName) {
            $params['body']['query']['bool']['should'][] = [
                'multi_match' => [
                    'query' => $itemName,
                    'fields' => [
                        'localizedNames.RU-RUS',
                        'uniqueName',
                        'localizedNames.EN-US'
                    ],
                    'type' => 'phrase'
                ]
            ];
        }

        $response = $this->client->search($params);

        foreach ($response['hits']['hits'] as $hit) {
            if (isset($hit['_source']['uniqueName'])) {
                $localizedName = $hit['_source']['localizedNames']["RU-RUS"];
                $uniqueName = $hit['_source']['uniqueName'];
                if ($uniqueName[1] === $tierItem) {
                    return [
                        'uniqueName' => $uniqueName,
                        'rusName' => $localizedName
                    ];
                }
            }
        }

        return null;
    }
}