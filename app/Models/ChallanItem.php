<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallanItem extends Model
{
    use HasFactory;

    protected $table = 'challan_items';

    protected $fillable = [
        'challan_id',
        'item_id',
        'item_name',
        'unit_name',
        'quantity',
        'note',
    ];

    public function challan(): BelongsTo
    {
        return $this->belongsTo(Challan::class, 'challan_id', 'id');
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class, 'item_id', 'id');
    }
}
