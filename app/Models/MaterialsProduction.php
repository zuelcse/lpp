<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialsProduction extends Model
{
    use HasFactory;

    protected $table = 'materials_production';

    protected $fillable = [
        'voucher_no',
        'production_item',
        'quantity',
        'date',
        'narration',
    ];

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class, 'production_item', 'id');
    }

    public function masterMaterials(): HasMany
    {
        return $this->hasMany(MasterMaterials::class, 'voucher_no', 'voucher_no');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_no', 'voucher_no');
    }
}
