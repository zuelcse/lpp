<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challan extends Model
{
    use HasFactory;

    protected $table = 'challans';

    public const STATUSES = [
        'Pending',
        'Dispatched',
        'Delivered',
        'Cancelled',
    ];

    protected $fillable = [
        'challan_no',
        'challan_date',
        'sales_id',
        'ledger_id',
        'delivery_address',
        'contact_person',
        'contact_phone',
        'driver_name',
        'vehicle_no',
        'transport_name',
        'reference_no',
        'status',
        'deduct_stock',
        'narration',
        'user_id',
    ];

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(Ledger::class, 'ledger_id', 'id');
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChallanItem::class, 'challan_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
