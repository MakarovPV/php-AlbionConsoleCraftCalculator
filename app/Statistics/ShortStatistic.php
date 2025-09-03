<?php

namespace App\Statistics;

use App\DTO\Statistics\StatisticBuildDTO;

class ShortStatistic extends Statistic
{
    public function __construct(array $cityNames = [])
    {
        parent::__construct($cityNames);
    }

    /**
     * @inheritDoc
     */
    public function build(StatisticBuildDTO $data): string
    {
        $str = '';
        foreach ($this->cityNames as $city){
            $this->mainItemCost = $this->calculate($data->countOfIteration, $data->amountItemsPerIteration, $this->getItemCostFromApi($data->namesOfMainItem['uniqueName'], $city));
            $str .= 'Стоимость ' . $data->amountItemsPerIteration . ' предметов ' . $data->namesOfMainItem['rusName'] . ' в городе ' . $city . ' составляет ' . $this->mainItemCost . " серебра.\n";

        }
         return $str;
    }
}