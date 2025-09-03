<?php

namespace App\Statistics;

use App\DTO\Statistics\StatisticBuildDTO;

class FullStatistic extends Statistic
{
    public function __construct(array $cityNames = [])
    {
        parent::__construct($cityNames);
        $this->prev = new DefaultStatistic();
    }

    /**
     * @inheritDoc
     */
    public function build(StatisticBuildDTO $data): string
    {
        $str = '';
        foreach ($this->cityNames as $city) {
            $str .= $this->getDataFromPrev($city, $data);
            $itemsArray = $this->getGeneratedItems($data->countOfIteration, $data->namesOfMainItem['uniqueName']);

            $str .= "Поиск данных по ресурсам для его создания:\n";
            $totalCost = 0;
            foreach ($itemsArray as $uniqueName => $count){
                $costItem = $this->calculate($data->countOfIteration, $count, $this->getItemCostFromApi($uniqueName, $city));
                $totalCost += $costItem;
                $str .= 'Требуется предмет ' . $this->getRusNameFromElastic($uniqueName) .
                    ' в количестве ' . $data->countOfIteration * $count . ' единиц, с общей стоимостью в ' .
                    $costItem .
                    " серебра. \n";
            }
            $str .= "\nОбщая стоимость ресурсов: " . $totalCost . " серебра.\n" .
                'Общая стоимость с 15.2% возвратом ресурсов: ' . $this->calculateCostWithReturn($totalCost, 15.2) . " серебра. Примерный профит составляет " . $this->percentageBalance($this->prev->prev->mainItemCost * $data->countOfIteration * 0.935, $totalCost * 0.848) . "%\n" .
                'Общая стоимость с 24.8% возвратом ресурсов: ' .$this->calculateCostWithReturn($totalCost, 24.8) . " серебра. Примерный профит составляет " . $this->percentageBalance($this->prev->prev->mainItemCost * $data->countOfIteration * 0.935, $totalCost * 0.752) . "%\n___________________\n";
        }
        return $str;
    }
}