<?php

namespace App\Statistics;


class DefaultStatistic extends Statistic
{
    public function __construct(string $cityName)
    {
        parent::__construct();
        $this->cityName = $cityName;
        $this->prev = new ShortStatistic($cityName);
    }

    /**
     * Получение данных по основному предмету и вывод их в консоль.
     * @param array $namesOfMainItem
     * @return void
     */
    public function build(int $amountItems, array $namesOfMainItem): string
    {
        $str = 'Предмет ' . $namesOfMainItem['rusName'] . ' крафтится в количестве ' . $amountItems . ' единиц.' . "\n\n";
        $str .= $this->prev->build($amountItems, $namesOfMainItem);
        $str .= 'Стоимость с 4% налогом за ' . $amountItems . ' предметов: ' . $this->calculateCostWithReturn($this->prev->mainItemCost, 4)  . " серебра.\n" .
            'Стоимость с 6.5% налогом за ' . $amountItems . ' предметов: ' . $this->calculateCostWithReturn($this->prev->mainItemCost, 6.5)  . " серебра.\n";
        return $str;

    }
}