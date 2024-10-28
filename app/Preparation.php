<?php

namespace App;

use App\Traits\TrimArray;
use Database\ElasticSearch\Cities;

/**
 * Класс, предназначенный для получения данных из консоли и преобразования их в более удобный и понятный вид.
 */
class Preparation
{
    use TrimArray;

    private array $parameters = [];

    public function __construct(array $inputDataArray)
    {
        $this->extractParametersFromConsoleInput($inputDataArray);
    }

    public function extractParametersFromConsoleInput(array $inputDataArray): void
    {
        $itemNamesAndTier = $this->trimArrayForElastic($inputDataArray);
        $cityAndStat = $this->getStatisticTypeAndCityName($this->getStatTypeAndCityName($inputDataArray));

        $this->parameters = [
            'countOfItems' => $inputDataArray[0],
            'itemTier' => array_pop($itemNamesAndTier),
            'itemNamesFromInput' => $itemNamesAndTier,
            'statisticType' => $cityAndStat['statisticType'],
            'cityName' => $cityAndStat['cityName']
        ];
    }

    public function getStatisticTypeAndCityName(array $array): array
    {
        if($this->getStatisticTypeNameFromInput($array[0])) {
            list($statisticType, $cityName) = $array;
        } else {
            list($cityName, $statisticType) = $array;
        }

        return [
            'statisticType' => $this->getStatisticTypeNameFromInput($statisticType),
            'cityName' => $this->getEngCityName($this->getCityNameFromInput($cityName))
        ];
    }

    private function getStatisticTypeNameFromInput(?string $statisticType): string|false
    {
        if(!$statisticType) $statisticType = 'default';
        $className = "\App\Statistics\\".ucfirst($statisticType) . 'Statistic';

        if (class_exists($className)) {
            return $className;
        }
        return false;
    }

    private function getEngCityName(string $cityName): string
    {
        $cities = new Cities();
        return $cities->search([$cityName])['enName'];
    }

    private function getCityNameFromInput(?string $cityName): string
    {
        if(!$cityName) $cityName = 'Thetford';
        return $cityName;
    }

    public function __get(string $parameterName): mixed
    {
        return $this->getParameter($parameterName);
    }

    public function getParameter(string $parameterName): mixed
    {
        return $this->parameters[$parameterName];
    }


}