<?php

namespace App\Statistics;


use App\DTO\Statistics\StatisticBuildDTO;

class DefaultStatistic extends Statistic
{
    public function __construct(array $cityNames = [])
    {
        parent::__construct($cityNames);
        $this->prev = new ShortStatistic();
    }

    /**
     * Получение данных по основному предмету и вывод их в консоль.
     * @param array $namesOfMainItem
     * @return void
     */
    public function build(StatisticBuildDTO $data): string
    {
        $str = 'Предмет ' . $data->namesOfMainItem['rusName'] . ' крафтится в количестве ' . $data->amountItemsPerIteration . ' единиц.' . "\n\n";

        foreach ($this->cityNames as $city) {
            $str .= $this->getDataFromPrev($city, $data);
            $str .= 'Стоимость с 4% налогом за ' . $data->amountItemsPerIteration . ' предметов в городе ' . $city . ': ' . $this->calculateCostWithReturn($this->prev->mainItemCost, 4) . " серебра.\n" .
                'Стоимость с 6.5% налогом за ' . $data->amountItemsPerIteration . ' предметов в городе ' . $city . ': ' . $this->calculateCostWithReturn($this->prev->mainItemCost, 6.5) . " серебра.\n\n";
        }
        return $str;

    }
}