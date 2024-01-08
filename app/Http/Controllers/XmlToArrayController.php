<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class XmlToArrayController extends Controller
{
    public function downloadAndProcessXml()
    {
        $url = 'https://quarta-hunt.ru/bitrix/catalog_export/export_Ngq.xml';

        //Используем Guzzle для выполнения HTTP-запроса
        $response = Http::get($url);
        
        // Проверка успешности запроса
        if ($response->successful()) {
            $xmlContent = $response->body();
            $xmlArray = simplexml_load_string($xmlContent);
            $categoriesArray = [];
            foreach ($xmlArray->shop->categories->category as $category) {

                $categoryId = (string) $category['id'];
                $categoryName = (string) $category;

                $parentId = isset($category['parentId']) ? (string) $category['parentId'] : null;   
                if ($parentId == null) {
                    $categoriesArray[$categoryId] = [
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'subcategory' => [],
                    ];
                    
                    foreach ($xmlArray->shop->categories->category as $subcategory) {
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

                            foreach ($xmlArray->shop->categories->category as $subsubcategory) {
                                $subsubcategoryId = (string) $subsubcategory['id'];
                                $subsubcategoryName = (string)$subsubcategory;
                                $parentId3 = isset($subsubcategory['parentId']) ? (string) $subsubcategory['parentId'] : null;
                                //dd($parentId3);
                                if ($subcategoryId == $parentId3) {
                                    //dd($productId);

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
        } else {
            throw new \Exception('Ошибка получения XML');
        }    
        return view('upload-xml');
    }
}
