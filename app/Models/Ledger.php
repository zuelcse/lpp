<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;

use Illuminate\Support\Facades\DB;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledgers';

    public static function getLedgers() {
        return Ledger::paginate(10);
    }
    
    public function Group(): belongsTo {
        return  $this->belongsTo(Group::class,'main_group_id','id');
    }
    
    public function Subgroups(): belongsTo {
        return  $this->belongsTo(Subgroups::class,'sub_group_id','id');
    }
    
    public function DebitMasterItems(): hasMany {
        return  $this->hasMany(MasterItems::class,'debit_head','id');
    }

    public function CreditMasterItems(){
        return $this->hasMany(MasterItems::class, 'credit_head', 'id');
    }
    
    /*public function MasterVoucher(): hasMany {
        return  $this->hasMany(MasterVoucher::class,'debit_head','id');
    }*/

    // public function DebitMasterVouchers(){
    //     return $this->hasMany(MasterVoucher::class, 'debit_head', 'id');
    // }
    public function DebitMasterVouchersBeforeRange(){
        return $this->hasMany(MasterVoucher::class, 'debit_head', 'id');
    }
    public function DebitMasterVouchersInRange(){
        return $this->hasMany(MasterVoucher::class, 'debit_head', 'id');
    }


    public function CreditMasterVouchersBeforeRange(){
        return $this->hasMany(MasterVoucher::class, 'credit_head', 'id');
    }
    public function CreditMasterVouchersInRange(){
        return $this->hasMany(MasterVoucher::class, 'credit_head', 'id');
    }
    
    public function LedgerPermission(): belongsTo {
        return  $this->belongsTo(LedgerPermission::class,'id','ledger_id');
    }

    public function SalesOrder(): belongsTo {
        return  $this->belongsTo(SalesOrder::class,'id','ledger_party');
    }

    public function getLedger() {
        return Ledger::pluck('select2-hidden-accessible','name','id');
    }

    // public function allLedgers() {
    //     return Ledger::pluck('name','id');
    // }

    public function cashLedgers() {
        $main_group = [30];
        return Ledger::whereIN('main_group_id',$main_group)
        ->pluck('name','id');
    }

    public function bankLedgers() {
        $main_group = [31];
        return Ledger::whereIN('main_group_id',$main_group)
        ->pluck('name','id');
    }

    public function partyLedgers() {
        $main_group = [25];
        return Ledger::whereIN('main_group_id',$main_group)
        ->pluck('name','id');
    }

    public function allLedgers($sgroups = [],$rgroups=[]) {//sgroups:Select,rgroups:Remove/Skips
        return Ledger::select('id',DB::raw("CONCAT(alias, ' - ', name) AS name"))->when($sgroups, function ($q, $sgroups) {
            $q->whereIn('main_group_id', $sgroups);
        })->when($rgroups, function ($q, $rgroups) {
            $q->whereNotIn('main_group_id', $rgroups);
        })->pluck('name','id');
    }


    protected $fillable = [
        'main_group_id',
        'sub_group_id',
        'alias',
        'name',
        'name_bn',
        'opening_balance',
        'closing_balance',
        'credit_limit',
        'description',
        'address',
        'address_bn',
        'mobile',
        'email',
        'bank_account_holder',
        'bank_account_number',
        'bank_name',
        'bank_branch',
        'region_id',
        'area_id',
        'territory_id'
    ];

}
