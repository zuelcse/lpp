<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materialitems extends Model
{
    use HasFactory;

    public $table = 'material_items';
    public $timestamps = true;

    protected $fillable = [
        'alias',
        'name',
        'name_bn',
        'unit',
        'group',
        'barcode',
        'quantity',
        'opening_stock',
        'opening_value',
    ];

    public function materialGroup()
    {
        return $this->belongsTo(MaterialGroups::class, 'group', 'id');
    }

    public function unitDetail()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }
}
