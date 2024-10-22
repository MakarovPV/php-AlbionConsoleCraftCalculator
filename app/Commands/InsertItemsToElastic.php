<?php

namespace App\Commands;

use Database\ElasticSearch\Items;

class InsertItemsToElastic extends Command
{
    public function __construct()
    {
        parent::__construct(new Items(), 'data/items.json');
    }

    public function notification()
    {
        echo 'Данные по предметам загружены';
    }
}