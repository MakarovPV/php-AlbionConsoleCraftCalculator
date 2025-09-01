<?php

namespace App\Statistics;


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
    public function build(int $countOfIteration, int $amountItemsPerIteration, array $namesOfMainItem): string
    {
        $str = 'Предмет ' . $namesOfMainItem['rusName'] . ' крафтится в количестве ' . $amountItemsPerIteration . ' единиц.' . "\n\n";

        foreach ($this->cityNames as $city) {
            $this->prev->setCityName($city);
            $str .= $this->prev->build($countOfIteration, $amountItemsPerIteration, $namesOfMainItem);
            $str .= 'Стоимость с 4% налогом за ' . $amountItemsPerIteration . ' предметов в городе ' . $city . ': ' . $this->calculateCostWithReturn($this->prev->mainItemCost, 4) . " серебра.\n" .
                'Стоимость с 6.5% налогом за ' . $amountItemsPerIteration . ' предметов в городе ' . $city . ': ' . $this->calculateCostWithReturn($this->prev->mainItemCost, 6.5) . " серебра.\n\n";
        }
        return $str;

    }
}