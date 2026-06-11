<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\hasOne;
use App\Models\Unit;
use App\Models\MasterItems;

class StockItem extends Model
{
    use HasFactory;

    public $table = 'stock_items';

    public function Unit(): hasOne {
        return  $this->hasOne(Unit::class,'id','unit');
    }

    public function MasterItems(){
        return $this->hasMany(MasterItems::class, 'item_id', 'id');
    }

    protected $fillable = [
        'name',
        'name_bn',
        'alias',
        'category',
        'unit',
        'salesRate',
        'purchaseRate',
        'manufacturer',
        'barcode',
        'part_no',
        'quantity'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function SalesItems(): belongsTo {
        return  $this->belongsTo(SalesItems::class,'id','item_id');
    }
}
