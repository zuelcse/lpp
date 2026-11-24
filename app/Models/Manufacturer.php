<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    public $table = 'manufacturer';

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    protected $fillable = ['name'];

    public function stockItems()
    {
        return $this->hasMany(StockItem::class, 'manufacturer', 'id');
    }

    public function getAll()
    {
        return Manufacturer::orderBy('name', 'ASC')->pluck('name', 'id');
    }

    public function getUnit($id)
    {
        return Manufacturer::select('name')->where('id', $id)->first();
    }
}
