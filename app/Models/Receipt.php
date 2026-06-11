<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'voucher';

    protected $fillable = [
		"date",
		"user_id",
		"voucher_type",
        "credit_head",
        "debit_head",
		"ledger_party",
		"bank_chas_name",
		"voucher_no",
        "amount",
        "attachment",
		"payment_mode",
		"check_no",
        "check_date",
        "narration",
        "status"
    ];

    public function Ledger(): hasOne {
        return  $this->hasOne(Ledger::class,'id','ledger_party');
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

}
