<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterMaterials extends Model
{
    use HasFactory;

    protected $table = 'master_materials';

    protected $fillable = [
        'voucher_no',
        'voucher_type',
        'item_id',
        'received_qty',
        'issue2production_qty',
        'production_qty',
        'rate',
        'value',
        'note',
        'date',
    ];

    public function materialItem(): BelongsTo
    {
        return $this->belongsTo(Materialitems::class, 'item_id', 'id');
    }

    public function mrr(): BelongsTo
    {
        return $this->belongsTo(MaterialsReceivedMrr::class, 'voucher_no', 'voucher_no');
    }
}
