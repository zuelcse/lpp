<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;

use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;

    public $table = 'group_main';
    public $timestamp = false;


    public function Subgroups(): hasMany {
        return  $this->hasMany(Subgroups::class,'main_group_id','id');
    }

    public static function getAll() {
        return Group::select('id',DB::raw("CONCAT(LPAD(id, 2, '0'), ' - ', name) as name"))->orderBy('name','ASC')->pluck('name','id');
    }

    public function getUnit($id) {
        return Group::select('name')->where('id',$id)->first();
    }

}
