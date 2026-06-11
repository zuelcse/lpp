<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class LedgerPermission extends Model
{
    use HasFactory;

    protected $table = 'mst_ledger_permission';

    
    public function Ledger(): HasMany {
        return  $this->hasMany(Ledger::class,'parent','ledger_group');
    }
}
