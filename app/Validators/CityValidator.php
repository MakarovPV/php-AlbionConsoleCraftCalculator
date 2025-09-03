<?php

namespace App\Validators;

use App\Exceptions\City\CityEmptyException;

class CityValidator
{
    public static function notEmpty(?string $cityName): void {
        if ($cityName === null || $cityName === '') throw new CityEmptyException();
    }
}