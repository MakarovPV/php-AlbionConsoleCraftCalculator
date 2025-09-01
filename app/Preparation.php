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
            'itemTier' => $itemNamesAndTier['tier'],
            'itemNamesFromInput' => $itemNamesAndTier['itemNames'],
            'statisticType' => $cityAndStat['statisticType'],
            'cityNames' => $cityAndStat['cityNames'],
            'itemEnchant' => $itemNamesAndTier['enchant']
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
            'cityNames' => $this->getEngCityNamesFromElasticByInput($cityName)
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

    private function getEngCityNamesFromElasticByInput(string $cityName): array
    {
        $cities = new Cities();
        return $cities->getEngCityNames($cityName);
    }

    private function getItemTier(array $tierAndEnchant): string
    {

        return array_pop($tierAndEnchant)[0];
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