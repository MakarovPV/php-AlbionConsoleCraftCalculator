<?php

namespace App\Statistics;

use App\DTO\Statistics\StatisticBuildDTO;
use App\Statistics\TextBlocks\FullStatisticTextBlock;

class FullStatistic extends Statistic
{
    public function __construct(array $cityNames = [])
    {
        parent::__construct($cityNames);

        $this->prev = new DefaultStatistic();
        $this->textBlock = new FullStatisticTextBlock();
    }

    /**
     * @inheritDoc
     */
    public function build(StatisticBuildDTO $data): string
    {
        foreach ($this->cityNames as $city) {
            $this->textBlock->addText($this->getDataFromPrev($city, $data));
            $itemsArray = $this->getGeneratedItems($data->countOfIteration, $data->namesOfMainItem['uniqueName']);

            $this->textBlock->addText("\nПоиск данных по ресурсам для его создания:\n");

            $totalCost = 0;
            foreach ($itemsArray as $uniqueName => $count){
                $costItem = $this->calculate($data->countOfIteration, $count, $this->getItemCostFromApi($uniqueName, $city));
                $totalCost += $costItem;

                $dataForCountOfItems = [
                    'uniqueName' => $this->getRusNameFromElastic($uniqueName),
                    'countOfIteration' => $data->countOfIteration * $count,
                    'costItem' => $costItem
                ];

                $this->textBlock->countOfItems($dataForCountOfItems);
            }

            $dataForTotalCost = [
                'totalCost' => $totalCost,
                'mainItemCost' => $this->getMainItemCost(),
                'countOfIteration' => $data->countOfIteration
            ];

            $this->textBlock->addText("\n");
            $this->textBlock->totalCost($dataForTotalCost);
       }
       $this->textBlock->addStringDelimiter();

       return $this->textBlock->getText();
    }
}