<?php

namespace App;

use App\Utils\TrimArray;
use Database\ElasticSearch\Cities;

class Preparation
{
    private array $parameters = [];

    public function __construct(array $inputDataArray)
    {
        $this->extractParametersFromConsoleInput($inputDataArray);
    }

    public function extractParametersFromConsoleInput(array $inputDataArray)
    {
        $itemNamesAndTier = TrimArray::trimArrayForElastic($inputDataArray);
        $cityAndStat = $this->getData(TrimArray::getStatTypeAndCityName($inputDataArray));

        $this->parameters = [
            'countOfItems' => $inputDataArray[0],
            'tier' => array_pop($itemNamesAndTier),
            'itemUniqueNames' => $itemNamesAndTier,
            'statisticType' => $cityAndStat['statisticType'],
            'cityName' => $cityAndStat['cityName']
        ];
    }

    public function getData(array $array)
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

    private function getStatisticTypeNameFromInput(?string $statisticType)
{
    if(!$statisticType) $statisticType = 'default';
    $className = "\App\Statistics\\".ucfirst($statisticType) . 'Statistic';

    if (class_exists($className)) {
        return $className;
    }
    return false;
}

    private function getCityNameFromInput(?string $cityName)
    {
        if(!$cityName) $cityName = 'Thetford';
        return $cityName;
    }

    public function __get(string $parameterName)
    {
        return $this->getParameter($parameterName);
    }

    public function getParameter(string $parameterName)
    {
        return $this->parameters[$parameterName];
    }

    private function getEngCityName(string $cityName)
    {
        $cities = new Cities();
        return $cities->search([$cityName])['enName'];
    }
}