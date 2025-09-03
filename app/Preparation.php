<?php

namespace App;

use App\Exceptions\Item\ItemEmptyTierException;
use App\Validators\CityValidator;
use App\Validators\ItemValidator;
use Database\ElasticSearch\Cities;

/**
 * Класс, предназначенный для получения данных из консоли и преобразования их в более удобный и понятный вид.
 */
class Preparation
{

    private array $parameters = [];

    public function __construct(array $inputDataArray)
    {
        $this->extractParametersFromConsoleInput($inputDataArray);
    }

    public function extractParametersFromConsoleInput(array $inputDataArray): void
    {
        $countOfItems = $inputDataArray[0];
        ItemValidator::notEmptyCountOfItems((int)$countOfItems);
        ItemValidator::correctCountOfItems((int)$countOfItems);

        $itemNamesAndTier = $this->getFirstPartOfInput($inputDataArray);
        $cityAndStatType = $this->getStatisticTypeAndCityName($this->getSecondPartOfInput($inputDataArray));

        $this->parameters = [
            'countOfItems' => $countOfItems,
            'itemTier' => $itemNamesAndTier['tier'],
            'itemNamesFromInput' => $itemNamesAndTier['itemNames'],
            'itemEnchant' => $itemNamesAndTier['enchant'],
            'statisticType' => $cityAndStatType['statisticType'],
            'cityNames' => $cityAndStatType['cityNames']
        ];
    }

    /**
     * Извлечение первой части вхдящей строки, включающей в себя название предмета, его тир и уровень зачарования.
     * @param array $array
     * @return array
     */
    private function getFirstPartOfInput(array $array): array
    {
        $trimArray = [];
        foreach (array_slice($array, 1) as $item){
            if(is_numeric($item)){
                $trimArray['tier'] = (string) round($item);
                ItemValidator::correctTier($trimArray['tier']);
                $trimArray['enchant'] = (string) round(($item - floor($item)) * 10);
                ItemValidator::correctEnchant($trimArray['enchant']);
                ItemValidator::notEmptyName('itemNames', $trimArray);
                return $trimArray;
            }
            $trimArray['itemNames'][] = $item;
        }
        throw new ItemEmptyTierException();
    }

    /**
     * Извлечение второй части вхдящей строки, включающей в себя название города и тип статистики.
     * @param array $array
     * @return array
     */
    private function getSecondPartOfInput(array $array): array
    {
        $statTypeAndCityName = [];
        for($i=1; $i<count($array); $i++){
            if(is_numeric($array[$i])){
                $statTypeAndCityName = array_slice($array, $i+1, 2);
                break;
            }
        }
        return array_merge($statTypeAndCityName, array_fill(0, 2 - count($statTypeAndCityName), null));
    }

    public function getStatisticTypeAndCityName(array $array): array
    {
        if($this->getStatisticTypeClassNameFromInput($array[0])) {
            list($statisticType, $cityName) = $array;
        } else {
            list($cityName, $statisticType) = $array;
        }

        return [
            'statisticType' => $this->getStatisticTypeClassNameFromInput($statisticType),
            'cityNames' => $this->getEngCityNamesFromElasticByInput($cityName)
        ];
    }


    private function getStatisticTypeClassNameFromInput(?string $statisticType): string|false
    {
        if(!$statisticType) $statisticType = 'default';
        $className = "\App\Statistics\\" . ucfirst($statisticType) . 'Statistic';

        if (class_exists($className)) {
            return $className;
        }

        return false;
    }

    private function getEngCityNamesFromElasticByInput(?string $cityName): array
    {
        CityValidator::notEmpty($cityName);

        $cities = new Cities();

        return $cities->getEngCityNames($cityName);
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