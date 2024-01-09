<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsubcategories extends Model
{
    protected $table = 'subsubcategories';
    protected $primaryKey = 'sub_sub_category_id';
    protected $fillable = ['sub_sub_category_id', 'name', 'parent_id', 'parent_name'];

    // Отношение многие к одному с подкатегориями
    public function subcategories()
    {
        return $this->belongsTo(Subcategories::class, 'sub_sub_category_id');
    }
    
}
