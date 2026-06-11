<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;

use Illuminate\Support\Facades\DB;

class Subgroups extends Model
{
    use HasFactory;

    public $table = 'group_sub';
    public $timestamp = false;

    public function Group(): hasOne {
        return  $this->hasOne(Group::class,'id','main_group_id');
    }

    public function Ledger(): hasMany {
        return  $this->hasMany(Ledger::class,'sub_group_id','id');
    }

    protected $fillable = [
        "main_group_id",
        "name",
        "alias"
    ];

}
