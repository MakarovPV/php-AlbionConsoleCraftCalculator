<?php

namespace App\Statistics;

class FullStatistic extends Statistic
{
    public function __construct(array $cityName = [])
    {
        parent::__construct();
        $this->cityName = $cityName;
        $this->prev = new DefaultStatistic();
    }

    /**
     * @inheritDoc
     */
    public function build(int $countOfIteration, int $amountItemsPerIteration, array $namesOfMainItem): string
    {
        $str = '';
        foreach ($this->cityName as $city) {
            $this->prev->setCityName($city);
            $str .= $this->prev->build($countOfIteration, $amountItemsPerIteration, $namesOfMainItem);

            $itemsArray = $this->getGeneratedItems($countOfIteration, $namesOfMainItem['uniqueName']);

            $str .= "Поиск данных по ресурсам для его создания:\n";
            $totalCost = 0;
            foreach ($itemsArray as $uniqueName => $count){
                $costItem = $this->calculate($count, $this->getItemCostFromApi($uniqueName, $city));
                $totalCost += $costItem;
                $str .= 'Требуется предмет ' . $this->getRusNameFromElastic($uniqueName) .
                    ' в количестве ' . $count . ' единиц, с общей стоимостью в ' .
                    $costItem .
                    " серебра. \n";
            }
            $str .= "\nОбщая стоимость ресурсов: " . $totalCost . " серебра.\n" .
                'Общая стоимость с 15.2% возвратом ресурсов: ' . $this->calculateCostWithReturn($totalCost, 15.2) . " серебра. Примерный профит составляет " . $this->percentageBalance($this->prev->prev->mainItemCost * 0.935, $totalCost * 0.848) . "%\n" .
                'Общая стоимость с 24.8% возвратом ресурсов: ' .$this->calculateCostWithReturn($totalCost, 24.8) . " серебра. Примерный профит составляет " . $this->percentageBalance($this->prev->prev->mainItemCost * 0.935, $totalCost * 0.752) . "%\n___________________\n";
        }
        return $str;
    }
}