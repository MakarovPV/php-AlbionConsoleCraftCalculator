<?php

namespace App\XML;

class XMLDataItemList extends XMLData
{
    private array $itemTypes = ['simpleitem', 'consumableitem', 'equipmentitem', 'weapon', 'mount'];

    public function __construct()
    {
        parent::__construct('data/items.xml');
    }

    public function generateItems(string $searchValue, int $count)
    {
        $itemType = $this->selectItemType($searchValue);

        if(empty($itemType)) return $searchValue;
        foreach ($itemType as $resource) {
            $uniqueName = $this->generateItems($resource['uniquename'], $count * $resource['count']);
            if (!empty($uniqueName)) {
                $this->generatedData[(string)$resource['uniquename']] = $resource['count'] * $count;
            }
        }
    }

    //поиск в xml файле по тикеру
    private function selectItemType(string $searchValue)
    {
        foreach ($this->itemTypes as $item){
            $foundItems = $this->xml->xpath("//$item
                                            [@uniquename='$searchValue']
                                            /craftingrequirements[1]
                                            /craftresource");

            if(!empty($foundItems)) return $foundItems;
        }
        return false;
    }

}