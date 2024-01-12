<?php

namespace App\Services;

use SimpleXMLElement;
use XMLReader;

class XmlProcessingService
{
    public function parseXmlToArray($xmlPath)
    {
        $xmlContent = file_get_contents($xmlPath);
        $xmlArray = $this->xmlToArray($xmlContent);
        return $xmlArray;
    }

    private function xmlToArray($xmlContent)
    {
        $xmlObject = simplexml_load_string($xmlContent);        
        $subcategories = [];
         // Обработка категорий, подкатегорий и подподкатегорий
        foreach ($xmlObject->shop->categories->category as  $category) {
            $id = (string) $category['id'];
            $name = (string) $category;
            $parentId = isset($category['parentId']) ? (string) $category['parentId'] : null;

            if ($parentId === null) {
                $categories[$id] = ['id' => $id, 'name' => $name];
            } else {
                if (isset($categories[$parentId])) {                                        
                    $subcategories[$id] = ['id' => $id, 'name' => $name, 'parentId' => $parentId, 'parentName' => $categories[$parentId]['name']];                   
                } else {                    
                    $subsubcategories[$id] = ['id' => $id, 'name' => $name, 'parentId' => $parentId, ];                    
                }
            }           
        } 

        //Добавление в подподкатегории подкатегорий
        foreach ($subsubcategories as $key => $subsubcategory) {
            if (isset($subcategories[$subsubcategory['parentId']])) {
                $subsubcategories[$key]['parentName'] = $subcategories[$subsubcategory['parentId']]['name'];
            }
        }     
        
        foreach ($xmlObject->shop->offers->offer as $offer) {        
            $arrayOffer = json_decode(json_encode($offer), true);
            $offerArray[] = [
                'name' => (string) $arrayOffer['name'],
                'product_id' => (string) $offer['id'],
                'url' => (string) $arrayOffer['url'],
                'price' => $arrayOffer['price'],
                'old_price' => isset($arrayOffer['oldprice']) ? $arrayOffer['oldprice'] : 'none',
                'currencyId' => (string) $arrayOffer['currencyId'],
                'category' => (string) $arrayOffer['categoryId'],
                'picture' => isset($arrayOffer['picture']) ? $arrayOffer['picture'] : 'none',
                'vendor' => (string) $arrayOffer['vendor'],

                'available' => (bool) $offer['available'],                
            ];            
           
        }

        $shopArray = ['categories' => $categories, 'subcategories' => $subcategories, 'subsubcategories' => $subsubcategories];
        return $shopArray;
    }
}