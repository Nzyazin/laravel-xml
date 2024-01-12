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

         // Обработка категорий, подкатегорий и подподкатегорий
         foreach ($xmlObject->shop->categories->category as $category) {
            
            $id = (string) $category['id'];
            $name = (string) $category;
            $parentId = isset($category['parentId']) ? (string) $category['parentId'] : null;

            if ($parentId === null) {
                $categoriesArray[$id] = ['id' => $id, 'name' => $name];
            } else {
                if (isset($categoriesArray[$parentId])) {
                    $subcategories[$id] = ['id' => $id, 'name' => $name, 'parentId' => $parentId];                    
                } else {
                    $subsubcategories[$id] = ['id' => $id, 'name' => $name, 'parentId' => $parentId];
                }
            }
        }

        foreach ($subcategories as $subcategoryId => $subcategory) {
            $parentId = $subcategory['parentId'];
        
            // Проверяем, существует ли категория с указанным parentId
            if (isset($categoriesArray[$parentId])) {
                // Добавляем подкатегорию в массив категории
                $categoriesArray[$parentId]['subcategories'][$subcategoryId] = $subcategory;
        
                // Проверяем, есть ли подподкатегории для текущей подкатегории
                if (isset($subsubcategories[$subcategoryId])) {
                    $subsubcategoryId = $subsubcategories[$subcategoryId]['id'];
                    $categoriesArray[$parentId]['subcategories'][$subcategoryId]['subsubcategories'][$subsubcategoryId] = $subsubcategories[$subcategoryId];
                }
            } else {
                    // Если категории не существует, создаем ее и добавляем подкатегорию
                $categoriesArray[$parentId] = [
                    'id' => $parentId,
                    'name' => 'Категория с ID ' . $parentId, // Можете использовать другое имя
                    'subcategories' => [$subcategoryId => $subcategory],
                ];

                // Проверяем, есть ли подподкатегории для текущей подкатегории
                if (isset($subsubcategories[$subcategoryId])) {
                    $subsubcategoryId = $subsubcategories[$subcategoryId]['id'];
                    $categoriesArray[$parentId]['subcategories'][$subcategoryId]['subsubcategories'][$subsubcategoryId] = $subsubcategories[$subcategoryId];
                }
            }
        }
        //dd($categoriesArray);
        
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
        dd($shopArray);
        return $shopArray;
    }
}