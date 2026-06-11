<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Model;

class MasterItems extends Model
{
    use HasFactory;

    protected $table = 'master_items';

    public function StockItem(): hasOne {
        return $this->hasOne(StockItem::class,'id','item_id');
    }

    public function WorkName(): hasOne {
        return $this->hasOne(WorkName::class,'id','work_name_id');
    }

    public function WorkType(): hasOne {
        return $this->hasOne(WorkType::class,'id','work_type_id');
    }

    public function Size(): hasOne {
        return $this->hasOne(MasterSize::class,'id','size_id');
    }

    public function Color(): hasOne {
        return $this->hasOne(MasterColor::class,'id','color_id');
    }

    public function Weight(): hasOne {
        return $this->hasOne(MasterWeight::class,'id','weight_id');
    }

    public function Paper(): hasOne {
        return $this->hasOne(MasterPaper::class,'id','paper_id');
    }

    public function Lamination(): hasOne {
        return $this->hasOne(MasterLamination::class,'id','lamination_id');
    }

    protected $fillable = [
		"voucher_no",
		"voucher_type",
		"item_id",
		"debit_head",
		"credit_head",
		"purchase_quantity",
		"sales_quantity",
		"work_name_id",
		"work_type_id",
		"size_id",
		"color_id",
		"weight_id",
		"paper_id",
		"lamination_id",
		"note",
		"unit",
		"rate",
		"amount",
		"discount_percent",
		"discount_amount",
		"net_amount",
		"date"
    ];

}