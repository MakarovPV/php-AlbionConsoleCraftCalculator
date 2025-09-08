<?php

namespace App\Statistics;

use App\DTO\Statistics\StatisticBuildDTO;
use App\Statistics\TextBlocks\ShortStatisticTextBlock;

class ShortStatistic extends Statistic
{
    public function __construct(array $cityNames = [])
    {
        parent::__construct($cityNames);

        $this->textBlock = new ShortStatisticTextBlock();
    }

    /**
     * @inheritDoc
     */
    public function build(StatisticBuildDTO $data): string
    {
        foreach ($this->cityNames as $city){
            $this->mainItemCost = $this->calculate($data->countOfIteration, $data->amountItemsPerIteration, $this->getItemCostFromApi($data->namesOfMainItem['uniqueName'], $city));

            $data = [
                'amountItemsPerIteration' => $data->amountItemsPerIteration,
                'rusName' => $data->namesOfMainItem['rusName'],
                'city' => $city,
                'mainItemCost' => $this->mainItemCost,
            ];

             $this->textBlock->totalCost($data);
        }
        return $this->textBlock->getText();
    }
}