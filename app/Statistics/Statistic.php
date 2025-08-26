<?php

namespace App\Statistics;

use App\Api\AlbionResourceCost;
use App\Traits\Calculate;
use Database\ElasticSearch\Items;


abstract class Statistic
{
    use Calculate;

    protected array $itemsArray;
    protected string $cityName;
    private AlbionResourceCost $api;
    private Items $elastic;
    protected int $mainItemCost = 0;
    protected Statistic $prev;

    /**
     * Первым аргументом передаётся массив со сгенерированными данными из xml-файла.
     * @param array $itemsArray
     * @param string $cityName
     */
    public function __construct()
    {
        $this->api = new AlbionResourceCost();
        $this->elastic = new Items();
    }

    /**
     * Генерация статистики по комплектующим предметам для вывода в консоль.
     * @return string
     */
    abstract public function build(int $amountItems, array $namesOfMainItem): string;

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
}