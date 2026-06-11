<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = 'category';
    public $timestamp = false;

    public function getAll() {
        return Category::pluck('name','id');
    }

    public function getUnit($id) {
        return Category::select('name')->where('id',$id)->first();
    }

}
