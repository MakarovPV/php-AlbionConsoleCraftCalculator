<?php

namespace App\Exceptions\Item;

class ItemIncorrectTierException extends \Exception
{
    protected $message = "Некорректный уровень предмета.";
}