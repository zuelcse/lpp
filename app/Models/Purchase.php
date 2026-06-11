<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\hasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchase';

    protected $fillable = [
		"voucher_no",
        "voucher_type",
		"credit_head",
        "total_amount",
        "discount_amount",
        "gross_amount",
        "paid_amount",
        "narration",
		"user_id",
		"date"
    ];

    protected $hidden = [
        'voucher_class'
    ];

    public function Ledger(): hasOne {
        return  $this->hasOne(Ledger::class,'id','credit_head');
    }

    public function VoucherType(): hasOne {
        return  $this->hasOne(VoucherType::class,'id','voucher_type');
    }

    public function Voucher(): hasOne {
        return  $this->hasOne(Voucher::class,'voucher_no','voucher_no');
    }

    public function CostCenter(): hasOne {
        return  $this->hasOne(CostCenter::class,'id','cost_center');
    }

    public function MasterItems(): HasMany {
        return  $this->hasMany(MasterItems::class,'voucher_no','voucher_no');
    }

    public function SalesOrderDetails(): BelongsTo {
        return  $this->belongsTo(SalesOrderDetails::class,'id','sales_order_id');
    }

    public function SalesDespatchDetails(): BelongsTo {
        return  $this->belongsTo(SalesDespatchDetails::class,'id','sales_order_id');
    }

    
    public function SalesItemsVariousInformation(): HasMany {
        return  $this->hasMany(SalesItemsVariousInformation::class,'sales_order_id','id');
    }

    public function getOrder() {
        return SalesOrder::with(['Ledger','VoucherType','CostCenter'])->orderBy('date','DESC')->paginate(10);
    }

}
