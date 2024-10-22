<?php

namespace App\Commands;

use Database\ElasticSearch\Cities;

class InsertCitiesToElastic extends Command
{
    public function __construct()
    {
        parent::__construct(new Cities(), 'data/cities.json');
    }

    public function notification()
    {
        echo 'Данные по городам загружены';
    }
}