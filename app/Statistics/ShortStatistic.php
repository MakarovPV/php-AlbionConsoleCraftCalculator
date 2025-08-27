<?php

namespace App\Statistics;

class ShortStatistic extends Statistic
{
    public function __construct(array $cityName = [])
    {
        parent::__construct();
        $this->cityName = $cityName;
    }

    /**
     * @inheritDoc
     */
    public function build(int $countOfIteration, int $amountItemsPerIteration, array $namesOfMainItem): string
    {
        $str = '';
        foreach ($this->cityName as $city){
            $this->mainItemCost = $this->calculate($amountItemsPerIteration, $this->getItemCostFromApi($namesOfMainItem['uniqueName'], $city));
            $str .= 'Стоимость ' . $amountItemsPerIteration . ' предметов ' . $namesOfMainItem['rusName'] . ' в городе ' . $city . ' составляет ' . $this->mainItemCost . " серебра.\n";

        }
         return $str;
    }
}