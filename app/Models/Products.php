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
            $product = new self();
            $subsubcategory = Subsubcategories::find((integer) $offer['category']);
            if ($subsubcategory) {
                $product->sub_sub_category = $subsubcategory->name;
                $product->sub_category = $subsubcategory->parent_name;
                $subcategory = Subcategories::find($subsubcategory->parent_id);
                $product->category = $subcategory->parent_name;
            } else {
                $subcategory = Subcategories::find((integer) $offer['category']);
                $product->sub_category = $subcategory->name;
                $product->category = $subcategory->parent_name;
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
        return $product;
    }
}
