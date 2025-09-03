<?php

namespace Database\ElasticSearch;

use App\DTO\DTO;
use App\DTO\Items\ItemSearchDTO;
use App\Exceptions\Item\ItemWrongNameException;

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
        return $this->search(new ItemSearchDTO($uniqueName, $tier, $enchant))['rusName'];
    }


    /**
     * Поиск предмета в эластике. Входящий массив содержит в себе название искомого предмета из 1 или нескольких слов и его уровень.
     * При нахождении получаем массив с его наименованием на русском языке и уникальным именем для получения по нему данных из API.
     * @param ItemSearchDTO $itemData
     * @return array|null
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function search(DTO $itemData): array
    {
        if (!$itemData instanceof ItemSearchDTO) throw new \InvalidArgumentException();

        $itemNames = $itemData->itemNames;
        $tier = $itemData->tier;
        $enchant = $itemData->enchant;

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
            'prefix' => ['uniqueName.keyword' => 'T' . $tier . '_']
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

        throw new ItemWrongNameException();
    }
}