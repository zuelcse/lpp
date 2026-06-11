<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\VoucherType;
use App\Models\VoucherNumbers;
use App\Models\Receipt;
use App\Models\Voucher;
use App\Models\Purchase;
use App\Models\Ledger;
use App\Models\StockItem;
use App\Models\Sales;
use App\Models\DeletedVoucher;
use App\Models\Subgroups;
use App\Models\Areas;
use App\Models\Terriitorys;

use App\Models\WorkType;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function voucherNo(Request $request) {
        if($request->date){
            $ym = date('ym',strtotime($request->date));
        }else{
            $ym = date('ym');
        }
        $vs = VoucherNumbers::select('id','serial')->where([
                'voucher_type_id' => $request->voucherType,
                'ym' => $ym
            ])->first();
        $sl = 0;
        if($vs){
            $sl = $vs->serial;
            // VoucherNumbers::where('id', $vs->id)->update(['serial'=>$sl]);
        }else{
            $val=[
                'voucher_type_id' => $request->voucherType,
                'ym' => $ym,
                'serial' => $sl
            ]; 
            VoucherNumbers::create($val);
        }

        $prefix = VoucherType::select('voucher_prefix')
                ->where(['id' => $request->voucherType])
                ->first()->voucher_prefix;
        ++$sl;//Show the sirial +1
        $data['voucher_no'] = $prefix.$ym .'-'. $sl;
        return json_encode($data);
    }

    public function voucherNoForUse(Request $request){
        $ym = date('ym',strtotime($request->date));
        $vs = VoucherNumbers::select('id','serial')->where([
                'voucher_type_id' => $request->voucherType,
                'ym' => $ym
            ])->first();
        $sl = 1;
        if($vs){
            $sl = $vs->serial + 1;
            VoucherNumbers::where('id', $vs->id)->update(['serial'=>$sl]);
        }else{
            $val=[
                'voucher_type_id' => $request->voucherType,
                'ym' => $ym,
                'serial' => $sl,
            ];
            VoucherNumbers::create($val);
        }

        $voucher = VoucherType::select('type','voucher_prefix')
                ->where(['id' => $request->voucherType])
                ->first();
        $voucher_no = $voucher->voucher_prefix.$ym.'-'.$sl;

        $myRequest = new Request();
        $myRequest->merge([
                'date' => $request->date,
                'voucherType' => $request->voucherType,
            ]);

        // dd($voucher_no);

        if($voucher->type == 1){
            $voucherExist = Purchase::select('voucher_no')
                    ->where(['voucher_no' => $voucher_no])
                    ->first();
            if($voucherExist){
                return $this->voucherNoForUse($myRequest);
            }
            return $voucher_no;
        }else if($voucher->type == 2){
            $voucherExist = Sales::select('voucher_no')
                    ->where(['voucher_no' => $voucher_no])
                    ->first();
            if($voucherExist){
                return $this->voucherNoForUse($myRequest);
            }
            return $voucher_no;
        }else if($voucher->type == 4){
            $voucherExist = Voucher::select('voucher_no')
                    ->where(['voucher_no' => $voucher_no])
                    ->first();
            if($voucherExist){
                return $this->voucherNoForUse($myRequest);
            }
            return $voucher_no;
        }

        exit('This Voucher Type is not developed yet!, Contact with Developer (+8801738051123)!');
    }

    public function voucherDeleteLog(Request $request){
        DeletedVoucher::create([
            "voucher_no" => $request->voucher_no,
            "voucher_type" => $request->voucher_type,
            "user_id" => Auth::user()->id
        ]);
        return null;
    }

    public function getSubGroups($main_group_id){
        return Subgroups::select('id', DB::raw("CONCAT(alias , ' - ', name) as name"))
                ->where('main_group_id', $main_group_id)
                ->orderBy('name')
                ->pluck('name','id');
    }

    public function getLedgers($group_id){
        return Ledger::select('id', DB::raw("CONCAT(alias , ' - ', name) as name"))
                ->where('sub_group_id', $group_id)
                ->orderBy('name')
                ->pluck('name','id');
    }

    public function getStockItems($group_id){
        return StockItem::select('id', DB::raw("CONCAT(alias , ' - ', name) as name"))
                ->where('category', $group_id)
                ->orderBy('name')
                ->pluck('name','id');
    }

    public function getAreas($region_id){
        return Areas::where('region_id', $region_id)
                    ->orderBy('name')
                    ->pluck('name','id');
    }

    public function getTerriitorys($area_id){
        return Terriitorys::where('area_id', $area_id)
                    ->orderBy('name')
                    ->pluck('name','id');
    }

    public function getWorkTypeVeriations($worktype_id){
        $infos = WorkType::where('id',$worktype_id)->with(['sizes','colors','weights','papers','laminations'])->first();
        
        return response()->json([$infos]);
    }
    
}
