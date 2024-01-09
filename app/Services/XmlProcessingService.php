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
        //dd($xmlObject->shop->offers);
        $categoriesArray = [];

        //Цикл для разбора категорий
        foreach ($xmlObject->shop->categories->category as $category) {            
            $categoryId = (string) $category['id'];
            $categoryName = (string) $category;

            $parentId = isset($category['parentId']) ? (string) $category['parentId'] : null;   
            if ($parentId == null) {
                $categoriesArray[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $categoryName,
                    'subcategory' => [],
                ];

                //Цикл для сопоставления категорий и подкатегорий
                foreach ($xmlObject->shop->categories->category as $subcategory) {
                    $subcategoryId = (string) $subcategory['id'];
                    $subcategoryName = (string) $subcategory;
                    $parentId2 = isset($subcategory['parentId']) ? (string) $subcategory['parentId'] : null; 

                    if ($categoryId == $parentId2) {
                        $categoriesArray[$categoryId]['subcategory'][$subcategoryId] = [
                            'id' => $subcategoryId,
                            'name' => $subcategoryName,
                            'parent_id' => $parentId2,
                            'subsubcategory' => [],
                        ];                            

                        //Цикл для сопоставления подкатегорий и подподкатегорий
                        foreach ($xmlObject->shop->categories->category as $subsubcategory) {
                            $subsubcategoryId = (string) $subsubcategory['id'];
                            $subsubcategoryName = (string)$subsubcategory;
                            $parentId3 = isset($subsubcategory['parentId']) ? (string) $subsubcategory['parentId'] : null;
                            
                            if ($subcategoryId == $parentId3) {

                                $categoriesArray[$categoryId]['subcategory'][$subcategoryId]['subsubcategory'][$subsubcategoryId] = [
                                    'id' => $subsubcategoryId,
                                    'name' => $subsubcategoryName,
                                    'parent_id' => $parentId3,
                                ];
                            }
                        }
                        
                    }                        
                } 
            }                                        
        }
        $offerArray = [];
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
        $shopArray = [
            'categories' => $categoriesArray,
            'offers' => $offerArray
        ];
        return $shopArray;
    }
}