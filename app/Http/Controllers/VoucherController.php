<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\CostCenter;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\VoucherType;
use App\Models\Voucher;
use App\Models\MasterVoucher;
use App\Models\Unit;
use App\Models\SalesOrder;
use App\Models\Receipt;
use App\Models\Sales;
use App\Models\Purchase;
use App\Models\SalesItems;
use App\Models\SalesOrderItems;
use App\Models\LedgerPermission;
use App\Models\StockItem;
use File;
use Storage;
use Auth;
use Route;

class VoucherController extends Controller
{
    // protected $voucherType=2;//4:Receipt

    public function paymentCash() {
        $voucherType = 9;//Cash Payment
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([],[30]);
        $crLedgers= $ledger->allLedgers([30],[]);

        // dd($crLedgers);
        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        return view('application.voucher.payment.create-cash',compact('crLedgers','drLedgers','voucher_no','voucherType'));
    }

    public function receiveCash() {
        $voucherType = 10;//Cash Receive
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([30],[]);
        $crLedgers= $ledger->allLedgers([],[30]);

        // $drLedgers= $ledger->cashLedgers();
        // $crLedgers= $ledger->allLedgers();

        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        return view('application.voucher.receive.create-cash',compact('drLedgers','crLedgers','voucher_no','voucherType'));
    }

    public function paymentBank() {
        $voucherType = 11;//Bank Payment
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([],[31]);
        $crLedgers= $ledger->allLedgers([31],[]);
        // dd($partyLedgers);
        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        return view('application.voucher.payment.create-bank',compact('crLedgers','drLedgers','voucher_no','voucherType'));
    }

    public function receiveBank() {
        $voucherType = 12;//Bank Receive
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([31],[]);
        $crLedgers= $ledger->allLedgers([],[31]);
        // dd($partyLedgers);
        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        return view('application.voucher.receive.create-bank',compact('drLedgers','crLedgers','voucher_no','voucherType'));
    }

    public function adjustment() {
        $voucherType = 13;//Adjustment
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers();
        $crLedgers= $ledger->allLedgers();

        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        return view('application.voucher.adjustment',compact('drLedgers','crLedgers','voucher_no','voucherType'));
    }

    public function newReceiveCash(Request $request) {
        if(isset($request->voucher_no) && !empty($request->voucher_no)){
            // From Purchase & Sales
            $voucher_no = $request->voucher_no;
        }else{
            $myRequest = new Request();
            $myRequest->merge([
                'date' => $request->date,
                'voucherType' => $request->voucherType,
            ]);
            // dd($myRequest->all());
            $homeCon = new HomeController;
            $voucher_no = $homeCon->voucherNoForUse($myRequest);
        }

        $voucherData = Voucher::create([
            "date" => $request->date,
            "voucher_no" => $voucher_no,
            "voucher_type" => $request->voucherType,
            "status" => 1,//no approval process so 1
            "user_id" => Auth::user()->id,
            "narration_remarks" => $request->narration_remarks       
        ]);

        $drname = $request->drname;
        $total_amount = 0;

        foreach ($request->item as $key => $item) {
            $item = (object) $item;
            $total_amount += $item->amount;

            $cheque_no = !isset($item->cheque_no)?NULL:$item->cheque_no;
            $cheque_date = !isset($item->cheque_date)?NULL:$item->cheque_date;
            
            MasterVoucher::create([
                "date" => $request->date,
                "voucher_no" => $voucher_no,
                "voucher_type" => $request->voucherType,
                "debit_head" => $drname,
                "credit_head" => $item->crname,
                "amount" => $item->amount,
                "cheque_no" => $cheque_no,
                "cheque_date" => $cheque_date,
                "note" => $item->note
            ]);

            $this->closingBalanceUpdate($drname, $item->amount, '+');
            $this->closingBalanceUpdate($item->crname, $item->amount, '-');

        }

        Voucher::where('id', $voucherData->id)
            ->update([
               'total_amount' => $total_amount
            ]);

        if(isset($request->voucher_no) && !empty($request->voucher_no)){
            // From Purchase & Sales
            return 1;
        }
        return redirect('voucher/list');
        // return redirect('voucher/details/'.$voucherData->id);
    }

    public function newPaymentCash(Request $request) {
        if(isset($request->voucher_no) && !empty($request->voucher_no)){
            // From Purchase & Sales
            $voucher_no = $request->voucher_no;
        }else{
            $myRequest = new Request();
            $myRequest->merge([
                'date' => $request->date,
                'voucherType' => $request->voucherType,
            ]);
            // dd($myRequest->all());
            $homeCon = new HomeController;
            $voucher_no = $homeCon->voucherNoForUse($myRequest);
        }

        $voucherData = Voucher::create([
            "date" => $request->date,
            "voucher_no" => $voucher_no,
            "voucher_type" => $request->voucherType,
            "status" => 1,//no approval process so 1
            "user_id" => Auth::user()->id,
            "narration_remarks" => $request->narration_remarks       
        ]);

        $crname = $request->crname;
        $total_amount = 0;

        foreach ($request->item as $key => $item) {
            $item = (object) $item;
            $total_amount += $item->amount;

            $cheque_no = !isset($item->cheque_no)?NULL:$item->cheque_no;
            $cheque_date = !isset($item->cheque_date)?NULL:$item->cheque_date;
            
            MasterVoucher::create([
                "date" => $request->date,
                "voucher_no" => $voucher_no,
                "voucher_type" => $request->voucherType,
                "debit_head" => $item->drname,
                "credit_head" => $crname,
                "amount" => $item->amount,
                "cheque_no" => $cheque_no,
                "cheque_date" => $cheque_date,
                "note" => $item->note
            ]);

            $this->closingBalanceUpdate($item->drname, $item->amount, '+');
            $this->closingBalanceUpdate($crname, $item->amount, '-');

        }

        Voucher::where('id', $voucherData->id)
            ->update([
               'total_amount' => $total_amount
            ]);

        if(isset($request->voucher_no) && !empty($request->voucher_no)){
            // From Purchase & Sales
            return 1;
        }
        return redirect('voucher/list');
        // return redirect('voucher/details/'.$voucherData->id);
    }

    public function newCreate(Request $request) {
        // dd($request->all());
        // if(empty($request->credit_head) || $request->credit_head == 0 || empty($request->debit_head) || $request->debit_head == 0 || empty($request->amount) || $request->amount == 0){exit('Party Ledger Name & Bank/Cash Name need to fillup!');  }
        if(isset($request->voucher_no) && !empty($request->voucher_no)){
            // From Purchase & Sales
            $voucher_no = $request->voucher_no;
        }else{
            $myRequest = new Request();
            $myRequest->merge([
                'date' => $request->date,
                'voucherType' => $request->voucherType,
            ]);
            // dd($myRequest->all());
            $homeCon = new HomeController;
            $voucher_no = $homeCon->voucherNoForUse($myRequest);
        }

        $voucherData = Voucher::create([
            "date" => $request->date,
            "voucher_no" => $voucher_no,
            "voucher_type" => $request->voucherType,
            "cheque_no" => $request->cheque_no??NULL,
            "cheque_date" => $request->cheque_date??NULL,
            "status" => 1,//no approval process so 1
            "user_id" => Auth::user()->id,
            "narration_remarks" => $request->narration_remarks       
        ]);

        $total_amount = 0;

        foreach ($request->item as $key => $item) {
            $item = (object) $item;
            $total_amount += $item->amount;

            $cheque_no = !isset($item->cheque_no)?NULL:$item->cheque_no;
            $cheque_date = !isset($item->cheque_date)?NULL:$item->cheque_date;
            
            MasterVoucher::create([
                "date" => $request->date,
                "voucher_no" => $voucher_no,
                "voucher_type" => $request->voucherType,
                "debit_head" => $item->drname,
                "credit_head" => $item->crname,
                "amount" => $item->amount,
                "cheque_no" => $cheque_no,
                "cheque_date" => $cheque_date,
                "note" => $item->note
            ]);

            $this->closingBalanceUpdate($item->drname, $item->amount, '+');
            $this->closingBalanceUpdate($item->crname, $item->amount, '-');

            /*Ledger::where('id', $item->drname)
            ->update([
                'closing_balance' => DB::raw('closing_balance + '.$item->amount)
            ]);

            Ledger::where('id', $item->crname)
            ->update([
                'closing_balance' => DB::raw('closing_balance - '.$item->amount)
            ]);*/
        }

        Voucher::where('id', $voucherData->id)
            ->update([
               'total_amount' => $total_amount
            ]);

        if(isset($request->voucher_no) && !empty($request->voucher_no)){
            // From Purchase & Sales
            return 1;
        }
        return redirect('voucher/list');
        // return redirect('voucher/details/'.$voucherData->id);
    }

    public function closingBalanceUpdate($id = 0, $amount = 0, $operator = null){
        $amount = $amount > 0? $amount : 0;
        $operator = in_array($operator, ['+', '-']) ? $operator : '+';
        Ledger::where('id', $id)
            ->update([
                'closing_balance' => DB::raw("closing_balance {$operator} {$amount}")
            ]);

        return true;
    }

    public function subClosingBalance($id = 0, $amount = 0){
        Ledger::where('id', $id)
            ->update([
                'closing_balance' => DB::raw('closing_balance - '.$amount)
            ]);
        return true;
    }

    public function voucherList(Request $request){
      // dd(Route::currentRouteName());
        
        $info=$request->all() ?? [];
        $s_date=!empty($request->s_date)?$request->s_date:date('Y-m-d');
        $e_date=!empty($request->e_date)?$request->e_date:date('Y-m-d');
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $ledger= Ledger::get();
        
        $ledger_ids=[];
            foreach($ledger as $val){
                array_push($ledger_ids,$val->id);
            }
        
        $ledgerRequest= !empty($request->ledger)?array($request->ledger):$ledger_ids;
        
        $vouchers= Voucher::with(['MasterVoucher','MasterVoucher.CreditLedger','MasterVoucher.DebitLedger','VoucherType'])
            ->where('status','!=',-1)//-1:SoftDeleted
            // ->whereIn('credit_head',$ledgerRequest)
            ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '>=',$s_date);
            })
            ->when(!empty($e_date), function ($query) use ($e_date) {
                    $query->where('date', '<=',$e_date);
            })
            ->orderBy('date','DESC')
            ->orderBy('id','DESC')
            ->paginate(50);
            // dd($vouchers);
        return view('application.voucher.list', compact('vouchers','ledger','info'));
    }

    public function details(Request $request){
        if(empty($request->id)){exit('ID is Null!');}
        $data= Voucher::with(['MasterVoucher','MasterVoucher.CreditLedger','MasterVoucher.DebitLedger','VoucherType'])
            ->where('id',$request->id)
            ->first();
            // dd($data); 
        return view('application.voucher.details', compact('data'));
    }

    public function voucherEdit(Request $request) {
        if(empty($request->id)){exit('ID is Null!');}
        $data = Voucher::where('id',$request->id)
            ->first();
        // dd($data); 

        $adjs = [11,12,13];

        if($data->voucher_type == 1){//1:Purchase
            $pData = Purchase::where('voucher_no',$data->voucher_no)->first();
            return redirect()->route('purchase-update', ['id' => $pData->id]);
        }else if($data->voucher_type == 3){//3:Sales
            $sData = Sales::where('voucher_no',$data->voucher_no)->first();
            return redirect()->route('sales-update', ['id' => $sData->id]);
        }else if($data->voucher_type == 9){//9:Cash Payment
            return redirect()->route('voucher-paymentcash-update', ['id' => $data->id]);
        }else if($data->voucher_type == 10){//10:Cash Receive
            return redirect()->route('voucher-receivecash-update', ['id' => $data->id]);
        }
        // else if($data->voucher_type == 11){//11:Bank Payment
        //     return redirect()->route('voucher-paymentbank-update', ['id' => $data->id]);
        // }
        // else if($data->voucher_type == 13){//13:Adjustment
        //     return redirect()->route('voucher-adjustment-update', ['id' => $data->id]);
        // }
        else if(in_array($data->voucher_type, $adjs)){//13:Adjustment
            return redirect()->route('voucher-adjustment-update', ['id' => $data->id]);
        }
        exit('This kind of voucher type update not done yet');
    }

    public function paymentCashEditFormAndStore(Request $request, $id=null) {
        if ($request->isMethod('post')){
            // dd($request->all());
            $data = Voucher::with('MasterVoucher')->where('id',$id)
                ->get()
                ->toArray();

            // dd($data);
            $vCon = new VoucherController;

            foreach ($data[0]['master_voucher'] as $k => $v){
                // Balance (Amount) Retrun to Heads
                $vCon->closingBalanceUpdate($v['debit_head'], $v['amount'], '-');
                $vCon->closingBalanceUpdate($v['credit_head'], $v['amount'], '+');
            }
            // dd($data);
            $voucher_no=$data[0]['voucher_no'];

            $total_amount = 0;
            $crname = $request->crname;

            foreach ($request->item as $key => $item) {
                $item=(object) $item;
                $total_amount += $item->amount;

                $mData = MasterVoucher::updateOrCreate(
                    ['id' => $item->id??0],
                    [
                        "date" => $request->date,
                        "voucher_no" => $voucher_no,
                        "voucher_type" => $data[0]['voucher_type'],
                        "debit_head" => $item->drname,
                        "credit_head" => $crname,
                        "amount" => $item->amount,
                        "note" => $item->note
                    ]
                );

                $vCon->closingBalanceUpdate($item->drname, $item->amount, '+');
                $vCon->closingBalanceUpdate($crname, $item->amount, '-');

                $ids[$key]= $mData->id;
            }

            // Delete extra items of this invoice
            $ddata= MasterVoucher::where('voucher_no', $voucher_no)
                ->whereNotIn('id', $ids)
                // ->pluck('id')
                ->delete()
                ;

            // dd($ddata);

            $vData = [
                "date" => $request->date,
                "total_amount" => $total_amount,
                "user_id" => Auth::id(),
                "narration_remarks" => $request->narration_remarks,
                "updated_at"   => date("Y-m-d H:i:s")
            ];
            Voucher::where('id',$id)->update($vData);
            return redirect('voucher/details/'.$id);
        }
        
        // dd($id);
        $data = Voucher::with('MasterVoucher')->where('id',$id)
            ->get()
            ->toArray();
        // dd($data);
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([],[30]);
        $crLedgers= $ledger->allLedgers([30],[]);

        return view('application.voucher.payment.edit-cash',compact('drLedgers','crLedgers','data'));
    }

    public function paymentBankEditFormAndStore(Request $request, $id=null) {
        if ($request->isMethod('post')){
            // dd($request->all());
            $data = Voucher::with('MasterVoucher')->where('id',$id)
                ->get()
                ->toArray();

            // dd($data);
            $vCon = new VoucherController;

            foreach ($data[0]['master_voucher'] as $k => $v){
                // Balance (Amount) Retrun to Heads
                $vCon->closingBalanceUpdate($v['debit_head'], $v['amount'], '-');
                $vCon->closingBalanceUpdate($v['credit_head'], $v['amount'], '+');
            }
            // dd($data);
            $voucher_no=$data[0]['voucher_no'];

            $total_amount = 0;
            $crname = $request->crname;

            foreach ($request->item as $key => $item) {
                $item=(object) $item;
                $total_amount += $item->amount;

                $mData = MasterVoucher::updateOrCreate(
                    ['id' => $item->id??0],
                    [
                        "date" => $request->date,
                        "voucher_no" => $voucher_no,
                        "voucher_type" => $data[0]['voucher_type'],
                        "debit_head" => $item->drname,
                        "credit_head" => $crname,
                        "amount" => $item->amount,
                        "note" => $item->note
                    ]
                );

                $vCon->closingBalanceUpdate($item->drname, $item->amount, '+');
                $vCon->closingBalanceUpdate($crname, $item->amount, '-');

                $ids[$key]= $mData->id;
            }

            // Delete extra items of this invoice
            $ddata= MasterVoucher::where('voucher_no', $voucher_no)
                ->whereNotIn('id', $ids)
                // ->pluck('id')
                ->delete()
                ;

            // dd($ddata);

            $vData = [
                "date" => $request->date,
                "total_amount" => $total_amount,
                "user_id" => Auth::id(),
                "narration_remarks" => $request->narration_remarks,
                "updated_at"   => date("Y-m-d H:i:s")
            ];
            Voucher::where('id',$id)->update($vData);
            return redirect('voucher/details/'.$id);
        }
        
        // dd($id);
        $data = Voucher::with('MasterVoucher')->where('id',$id)
            ->get()
            ->toArray();
        dd($data);
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([],[30]);
        $crLedgers= $ledger->allLedgers([30],[]);

        return view('application.voucher.payment.edit-bank',compact('drLedgers','crLedgers','data'));
    }

    public function receiveCashEditFormAndStore(Request $request, $id=null) {
        if ($request->isMethod('post')){
            // dd($request->all());
            $data = Voucher::with('MasterVoucher')->where('id',$id)
                ->get()
                ->toArray();

            // dd($data);
            $vCon = new VoucherController;

            foreach ($data[0]['master_voucher'] as $k => $v){
                // Balance (Amount) Retrun to Heads
                $vCon->closingBalanceUpdate($v['debit_head'], $v['amount'], '-');
                $vCon->closingBalanceUpdate($v['credit_head'], $v['amount'], '+');
            }
            // dd($data);
            $voucher_no=$data[0]['voucher_no'];

            $total_amount = 0;
            $drname = $request->drname;

            foreach ($request->item as $key => $item) {
                $item=(object) $item;
                $total_amount += $item->amount;

                $mData = MasterVoucher::updateOrCreate(
                    ['id' => $item->id??0],
                    [
                        "date" => $request->date,
                        "voucher_no" => $voucher_no,
                        "voucher_type" => $data[0]['voucher_type'],
                        "debit_head" => $drname,
                        "credit_head" => $item->crname,
                        "amount" => $item->amount,
                        "note" => $item->note
                    ]
                );

                $vCon->closingBalanceUpdate($drname, $item->amount, '+');
                $vCon->closingBalanceUpdate($item->crname, $item->amount, '-');

                $ids[$key]= $mData->id;
            }

            // Delete extra items of this invoice
            $ddata= MasterVoucher::where('voucher_no', $voucher_no)
                ->whereNotIn('id', $ids)
                // ->pluck('id')
                ->delete()
                ;

            // dd($ddata);

            $vData = [
                "date" => $request->date,
                "total_amount" => $total_amount,
                "user_id" => Auth::id(),
                "narration_remarks" => $request->narration_remarks,
                "updated_at"   => date("Y-m-d H:i:s")
            ];
            Voucher::where('id',$id)->update($vData);
            return redirect('voucher/details/'.$id);
        }
        
        // dd($id);
        $data = Voucher::with('MasterVoucher')->where('id',$id)
            ->get()
            ->toArray();
        // dd($data);
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers([30],[]);
        $crLedgers= $ledger->allLedgers([],[30]);

        return view('application.voucher.receive.edit-cash',compact('drLedgers','crLedgers','data'));
    }

    public function adjustmentEditFormAndStore(Request $request, $id=null) {
        if ($request->isMethod('post')){
            // dd($request->all());
            $data = Voucher::with('MasterVoucher')->where('id',$id)
                ->get()
                ->toArray();

            // dd($data);
            $vCon = new VoucherController;

            // dd($data[0]['voucher']['master_voucher']);
            foreach ($data[0]['master_voucher'] as $k => $v){
                // Balance (Amount) Retrun to Heads
                $vCon->closingBalanceUpdate($v['debit_head'], $v['amount'], '-');
                $vCon->closingBalanceUpdate($v['credit_head'], $v['amount'], '+');
            }
            // dd($data);
            $voucher_no=$data[0]['voucher_no'];

            $total_amount = 0;

            foreach ($request->item as $key => $item) {
                $item=(object) $item;
                $total_amount += $item->amount;

                $mData = MasterVoucher::updateOrCreate(
                    ['id' => $item->id??0],
                    [
                        "date" => $request->date,
                        "voucher_no" => $voucher_no,
                        "voucher_type" => $data[0]['voucher_type'],
                        "debit_head" => $item->drname,
                        "credit_head" => $item->crname,
                        "amount" => $item->amount,
                        "note" => $item->note
                    ]
                );

                $vCon->closingBalanceUpdate($item->drname, $item->amount, '+');
                $vCon->closingBalanceUpdate($item->crname, $item->amount, '-');

                $ids[$key]= $mData->id;
            }

            // Delete extra items of this invoice
            $ddata= MasterVoucher::where('voucher_no', $voucher_no)
                ->whereNotIn('id', $ids)
                // ->pluck('id')
                ->delete()
                ;

            // dd($ddata);

            $vData = [
                "date" => $request->date,
                "total_amount" => $total_amount,
                "user_id" => Auth::id(),
                "cheque_no" => $request->cheque_no??NULL,
                "cheque_date" => $request->cheque_date??NULL,
                "narration_remarks" => $request->narration_remarks,
                "updated_at"   => date("Y-m-d H:i:s")
            ];
            Voucher::where('id',$id)->update($vData);
            return redirect('voucher/details/'.$id);
        }
        
        // dd($id);
        $data = Voucher::with('MasterVoucher')->where('id',$request->id)
            ->get()
            ->toArray();
        $title = $data[0]['voucher_type']==11?"Bank Payment":($data[0]['voucher_type']==12?"Bank Receive":($data[0]['voucher_type']==13?"Adjustment":''));
        // dd($title);
        $ledger= new Ledger();
        $drLedgers= $ledger->allLedgers();
        $crLedgers= $ledger->allLedgers();

        return view('application.voucher.adjustment_edit',compact('drLedgers','crLedgers','data','title'));
    }
    
    public function delete(Request $request){
        if(empty($request->id)){exit('ID is Null!');}

        // $info = Receipt::where('id',$request->id)->first();
        $data = Voucher::find($request->id);
        if(Voucher::where('id',$request->id)->update(['status' => -1])){
            MasterVoucher::where('voucher_no',$data->voucher_no)->update(['status' => -1]);
            // Delete Log ===
            /*$myRequest = new Request();
            $myRequest->voucher_no = $info->voucher_no;
            $myRequest->voucher_type = 2;//2:for Receipt

            $homeCon = new HomeController;
            $homeCon->voucherDeleteLog($myRequest);*/
            // ---
            exit('Successfully Deleted!');
        }
        exit('Not Delete!');
    }

}
