<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\CostCenter;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\VoucherType;
use App\Models\Unit;
use App\Models\SalesOrder;
use App\Models\Receipt;
use App\Models\SalesItems;
use App\Models\SalesOrderItems;
use App\Models\LedgerPermission;
use App\Models\StockItem;
use File;
use Storage;
use Auth;

class ReceiptController extends Controller
{
    public function newReceipt() {
        $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $voucherType = new VoucherType();
        $costCentre = new CostCenter();
        // $salesItem = new SalesItems();
        $ledgerTypes = $ledger;
        $ledgerBankCash = Ledger::select('name','id')
                        ->where('parent', 'like', '%Bank%')
                        ->orWhere('parent', 'like', '%Cash%')
                        ->get();
        // dd($ledgerTypes);
        $voucherTypes = $voucherType->getReceiptVoucherTypes();
        $costCentreType = $costCentre->getCostCenter();
        // $salesItemData = StockItem::with('StockItem')->get()->toArray();
        
        // dd($salesItemData);
        return view('application.receipt.create',compact('ledgerTypes','voucherTypes','costCentreType','ledgerBankCash'));
    }

    

    public function newReceiptStore(Request $request) {
        // dd($request->all());
        VoucherType::where('id',$request->voucherType)
                        ->update(['start_number' => DB::Raw("start_number + 1")]);
        $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix, voucher_prefix2, start_number) AS voucher_no"))
                ->where(['id' => $request->voucherType])
                ->first()->voucher_no;
        
        $attachment = NULL;
        // Attached ========
        if(!empty($request->file('attachment'))){
            $attachment = $request->file('attachment');
            $fileName = $voucher_no.'_'.time() . '.' . $attachment->getClientOriginalExtension();
            $uploadFolder = 'receipt_attached/'.date('Y');
            $attachment = $attachment->storeAs($uploadFolder, $fileName, 'public');
        }

        Receipt::create([
            "date" => date('Y-m-d'),
            "voucher_type" => $request->voucherType,
            "ledger_party" => $request->ledger,
            "cost_center" => $request->costCenter,
            "bank_chas_name" => $request->bank_chas_name,
            "voucher_no" => $voucher_no,
            "amount" => $request->amount,
            "payment_mode" => $request->payment_mode,
            "attachment" => $attachment,
            "is_tally_synced" => 1,//no approval process so 1
            "user_id" => Auth::user()->id,
            "narration" => $request->narration
        ]);
        
        return redirect('receipt/list');
    }


    public function receiptList(Request $request){
        
        $info=$request->all() ?? [];
        $s_date=!empty($request->s_date)?$request->s_date:date('Y-m-d');
        $e_date=!empty($request->e_date)?$request->e_date:date('Y-m-d');
        $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        
        $ledger_ids=[];
        foreach($ledger as $led){
            foreach($led->Ledger as $val){
                array_push($ledger_ids,$val->id);
            }
        }
        
        $ledgerRequest= !empty($request->ledger)?array($request->ledger):$ledger_ids;
        

        $receipts= Receipt::with(['Ledger','VoucherType','CostCenter'])
            ->whereIn('ledger_party',$ledgerRequest)
            ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '>=',$s_date);
            })
            ->when(!empty($e_date), function ($query) use ($e_date) {
                    $query->where('date', '<=',$e_date);
            })
            ->orderBy('date','DESC')
            ->orderBy('id','DESC')
            ->paginate(10);
        return view('application.receipt.list', compact('receipts','ledger','info'));
    }

    public function recEdit(Request $request) {
        if ($request->isMethod('post'))
        {
            $result=Receipt::where('id',$request->id)->where('is_tally_synced','!=', 2)->update([
                "voucher_type" => $request->voucherType,
                "ledger_party" => $request->ledger,
                "cost_center" => $request->costCenter,
                "bank_chas_name" => $request->bank_chas_name,
                "amount" => $request->amount,
                "payment_mode" => $request->payment_mode,
                "user_id" => Auth::user()->id,
                "narration" => $request->narration
            ]);

            if($result){
                $msgtype='success';
                $msg='Receipt update successfully.';
            }else{
                $msgtype='error';
                $msg='Receipt update unsuccessfully.';
            }

            return redirect('receipt/list')->with($msgtype,$msg);
        }

        $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $voucherType = new VoucherType();
        $costCentre = new CostCenter();
        // $salesItem = new SalesItems();
        $ledgerTypes = $ledger;
        $ledgerBankCash = Ledger::select('name','id')
                        ->where('parent', 'like', '%Bank%')
                        ->orWhere('parent', 'like', '%Cash%')
                        ->get();
        // dd($ledgerTypes);
        $voucherTypes = $voucherType->getReceiptVoucherTypes();
        $costCentreType = $costCentre->getCostCenter();
        // $salesItemData = StockItem::with('StockItem')->get()->toArray();

        $existingdata= Receipt::with(['Ledger','VoucherType','CostCenter'])
            ->where('id',$request->id)
            ->first();
        
        // dd($data->narration);
        return view('application.receipt.edit',compact('ledgerTypes','voucherTypes','costCentreType','ledgerBankCash','existingdata'));
    }
    
    public function details(Request $request){
        if(empty($request->id)){exit('ID is Null!');}
        $data= Receipt::with(['Ledger','VoucherType','CostCenter'])
            ->where('id',$request->id)
            ->first();
            // dd($data);
        return view('application.receipt.details', compact('data'));
    }
    
    public function recDelete(Request $request){
        if(empty($request->id)){exit('ID is Null!');}
        if(Receipt::where('id',$request->id)->where('is_tally_synced','!=', 2)->delete()){exit('Successfully Deleted!');}
        exit('Not Delete!');
    }
    // API
    public function unpostedlist(){
        $receipts = DB::table('receipt_voucher')
                ->select([
                    'receipt_voucher.id',
                    DB::raw('DATE_FORMAT(receipt_voucher.date, "%d-%m-%Y") as date'),
                    'receipt_voucher.voucher_no',
                    'receipt_voucher.amount',
                    'receipt_voucher.payment_mode',
                    DB::raw("CONCAT('storage/',receipt_voucher.attachment) as attachment"),
                    'receipt_voucher.narration',
                    'voucher_types.voucher_name',
                    'mst_ledger.name as ledger_name',
                    'mst_cost_centre.name as cost_centre_name',
                    'LBC.name as bank_chas_name'
                ])
                ->leftJoin('voucher_types', 'voucher_types.id', '=', 'receipt_voucher.voucher_type')
                ->leftJoin('mst_ledger', 'mst_ledger.id', '=', 'receipt_voucher.ledger_party')
                ->leftJoin('mst_cost_centre', 'mst_cost_centre.id', '=', 'receipt_voucher.cost_center')
                ->leftJoin('mst_ledger as LBC', 'LBC.id', '=', 'receipt_voucher.bank_chas_name')
                ->where(['receipt_voucher.is_tally_synced'=>1])
                ->get();
        if(empty($receipts)){
            return response()->json(['success'=> false,'RcvdLedgerEntries'=> []]);
        }
        
        return response()->json(['success'=> true,'RcvdLedgerEntries'=> $receipts]);
    }

    // API
    public function posted2tally(Request $request) {
        $voucher_ids = [];
        foreach($request->voucher_ids as $key=>$row){
            if(!empty($row['id'])){
                $voucher_ids[] = $row['id'];
            }
        }
        // print_r($voucher_ids);exit;
        if(Receipt::whereIn('voucher_no', $voucher_ids)->update(['is_tally_synced'=>2])){
            return response()->json(['success'=> true,'msg'=> 'Updated!']);
        }
        return response()->json(['success'=> false,'msg'=> 'Not Updated!']);
    }

    public function voucherNo($voucherType){
        VoucherType::where('id',$voucherType)
                        ->update(['start_number' => DB::Raw("start_number + 1")]);
        $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix, voucher_prefix2, start_number) AS voucher_no"))
                ->where(['id' => $voucherType])
                ->first()->voucher_no;

        $voucherExist = Receipt::select('voucher_no')
                ->where(['voucher_no' => $voucher_no])
                ->first();

        if($voucherExist){
            return $this->voucherNo($voucherType);
        }

        return $voucher_no;
    }

}
