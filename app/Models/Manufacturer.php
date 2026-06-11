<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    public $table = 'manufacturer';
    public $timestamp = false;

    public function getAll() {
        return Manufacturer::pluck('name','id');
    }

    public function getUnit($id) {
        return Manufacturer::select('name')->where('id',$id)->first();
    }

}
