<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'product_id', 'url', 'price', 
    'old_price', 'currencyId', 'picture', 'vendor',
    'category', 'sub_category', 'sub_sub_category', 'available'
    ];    

    public static function createWithCategories($shopArray)
    
    {
        Products::truncate(); 
        foreach ($shopArray['offers'] as $offer) {
            //dd($shopArray['subcategories']);
            $product = new self();
            $Id = isset($offer['category']) ? $offer['category'] : null;
            if ($Id) {
                if (isset($shopArray['subsubcategories'][$Id])) {
                    $product->sub_sub_category = $shopArray['subsubcategories'][$Id]['name'];
                    $product->sub_category = $shopArray['subsubcategories'][$Id]['parentName'];

                    $subsubcategoriesId = $shopArray['subsubcategories'][$Id]['id'];
                    
                    foreach($shopArray['subcategories'] as $subcategories) {
                        if ($subcategories['parentId'] == $subsubcategoriesId) {
                            $product->category = $subcategories['parentName'];
                        }
                    } 

                } elseif (isset($shopArray['subcategories'][$Id])) {
                    $product->sub_category = $shopArray['subcategories'][$Id]['name'];
                    $product->category = $shopArray['subcategories'][$Id]['parentName'];
                }
            }       

            $product->name = $offer['name'];
            $product->product_id = $offer['product_id'];
            $product->url = $offer['url'];
            $product->price = $offer['price'];
            $product->old_price = $offer['old_price'];
            $product->currencyId = $offer['currencyId'];
            $product->picture = $offer['picture'];
            $product->vendor = $offer['vendor'];
            $product->available = $offer['available'];
            $product->save();            
        }   
    }
}
