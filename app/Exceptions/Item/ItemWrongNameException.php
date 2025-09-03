<?php

namespace App\Exceptions\Item;

class ItemWrongNameException extends \Exception
{
    protected $message = "Предмет не найден.";
}