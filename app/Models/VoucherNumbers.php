<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class VoucherNumbers extends Model
{
    use HasFactory;

    protected $table = 'voucher_numbers';

    public function getAllVoucherTypes() {
        return VoucherType::paginate(10);
    }

    public function SalesOrder(): belongsTo {
        return  $this->belongsTo(SalesOrder::class,'id','voucher_type');
    }

    public function getVoucherType($i=1) {
        return VoucherType::where('status',$i)->pluck('voucher_name','id');
    }

    public function getSalesVoucherTypes() {
        return VoucherType::where('voucher_type',1)->pluck('voucher_name','id');
    }
    public function getReceiptVoucherTypes() {
        return VoucherType::where('voucher_type',2)->pluck('voucher_name','id');
    }

    protected $fillable = [
        'voucher_type_id',
        'ym',
        'serial'
    ];

}
