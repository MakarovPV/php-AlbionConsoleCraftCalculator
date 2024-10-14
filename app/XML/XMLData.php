<?php

namespace App\XML;

abstract class XMLData
{
    protected array $generatedData = [];
    protected $xml;

    public function __construct(string $xmlFilePath)
    {
        $this->xml = simplexml_load_file($xmlFilePath);
    }

    abstract public function generateItems(string $searchValue, int $count);

    public function getGeneratedData()
    {
        return $this->generatedData;
    }
}