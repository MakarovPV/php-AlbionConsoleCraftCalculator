<?php

namespace App;

use App\Statistics\Statistic;
use App\XML\XMLData;
use App\XML\XMLDataItemList;
use Database\ElasticSearch\Items;

class Craft
{
    private string $countOfItems;
    private array $itemNamesFromInput;
    private array $itemNamesFromElastic;
    private string $itemTier;
    private string $statisticType;
    private string $cityName;
    private Statistic $statistic;

    public function __construct(array $arrayFromConsole)
    {
        $parameters = new Preparation($arrayFromConsole);

        $this->countOfItems = $parameters->countOfItems;
        $this->itemNamesFromInput = $parameters->itemNamesFromInput;
        $this->itemTier = $parameters->itemTier;
        $this->statisticType = $parameters->statisticType;
        $this->cityName = $parameters->cityName;
    }

    public function craft()
    {
        return $this->getStatistic();
    }

    private function getStatistic()
    {
        $this->statistic = new $this->statisticType($this->getGeneratedItems(new XMLDataItemList()), $this->cityName);
        $this->statistic->dataOfMainItem($this->itemNamesFromElastic);
        return $this->statistic->build();
    }

    private function getGeneratedItems(XMLData $items)
    {
        $this->itemNamesFromElastic = $this->getItemNamesFromElastic(new Items());
        $items->generateItems($this->itemNamesFromElastic['uniqueName'], $this->countOfItems);
        return $items->getGeneratedData();
    }

    private function getItemNamesFromElastic(Items $albionItems)
    {
        $result = $albionItems->search(
            [
                'itemNames' => $this->itemNamesFromInput,
                'tier' => $this->itemTier
            ]);

        return $result;
    }
}