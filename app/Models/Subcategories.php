<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    protected $table = 'subcategories';
    protected $primaryKey = 'sub_category_id';
    protected $fillable = ['sub_category_id', 'name', 'parent_id', 'parent_name'];

    // Отношение многие к одному с категориями
    public function category()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    // Отношение один ко многим с подподкатегориями
    public function subsubcategories()
    {
        return $this->hasMany(Subsubcategories::class, 'sub_sub_category_id');
    }
}
