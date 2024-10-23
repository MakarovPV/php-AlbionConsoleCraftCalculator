<?php

namespace App\Commands;

Interface CommandInterface
{
    /**
     * Запуск команды.
     */
    public function run();


    /**
     * Оповещение об успешном выполнении команды.
     * @return string
     */
    public function notification(): string;
}