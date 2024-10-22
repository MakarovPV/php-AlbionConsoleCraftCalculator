<?php

namespace App\Commands;

use App\Utils\JsonDecoder;
use Database\ElasticSearch\Elastic;

abstract class Command implements CommandInterface
{
    protected Elastic $elastic;
    protected string $fileNameWithData;

    public function __construct(Elastic $elastic, string $fileNameWithData)
    {
        $this->elastic = $elastic;
        $this->fileNameWithData = $fileNameWithData;
    }

    public function run()
    {
        $this->elastic->insertAllDocuments(JsonDecoder::getDataFromJson($this->fileNameWithData));
    }
}