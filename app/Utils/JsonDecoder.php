<?php

namespace App\Utils;

class JsonDecoder
{
    public static function getDataFromJson(string $path)
    {
        $jsonData = file_get_contents($path);

        if (!$jsonData) {
            die('Ошибка при получении данных');
        }

        $dataArray = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Ошибка декодирования JSON: ' . json_last_error_msg());
        }

        return $dataArray;
    }
}