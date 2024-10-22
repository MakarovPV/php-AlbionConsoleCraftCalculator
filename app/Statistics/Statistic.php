<?php

namespace App\Statistics;

use App\Api\AlbionResourceCost;
use Database\ElasticSearch\Items;

abstract class Statistic
{
    protected array $itemsArray;
    protected string $cityName;
    private AlbionResourceCost $api;
    private Items $elastic;
    protected int $mainItemCost = 0;

    public function __construct(array $itemsArray, string $cityName)
    {
        $this->api = new AlbionResourceCost();
        $this->elastic = new Items();
        $this->itemsArray = $itemsArray;
        $this->cityName = $cityName;
    }

    abstract public function build();

    protected function getItemCostFromApi(string $uniqueName, string $cityName = 'Thetford')
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