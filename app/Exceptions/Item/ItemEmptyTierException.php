<?php

namespace App\Exceptions\Item;

class ItemEmptyTierException extends \Exception
{
    protected $message = "Уровень предмета не может быть пустым.";
}