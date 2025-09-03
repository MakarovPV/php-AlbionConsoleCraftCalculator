<?php

namespace App\Exceptions\Item;

class CountOfItemsIncorrectException extends \Exception
{
    protected $message = "Количество предметов не может быть меньше 0";
}