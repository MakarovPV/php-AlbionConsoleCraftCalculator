<?php

namespace App\Statistics;

use App\Api\AlbionResourceCost;
use App\DTO\Statistics\StatisticBuildDTO;
use App\ReturnPercents;
use App\Traits\Calculate;
use App\XML\XMLDataItemList;
use Database\ElasticSearch\Items;


abstract class Statistic
{
    use Calculate;

    protected array $cityNames;
    protected int $mainItemCost = 0;
    protected Statistic $prev;
    private XMLDataItemList $xml;
    private AlbionResourceCost $api;
    private Items $elasticItems;

    /**
     * Первым аргументом передаётся массив со сгенерированными данными из xml-файла.
     * @param array $itemsArray
     * @param string $cityName
     */
    public function __construct(array $cityNames = [])
    {
        $this->cityNames = $cityNames;
        $this->api = new AlbionResourceCost();
        $this->elasticItems = new Items();
        $this->xml = new XMLDataItemList();
    }

    /**
     * Генерация статистики по комплектующим предметам для вывода в консоль.
     * @param int $countOfIteration
     * @param int $amountItemsPerIteration
     * @param array $namesOfMainItem
     * @return string
     */
    abstract public function build(StatisticBuildDTO $data): string;

    protected function getItemCostFromApi(string $uniqueName, string $cityName)
    {
        return $this->api->getCostByItemName($uniqueName, $cityName);
    }

    protected function getRusNameFromElastic(string $uniqueName)
    {
        return $this->elasticItems->getRusItemName($uniqueName);
    }

    /**
     * Генерация данных по комплектующим предметам из xml-файла.
     * @param string $itemUniqueName
     * @param int $countOfItems
     * @return array
     */
    protected function getGeneratedItems(int $countOfItems, string $itemUniqueName): array
    {
        $this->xml->generateItems($itemUniqueName, $countOfItems);
        return $this->xml->getGeneratedData();
    }

    protected function getDataFromPrev(string $city, StatisticBuildDTO $dto): string
    {
        $this->prev->setCityName($city);
        return $this->prev->build($dto);
    }

    protected function setCityName(string $cityName)
    {
        $this->cityNames = [$cityName];
    }


}