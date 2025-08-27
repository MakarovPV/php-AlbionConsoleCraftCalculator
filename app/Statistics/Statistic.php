<?php

namespace App\Statistics;

use App\Api\AlbionResourceCost;
use App\Traits\Calculate;
use App\XML\XMLDataItemList;
use Database\ElasticSearch\Items;


abstract class Statistic
{
    use Calculate;

    protected string|array $cityName;
    private AlbionResourceCost $api;
    private Items $elastic;
    protected int $mainItemCost = 0;
    protected Statistic $prev;
    protected XMLDataItemList $xml;

    /**
     * Первым аргументом передаётся массив со сгенерированными данными из xml-файла.
     * @param array $itemsArray
     * @param string $cityName
     */
    public function __construct(array $cityName = [])
    {
        $this->api = new AlbionResourceCost();
        $this->elastic = new Items();
        $this->xml = new XMLDataItemList();
    }

    /**
     * Генерация статистики по комплектующим предметам для вывода в консоль.
     * @param int $countOfIteration
     * @param int $amountItemsPerIteration
     * @param array $namesOfMainItem
     * @return string
     */
    abstract public function build(int $countOfIteration, int $amountItemsPerIteration, array $namesOfMainItem): string;

    protected function getItemCostFromApi(string $uniqueName, string $cityName)
    {
        return $this->api->getCostByItemName($uniqueName, $cityName);
    }

    protected function getRusNameFromElastic(string $uniqueName)
    {
        $tier = substr($uniqueName, 1, 1);
        return $this->elastic->search(
            [
                'itemNames' => [$uniqueName],
                'tier' => $tier
            ])['rusName'];
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

    protected function setCityName(string $cityName)
    {
        $this->cityName = [$cityName];
    }
}