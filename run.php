<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Commands\CommandManager;
use App\Commands\InsertCitiesToElastic;
use App\Commands\InsertItemsToElastic;

/**
 * Загрузка данных в эластик.
 */
$commands = new CommandManager([new InsertCitiesToElastic(), new InsertItemsToElastic()]);
$commands->run();