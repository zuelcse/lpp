<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubCategory extends Model
{
    use HasFactory;

    protected $table = 'item_sub_categories';

    protected $fillable = [
        'category_id',
        'name',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'sub_category', 'id');
    }

    public function getAll()
    {
        return ItemSubCategory::orderBy('name', 'ASC')->pluck('name', 'id');
    }
}
