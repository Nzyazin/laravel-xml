<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    
    // Отношение один ко многим с подкатегориями
    public function subcategories()
    {
        return $this->hasMany(Subcategories::class, 'parent_id');
    }
}