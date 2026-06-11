<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Support\Facades\DB;

class WorkType extends Model
{
    use HasFactory;

    public $table = 'work_types';
    public $timestamp = false;

    protected $fillable = ["name","name_bn"];

    public function sizes():BelongsToMany {
        return $this->belongsToMany(MasterSize::class,'work_type_sizes','work_type_id','root_id');
    }

    public function colors():BelongsToMany{
        return $this->belongsToMany(MasterColor::class,'work_type_colors','work_type_id','root_id');
    }

    public function weights():BelongsToMany{
        return $this->belongsToMany(MasterWeight::class,'work_type_weights','work_type_id','root_id');
    }

    public function papers():BelongsToMany{
        return $this->belongsToMany(MasterPaper::class,'work_type_papers','work_type_id','root_id');
    }

    public function laminations():BelongsToMany{
        return $this->belongsToMany(MasterLamination::class,'work_type_laminations','work_type_id','root_id');
    }

}
