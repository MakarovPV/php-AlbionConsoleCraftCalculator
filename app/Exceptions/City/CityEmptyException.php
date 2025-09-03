<?php

namespace App\Exceptions\City;

class CityEmptyException extends \Exception
{
    protected $message = "Название города не может быть пустым.";
}