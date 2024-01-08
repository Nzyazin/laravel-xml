<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    // Отношение многие к одному с категориями
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category');
    }

    // Отношение многие к одному с подкатегориями
    public function subcategory()
    {
        return $this->belongsTo(Subcategories::class, 'sub_category');
    }

    // Отношение многие к одному с подподкатегориями
    public function subsubcategories()
    {
        return $this->belongsTo(Subsubcategories::class, 'sub_sub_category');;
    }
}
