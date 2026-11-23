<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialsReceivedMrr extends Model
{
    use HasFactory;

    protected $table = 'materials_received_mrr';

    protected $fillable = [
        'voucher_no',
        'supplier_id',
        'challan_no',
        'challan_date',
        'date',
        'paid_amount',
        'narration',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_no', 'voucher_no');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Ledger::class, 'supplier_id', 'id');
    }

    public function masterMaterials(): HasMany
    {
        return $this->hasMany(MasterMaterials::class, 'voucher_no', 'voucher_no');
    }
}
