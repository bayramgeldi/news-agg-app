<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
    ];


    public function sub_categories()
    {
        return $this->belongsToMany(Category::class,'main_category_categories');
    }
}
