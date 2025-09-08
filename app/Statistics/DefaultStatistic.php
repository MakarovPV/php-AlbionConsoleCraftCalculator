<?php

namespace App\Statistics;


use App\DTO\Statistics\StatisticBuildDTO;
use App\Statistics\TextBlocks\DefaultStatisticTextBlock;

class DefaultStatistic extends Statistic
{
    public function __construct(array $cityNames = [])
    {
        parent::__construct($cityNames);

        $this->prev = new ShortStatistic();
        $this->textBlock = new DefaultStatisticTextBlock();
    }

    /**
     * Получение данных по основному предмету и вывод их в консоль.
     * @param array $namesOfMainItem
     * @return void
     */
    public function build(StatisticBuildDTO $data): string
    {
        $dataForCountOfItems = [
            'rusName' => $data->namesOfMainItem['rusName'],
            'amountItems' => $data->amountItemsPerIteration,
        ];
        $this->textBlock->countOfItems($dataForCountOfItems);

        foreach ($this->cityNames as $city) {

            $this->textBlock->addText($this->getDataFromPrev($city, $data));

            $dataForItemCost = [
                'baseCost' => $this->getMainItemCost(),
                'city' => $city
            ];
            $this->textBlock->totalCost($dataForItemCost);
        }

        return $this->textBlock->getText();
    }
}