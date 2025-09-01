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
            'id' => $array['Index'],
            'body' => [
                'uniqueName' => $array['UniqueName'],
                'localizedNames' => [
                    'EN-US' => $engName,
                    'RU-RUS' => $russianName
                ]
            ]
        ]);
    }

    /**
     * @param string $uniqueName
     * @return mixed
     */
    public function getRusItemName(string $uniqueName)
    {
        $tier = substr($uniqueName, 1, 1);
        $enchant = is_numeric($uniqueName[-1]) ? substr($uniqueName, -1, 1) : 0;
        return $this->search(
            [
                'itemNames' => [$uniqueName],
                'tier' => $tier,
                'enchant' => $enchant
            ])['rusName'];
    }

    /**
     * Поиск предмета в эластике. Входящий массив содержит в себе название искомого предмета из 1 или нескольких слов и его уровень.
     * При нахождении получаем массив с его наименованием на русском языке и уникальным именем для получения по нему данных из API.
     * @param array $itemNamesAndTier
     * @return array|null
     */
    public function search(array $itemNamesAndTier): array|null
    {
        list('itemNames' => $itemNames, 'tier' => $tierItem, 'enchant' => $enchant) = $itemNamesAndTier;

        $params = [
            'index' => $this->getIndex(),
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],
                        'filter' => []
                    ]
                ]
            ]
        ];

        // Поиск по словам
        foreach ($itemNames as $itemName) {
            $params['body']['query']['bool']['must'][] = [
                'multi_match' => [
                    'query' => $itemName,
                    'fields' => [
                        'localizedNames.RU-RUS',
                        'uniqueName',
                        'localizedNames.EN-US'
                    ],
                ]
            ];
        }

        // Фильтр по тиру
        $params['body']['query']['bool']['filter'][] = [
            'prefix' => ['uniqueName.keyword' => 'T' . $tierItem . '_']
        ];

        // Фильтр по зачарованию
        if ($enchant > 0) {
            $params['body']['query']['bool']['filter'][] = [
                'wildcard' => ['uniqueName.keyword' => '*@' . $enchant]
            ];
        }

        $response = $this->client->search($params);

        foreach ($response['hits']['hits'] as $hit) {
            if (isset($hit['_source']['uniqueName'])) {
                $localizedName = $hit['_source']['localizedNames']["RU-RUS"] ?? '';
                $uniqueName = $hit['_source']['uniqueName'];

                return [
                    'uniqueName' => $uniqueName,
                    'rusName' => $localizedName
                ];
            }
        }

        return null;
    }
}