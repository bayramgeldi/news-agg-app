<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'source',
        'name',
        'title',
        'description',
    ];


    public function main_categories()
    {
        return $this->belongsToMany(MainCategory::class,'main_category_categories');
    }
}
