<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Model;

class MasterVoucher extends Model
{
    use HasFactory;

    protected $table = 'master_voucher';

    public function CreditLedger(): hasOne {
        return  $this->hasOne(Ledger::class,'id','credit_head');
    }

    public function DebitLedger(): hasOne {
        return  $this->hasOne(Ledger::class,'id','debit_head');
    }

    public function scopeOfLedgerAndDate(Builder $query, $ledgerId, $from, $to){
        return $query->where(function ($q) use ($ledgerId) {
                    $q->where('debit_head', $ledgerId)
                      ->orWhere('credit_head', $ledgerId);
                })
                ->whereBetween('date', [$from, $to]);
    }

    protected $fillable = [
		"voucher_no",
		"voucher_type",
		"debit_head",
		"credit_head",
		"amount",
		"note",
		"date",
		"status"
    ];

}