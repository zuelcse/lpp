<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class StockItemPermission extends Model
{
    use HasFactory;

    protected $table = 'stock_item_permission';
    
    public function StockItem(): HasMany {
        return  $this->hasMany(StockItem::class,'parent','stock_item_group');
    }

}
