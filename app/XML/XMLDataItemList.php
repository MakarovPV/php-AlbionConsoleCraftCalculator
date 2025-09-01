<?php

namespace App\XML;

class XMLDataItemList extends XMLData
{
    private array $itemTypes = ['simpleitem', 'consumableitem', 'equipmentitem', 'weapon', 'mount'];

    /**
     * xml-файл, из которого будут извлекаться комплектующие для крафта предмета.
     */
    public function __construct()
    {
        parent::__construct('data/items.xml');
    }
    
    /**
     * Заполнение массива данными по искомому предмету и его комплектующим из xml-файла.
     * @param string $searchValue
     * @param int $count
     * @return string|void
     */
    public function generateItems(string $searchValue, int $count)
    {
        $itemType = $this->selectItemType($searchValue);

        if(!$itemType) return;

        foreach ($itemType as $resource) {
            $this->generatedData[(string)$resource['uniquename']] = $resource['count'] * $count;
        }
    }

    /**
     * Более глубокое заполнение массива данными по искомому предмету, его комплектующим и комплектующим его комплектующих из xml-файла.
     * @param string $searchValue
     * @param int $count
     * @return string
     */
    public function deepGenerateItems(string $searchValue, int $count)
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

    /**
     * Проверка на наличие искомого предмета среди имеющихся типов крафтовых предметов.
     * Если предмет является не крафтовым, а базовым (не является создаваемым предметом), то возвращается false.
     * @param string $searchValue
     * @return array|false|false[]|\SimpleXMLElement[]
     */
    private function selectItemType(string $searchValue): array|false
    {
        if(is_numeric($searchValue[-1])){
            return $this->getEnchantItemDataByTag($searchValue);
        }
        return $this->getCommonItemDataByTag($searchValue);
    }

    private function getCommonItemDataByTag(string $searchValue): array|false
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

    private function getEnchantItemDataByTag(string $searchValue): array|false
    {
        $enchantLevel =  $searchValue[-1];
        $searchValue = substr($searchValue, 0, -2);

        foreach ($this->itemTypes as $item){
            $foundItems = $this->xml->xpath("//$item
                                            [@uniquename='$searchValue']
                                            /enchantments
                                            /enchantment[@enchantmentlevel='$enchantLevel']
                                            /craftingrequirements
                                            /craftresource");

            if(!empty($foundItems)) return $foundItems;
        }

        return $this->getCommonItemDataByTag($searchValue);
    }

    /**
     * Получение количества предметов, производимых за одну итерацию.
     * @param string $itemName
     * @return int
     */
    public function getAmountCraftedItems(string $itemName): int
    {
        foreach ($this->itemTypes as $item) {
            $amountCrafted = $this->xml->xpath("//$item
                                                [@uniquename='$itemName']
                                                /craftingrequirements[1]
                                                /@amountcrafted");
            if ($amountCrafted) {
                return (int) $amountCrafted[0];
            }
        }
        return 1;
    }
}