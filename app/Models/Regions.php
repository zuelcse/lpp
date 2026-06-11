<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use HasFactory;

    public $table = 'location_regions';
    public $timestamp = false;

    public function getAll() {
        return Regions::pluck('name','id');
    }

    public function getUnit($id) {
        return Regions::select('name')->where('id',$id)->first();
    }

}
