<?php

namespace App\Exceptions\Item;

class ItemEmptyNameException extends \Exception
{
    protected $message = "Название предмета не может быть пустым.";
}