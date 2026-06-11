<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    public $table = 'units';
    public $timestamp = false;

    public function getAllUnits() {
        return Unit::pluck('name','id');
    }

    public function getUnit($id) {
        return Unit::select('name')->where('id',$id)->first();
    }

}
