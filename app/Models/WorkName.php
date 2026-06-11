<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;

use Illuminate\Support\Facades\DB;

class WorkName extends Model
{
    use HasFactory;

    public $table = 'work_names';
    public $timestamp = false;

    public function Group(): hasOne {
        return  $this->hasOne(Group::class,'id','main_group_id');
    }

    public function Ledger(): hasOne {
        return  $this->hasOne(Ledger::class,'id','debit_head');
        // return  $this->hasMany(Ledger::class,'sub_group_id','id');
    }

    protected $fillable = [
        "debit_head",
        "name",
        "name_bn"
    ];

}
