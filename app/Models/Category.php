<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = 'category';

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = ['name'];

    public function subCategories()
    {
        return $this->hasMany(ItemSubCategory::class, 'category_id', 'id');
    }

    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'category', 'id');
    }

    public function getAll()
    {
        return Category::orderBy('name', 'ASC')->pluck('name', 'id');
    }

    public function getUnit($id)
    {
        return Category::select('name')->where('id', $id)->first();
    }
}
