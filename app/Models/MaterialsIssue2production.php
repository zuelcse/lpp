<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialsIssue2production extends Model
{
    use HasFactory;

    protected $table = 'materials_issue2production';

    protected $fillable = [
        'voucher_no',
        'date',
    ];

    public function masterMaterials(): HasMany
    {
        return $this->hasMany(MasterMaterials::class, 'voucher_no', 'voucher_no');
    }
}
