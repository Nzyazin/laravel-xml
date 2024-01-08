<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsubcategories extends Model
{
    protected $table = 'subsubcategories';

    // Отношение многие к одному с подкатегориями
    public function subcategories()
    {
        return $this->belongsTo(Subcategories::class, 'sub_sub_category_id');
    }

    // Отношение один ко многим с продуктами
    public function products()
    {
        return $this->hasMany(Products::class, 'sub_sub_category');
    }
}
