<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;

use Illuminate\Support\Facades\DB;

class MasterWeight extends Model
{
    use HasFactory;

    public $table = 'master_weights';
    public $timestamp = false;

    protected $fillable = [
        "name",
        "name_bn"
    ];

}
