<?php

namespace App\Api;

use App\Utils\JsonDecoder;

class AlbionResourceCost
{
    public function getCostByItemName(string $resourceName, string $cityName)
    {
        $url = "https://old.west.albion-online-data.com/api/v1/stats/Prices/$resourceName.json?locations=$cityName";
        $dataFromApi = JsonDecoder::getDataFromJson($url);

        return $dataFromApi[0]['sell_price_min'];
    }
}