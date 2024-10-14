<?php

namespace App;

use App\XML\XMLData;
use App\XML\XMLDataItemList;
use Database\ElasticSearch\Items;

class Craft
{
    private string $countOfItems;
    private array $itemUniqueNames;
    private string $tier;
    private string $statisticType;
    private string $cityName;

    public function __construct(array $arrayFromConsole)
    {
        $parameters = new Preparation($arrayFromConsole);

        $this->countOfItems = $parameters->countOfItems;
        $this->itemUniqueNames = $parameters->itemUniqueNames;
        $this->tier = $parameters->tier;
        $this->statisticType = $parameters->statisticType;
        $this->cityName = $parameters->cityName;
    }

    public function craft()
    {
        return $this->getStatistic();
    }

    private function getStatistic()
    {
        $statistic = new $this->statisticType($this->getGeneratedItems(new XMLDataItemList()), $this->cityName);
        return $statistic->build();
    }

    private function getGeneratedItems(XMLData $items)
    {
        $items->generateItems($this->getUniqueName(new Items()), $this->countOfItems);
        return $items->getGeneratedData();
    }

    private function getUniqueName(Items $albionItems)
    {
        return $albionItems->search(
            [
                'itemNames' => $this->itemUniqueNames,
                'tier' => $this->tier
            ])['uniqueName'];
    }
}