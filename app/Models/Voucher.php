<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';

    public function getVouchers() {
        return Voucher::paginate(10);
    }

    public function MasterVoucher(): hasMany {
        return $this->hasMany(MasterVoucher::class,'voucher_no','voucher_no');
    }

    public function CreditHead(): hasOne {
        return  $this->hasOne(Ledger::class,'id','credit_head');
    }

    public function DebitHead(): hasOne {
        return  $this->hasOne(Ledger::class,'id','debit_head');
    }

    public function VoucherType(): hasOne {
        return  $this->hasOne(VoucherType::class,'id','voucher_type');
    }

    public function CostCenter(): hasOne {
        return  $this->hasOne(CostCenter::class,'id','cost_center');
    }

    public function getReceipt() {
        return Receipt::with(['Ledger','VoucherType','CostCenter'])->orderBy('date','DESC')->paginate(10);
    }

    protected $fillable = [
        "date",
        "voucher_no",
        "voucher_type",
        "total_amount",
        "narration_remarks",
        "payment_mode",
        "cheque_no",
        "cheque_date",
        "user_id",
        "status"
    ];

}

