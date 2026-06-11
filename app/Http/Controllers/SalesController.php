<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

// use App\Models\SalesDetails;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\VoucherType;
use App\Models\Unit;
use App\Models\Sales;
use App\Models\LedgerPermission;
use App\Models\MasterItems;
use App\Models\MasterVoucher;
use App\Models\StockItem;
use App\Models\StockItemPermission;
use App\Models\Voucher;
use App\Models\WorkType;
use Auth;

class SalesController extends Controller
{
    protected $voucherType=3;//Sales

    public function newEntryForm() {
        // $unit = new Unit();
        // $allUnits = $unit->getAllUnits();
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        // $ledger= [];
        // $salesItem = new MasterMasterVoucher();
        $ledgers = Ledger::get();
        $stockItem = StockItem::get();
        // $stockItem = StockItemPermission::with('StockItem')->where('user_id', Auth::user()->id)->get();

        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $this->voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        $voucherType = $this->voucherType;
        // dd($ledgers);
        return view('application.sales.create',compact('ledgers','stockItem','voucher_no','voucherType'));
    }
    
    public function newDesktopEntryForm() {
        // $unit = new Unit();
        // $allUnits = $unit->getAllUnits();
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        // $ledger= [];
        // $salesItem = new MasterMasterVoucher();
        $ledgers = Ledger::get();
        $stockItem = StockItem::get();
        // $stockItem = StockItemPermission::with('StockItem')->where('user_id', Auth::user()->id)->get();
        $items = StockItem::select('id','barcode','name','salesRate as rate','quantity as stock')->get()->toArray();
        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $this->voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        $voucherType = $this->voucherType;
        // dd($ledgers);
        return view('application.sales.create-desktop',compact('ledgers','stockItem','items','voucher_no','voucherType'));
    }
    
    public function ppEntryForm() {
        $ledgers = Ledger::get();
        $stockItem = StockItem::get();
        // $items = StockItem::select('id','barcode','name','salesRate as rate','quantity as stock')->get()->toArray();
        $WorkTypes = WorkType::orderBy('name','ASC')->get()->toArray();
        // dd($WorkTypes);
        $myRequest = new Request();
        $myRequest->date = date('Y-m-d');
        $myRequest->voucherType = $this->voucherType;

        $homeCon = new HomeController;
        $voucher_no_js = $homeCon->voucherNo($myRequest);
        $v = json_decode($voucher_no_js);
        $voucher_no = $v->voucher_no;

        $voucherType = $this->voucherType;
        // dd($ledgers);
        return view('application.sales.pp-create',compact('ledgers','stockItem','WorkTypes','voucher_no','voucherType'));
    }


    public function newStorePp(Request $request) {
        $myRequest = new Request();
        $myRequest->date = $request->date;
        $myRequest->voucherType = $this->voucherType;

        $homeCon = new HomeController;
        $voucher_no = $homeCon->voucherNoForUse($myRequest);
        $extra_discount = $request->extra_discount==''?0:$request->extra_discount;
        $sales=Sales::create([
            "voucher_no" => $voucher_no,
            "voucher_type" => $this->voucherType,
            "debit_head" => $request->ledger,
            "discount_amount" => $extra_discount,
            "paid_amount" => $request->paid_amount??0,
            "narration" => $request->narration_remarks,
            "user_id" => Auth::user()->id,
            "date" => $request->date
        ]);

        $total_amount = 0;

        foreach ($request->item as $key => $item) {
            $item=(object) $item;
            // dd($item);
            $amount = $item->quantity * $item->rate;
            $damount = 0;
            // if($item->discount > 0){$damount = round(($amount * $item->discount)/100, 2);}
            $net_amount = $amount - $damount;
            $total_amount += $net_amount;
            
            MasterItems::create([
                "voucher_no" => $voucher_no,
                "voucher_type" => 2,//Sales
                // "item_id" => $item->name,
                "debit_head" => $request->ledger,
                "credit_head" => 8,//Sales Ledger
                "sales_quantity" => $item->quantity,
                "work_name_id" => $item->work_name,
                "work_type_id" => $item->work_type,
                "size_id" => $item->size,
                "color_id" => $item->color,
                "weight_id" => $item->weight,
                "paper_id" => $item->paper,
                "lamination_id" => $item->lamination,
                "note" => $item->note,
                "rate" => $item->rate,
                "amount" => $amount,
                // "discount_percent" => $item->discount,
                // "discount_amount" => $damount,
                "net_amount" => $net_amount,
                "date" => $request->date
            ]);

            /*StockItem::where('id', $item->name)
                ->update([
                   'quantity' => DB::Raw("quantity - ".$item->quantity),
                ]);*/
        }
            
        Sales::where('id', $sales->id)
           ->update([
               'total_amount' => $total_amount,
               'gross_amount' => $total_amount - $extra_discount,
            ]);

        // For Sales Entry
        $item = [];
        $item[] =   [
            "amount" => $total_amount - $extra_discount,
            "drname" => $request->ledger,
            "crname" => 3, // Sales Ledger
            "note"   => "For Sales"
        ];

        if ($request->paid_amount > 0) {
            // Cash Receive For Sales
            $item[] =   [
                "amount" => $request->paid_amount,
                "drname" => 1,//Cash in Hand
                "crname" => $request->ledger, // Sales Ledger
                "note"   => "Cash Received from Party"
            ];
        }
        // Voucher Entry...
        $myRequest = new Request();
        $myRequest->merge([
            'date' => $request->date,
            'voucherType' => $this->voucherType,
            'voucher_no' => $voucher_no,
            'narration_remarks' => "Cash Received from Party",
            'item' => $item
        ]);

        // dd($myRequest->all());
        $vCon = new VoucherController;
        $vCon->newCreate($myRequest);
        
        // return redirect('sales/list');
        return redirect()->route('sales-details-pp', ['id' => $sales->id]);
        
    }


    public function newStore(Request $request) {
        /*VoucherType::where('id',$request->voucherType)
                        ->update(['start_number' => DB::Raw("start_number + 1")]);
        $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix,voucher_prefix2, start_number) AS voucher_no"))
                ->where(['id' => $request->voucherType])
                ->first()->voucher_no;*/
        $myRequest = new Request();
        $myRequest->date = $request->date;
        $myRequest->voucherType = $this->voucherType;
        // dd($myRequest->all());

        $homeCon = new HomeController;
        $voucher_no = $homeCon->voucherNoForUse($myRequest);
        $extra_discount = $request->extra_discount==''?0:$request->extra_discount;
        $sales=Sales::create([
            "voucher_no" => $voucher_no,
            "voucher_type" => $this->voucherType,
            "debit_head" => $request->ledger,
            "discount_amount" => $extra_discount,
            "paid_amount" => $request->paid_amount??0,
            "narration" => $request->narration_remarks,
            "user_id" => Auth::user()->id,
            "date" => $request->date
        ]);

        $total_amount = 0;

        foreach ($request->item as $key => $item) {
            $item=(object) $item;
            $amount = $item->quantity * $item->rate;
            $damount = 0;
            if($item->discount > 0){$damount = round(($amount * $item->discount)/100, 2);}
            $net_amount = $amount - $damount;
            $total_amount += $net_amount;
            
            MasterItems::create([
                "voucher_no" => $voucher_no,
                "voucher_type" => 2,//Sales
                "item_id" => $item->name,
                "debit_head" => $request->ledger,
                "credit_head" => 8,//Sales Ledger
                "sales_quantity" => $item->quantity,
                "rate" => $item->rate,
                "amount" => $amount,
                "discount_percent" => $item->discount,
                "discount_amount" => $damount,
                "net_amount" => $net_amount,
                "date" => $request->date
            ]);

            StockItem::where('id', $item->name)
                ->update([
                   'quantity' => DB::Raw("quantity - ".$item->quantity),
                ]);
        }
            
        Sales::where('id', $sales->id)
           ->update([
               'total_amount' => $total_amount,
               'gross_amount' => $total_amount - $extra_discount,
            ]);

        // For Sales Entry
        $item = [];
        $item[] =   [
            "amount" => $total_amount - $extra_discount,
            "drname" => $request->ledger,
            "crname" => 3, // Sales Ledger
            "note"   => "For Sales"
        ];

        if ($request->paid_amount > 0) {
            // Cash Receive For Sales
            $item[] =   [
                "amount" => $request->paid_amount,
                "drname" => 1,//Cash in Hand
                "crname" => $request->ledger, // Sales Ledger
                "note"   => "Cash Received from Party"
            ];
        }
        // Voucher Entry...
        $myRequest = new Request();
        $myRequest->merge([
            'date' => $request->date,
            'voucherType' => $this->voucherType,
            'voucher_no' => $voucher_no,
            'narration_remarks' => "Cash Received from Party",
            'item' => $item
        ]);

        // dd($myRequest->all());
        $vCon = new VoucherController;
        $vCon->newCreate($myRequest);
        
        return redirect('sales/list');
        // return redirect()->route('sales-details-pp', ['id' => $sales->id]);
        
    }

    public function updateFormAndStore(Request $request,$id=null){
        if ($request->isMethod('post')){
            $vCon = new VoucherController;

            $data = Sales::with('Voucher.MasterVoucher')
                ->where('id',$id)
                ->get()
                ->toArray()
                ;
            // dd($data[0]['voucher']['master_voucher']);
            foreach ($data[0]['voucher']['master_voucher'] as $k => $v){
                // Balance (Amount) Retrun to Heads
                $vCon->closingBalanceUpdate($v['debit_head'], $v['amount'], '-');
                $vCon->closingBalanceUpdate($v['credit_head'], $v['amount'], '+');
            }
            // dd($data);
            $voucher_no=$data[0]['voucher_no'];

            $total_amount = 0;
            $items=[];

            $ledger=$request->ledger;

            foreach ($request->item as $key => $item) {
                $item=(object) $item;
                $sold_quantity = 0;
                $master_items = MasterItems::select('sales_quantity')
                        ->where(['voucher_no'=>$voucher_no,'item_id'=>$item->name])
                        ->first();
                if($master_items){
                    $sold_quantity = $master_items->sales_quantity;
                }
                // dd($master_items->sales_quantity);
                $amount = $item->quantity * $item->rate;
                $damount = 0;
                if($item->discount > 0){$damount = round(($amount * $item->discount)/100, 2);}
                $net_amount = $amount - $damount;
                $total_amount += $net_amount;

                $mData = MasterItems::updateOrCreate(
                    ['id' => $item->id??0],
                    [
                        "voucher_no" => $voucher_no,
                        "item_id" => $item->name,
                        "debit_head" => $ledger,
                        "credit_head" => 8, // Sales Ledger
                        "sales_quantity" => $item->quantity,
                        "rate" => $item->rate,
                        "amount" => $amount,
                        "discount_percent" => $item->discount,
                        "discount_amount" => $damount,
                        "net_amount" => $net_amount,
                        "date" => $request->date
                    ]
                );

                $itemIds[$key]= $mData->id;
                // Stock Item Adjust
                StockItem::where('id', $item->name)
                    ->update([
                       'quantity' => DB::Raw("quantity - ".($item->quantity-$sold_quantity)),
                    ]);
            }

            // Delete extra items of this invoice
            $ddata= MasterItems::where('voucher_no', $voucher_no)
                ->whereNotIn('id', $itemIds)
                // ->pluck('id')
                ->delete()
                ;

            // dd($ddata);

            $extra_discount = $request->extra_discount==''?0:$request->extra_discount;
            $gross_amount = $total_amount - $extra_discount;
            
            $sales = Sales::where('id', $id)->update([
                "debit_head" => $ledger,
                "discount_amount" => $extra_discount,
                "total_amount" => $total_amount,
                "gross_amount" => $gross_amount,
                "paid_amount" => $request->paid_amount ?? 0,
                "narration" => $request->narration_remarks,
                "user_id" => Auth::id(),
                "date" => $request->date
            ]);
            $salesLedger = 3; // Sales Ledger
            // For Sales Entry
            $mData =   [
                "amount" => $gross_amount,
                "debit_head" => $ledger,
                "credit_head" => $salesLedger,
                // "note"   => "For Sales"
            ];
            // MasterVoucher Update
            MasterVoucher::where(['voucher_no'=>$voucher_no,'credit_head'=>$salesLedger])->update($mData);

            $vCon->closingBalanceUpdate($request->ledger, $gross_amount, '+');
            $vCon->closingBalanceUpdate($salesLedger, $item->amount, '-');

            if ($request->paid_amount > 0) {
                $drname = 1;//Cash in Hand
                // $ledger = $request->ledger; // Sales Ledger
                // Cash Receive For Sales
                $mData = [
                    "amount" => $request->paid_amount,
                    "debit_head" => $drname,
                    "credit_head" => $ledger,
                    "note"   => "Cash Received from Party"
                ];

                MasterVoucher::where(['voucher_no'=>$voucher_no])
                ->whereNot('credit_head',$salesLedger)
                ->update($mData);

                $vCon->closingBalanceUpdate($drname, $request->paid_amount, '+');
                $vCon->closingBalanceUpdate($ledger, $request->paid_amount, '-');
            }else{
                // Delete Cash Receive For Sales
                MasterVoucher::where('voucher_no',$voucher_no)->delete();
            }

            $vData = [
                "date" => $request->date,
                "total_amount" => $gross_amount + $request->paid_amount,
                "user_id" => Auth::id(),
                "updated_at"   => date("Y-m-d H:i:s")
            ];
            Voucher::where('voucher_no',$voucher_no)->update($vData);

            // return redirect('sales/details/'.$id)->with($msgtype,$msg);
            return redirect('sales/details/'.$id);
        }

        $ledgers = Ledger::get();
        $stockItem = StockItem::get();
        $data = $this->salesDetails($request->id);
        // dd($data);
        return view('application.sales.edit',compact('data','ledgers','stockItem'));
    }

    public function removeMasterItems(Request $request){
        if(empty($request->id)){
            return response()->json([
                'message' => 'Id not found.'
            ]);
        }
        $info = MasterItems::where('id',$request->id)->get();
        if(MasterItems::where('id',$request->id)->delete()){
            MasterItemsVariousInformation::where('sales_items_id', $info[0]->item_id)
                ->where('sales_order_id', $info[0]->sales_order_id)->delete();
            return response()->json([
                'message' => 'Successfully deleted.'
            ]);
        }
        return response()->json([
            'message' => 'Delete operation failed.'
        ]);
    }

    public function updateSalesAction(Request $request) {

        // dd($request->all());
                
        // VoucherType::where('id',$request->voucherType)
        //                 ->update(['start_number' => DB::Raw("start_number + 1")]);
                        
        // $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix,voucher_prefix2, start_number) AS voucher_no"))
        //         ->where(['id' => $request->voucherType])
        //         ->first()->voucher_no;

        $sales=Sales::where('id',$request->id)->where('is_tally_synced','!=', 2)->update([
            // "date" => $request->date,
            "ledger_party" => $request->ledger,
            // "voucher_type" => $request->voucherType,
            // "voucher_class" => $request->voucherClass,
            "cost_center" => $request->costCenter,
            // "due_on" => $request->dueOn,
            "user_id" => Auth::user()->id,
            "narration" => $request->narration_remarks,
        ]);

        if(!$sales){
            $msgtype='error';
            $msg='Sales update unsuccessfully.';
            return redirect('sales/list');
        }

        SalesDetails::where('sales_order_id',$request->id)->update([
            'sales_order_id' => $request->id,
            'mode_termsofpayment' => $request->mode,
            'other_reference' => $request->otherReferences,
            'termsofdelivery' => $request->tod
        ]);

        SalesDespatchDetails::where('sales_order_id',$request->id)->update([
            'sales_order_id' => $request->id,
            'despatch_docno' => $request->despatch_doc_no,
            'despatched_through' => $request->despatch_through,
            'destination' => $request->destination,
            'bill_lading_lr_rr_no' => $request->bill_of_ladding,
            'motor_vehicle_no' => $request->motor_vehicle_no,
        ]);

        $total_amount = 0;
        
        foreach ($request->item as $key => $item) {
            $item=(object) $item;
            $amount = $item->quantity * $item->rate;
            $damount = 0;
            if($item->discount > 0){$damount = round(($amount * $item->discount)/100, 2);}
            $amountwithtax = $amount - $damount;
            $total_amount += $amountwithtax;
            
            // $total_amount += $item->amountwithtax;
            $salesItem = MasterItems::updateOrCreate(['id' => $item->salesID ?? 0],
                [
                    "item_id" => $item->name,
                    "sales_order_id" => $request->id,
                    "item_description" => !empty($item->description) ? $item->description : '' ,
                    "quantity" => $item->quantity,
                    "unit" => $item->unit,
                    "rate" => $item->rate,
                    "discount_percent" => $item->discount,
                    "discount_amount" => $damount,
                    "amount_with_tax" => $amountwithtax,
                    "amount" => $amount
                ]);
            

            // dd($item);
            if($request->voucherType == 3){
                MasterItemsVariousInformation::updateOrCreate([
                        'id' => $item->MasterItemsVariousInformationID ?? 0
                    ],
                    [
                        'sales_order_id' => $request->id,
                        'sales_items_id' =>  $item->name,
                        'cnc_bs' => $item->cnc_bs,
                        'cnc_no' => $item->cnc_no,
                        'cnc_colour' => $item->cnc_colour,
                        'handle_position' => $item->handle_position,
                        'under_clear' => $item->under_clear,
                        'customized_size' => $item->customize_size,
                        'lacquer_color' => $item->lacquer_colour,
                    ]
                );
                
            }
        }

        Sales::where('id', $request->id)
            ->update([
               'total_amount' => $total_amount,
               'gross_amount' => $total_amount,
            ]);

        return redirect('sales/list');

    }


    public function salesList(Request $request){
        $info=$request->all() ?? [];
        $s_date=!empty($request->s_date)?$request->s_date:date('Y-m-d');
        $e_date=!empty($request->e_date)?$request->e_date:date('Y-m-d');
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        
        // $ledger_ids=[];
        // foreach($ledger as $led){
        //     foreach($led->Ledger as $val){
        //         array_push($ledger_ids,$val->id);
        //     }
        // }
        
        // $ledgerRequest= !empty($request->ledger)?array($request->ledger):$ledger_ids;
        $ledger= Ledger::get();
        $orders= Sales::with(['Ledger'])
            // ->whereIn('ledger_party',$ledgerRequest)
            ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '>=',$s_date);
            })
            ->when(!empty($e_date), function ($query) use ($e_date) {
                    $query->where('date', '<=',$e_date);
            })
            ->orderBy('date','DESC')
            ->orderBy('id','DESC')
            ->paginate(50);
        // dd($orders);
        return view('application.sales.list', compact('orders','ledger','info'));
    }

    /*public function allSaleOrder() {
        $data = Sales::with('Ledger','VoucherType','CostCenter','SalesDetails','MasterItems','SalesDespatchDetails','MasterItemsVariousInformation')
            ->where('is_tally_synced', '!=', 1)
            ->get()
            ->map(function($sales) {

                $MasterItems = $sales->MasterItems;

                return [
                    'sales_id' => $sales->id,
                    'date' => date('d-m-Y', strtotime($sales->date)),
                    'due_on' => date('d-m-Y', strtotime($sales->due_on)),
                    'voucher_no' => $sales->voucher_no,
                    'total_amount' => $sales->total_amount,
                    'discount_amount' => $sales->discount_amount,
                    'gross_amount' => $sales->gross_amount,
                    'narration' => $sales->narration,
                    'ledger_name' => $sales->Ledger->name,
                    'voucher_type' => $sales->VoucherType->voucher_name,
                    'cost_center' => $sales->CostCenter->name,
                    'sales_items' => $sales->MasterItems != null ? 
                    $sales->MasterItems->map(function($item) {
                        
                        return [
                            'voucher_no' => Sales::select('voucher_no')->where('id',$item->sales_order_id)->first()->voucher_no,
                            'name' => StockItem::select('name')->where('id',$item->item_id)->first()->name,
                            'item_description' => $item->item_description,
                            'quantity' => $item->quantity,
                            'unit' => Unit::select('name')->where('id',$item->unit)->first()->name,
                            'rate' => $item->rate,
                            'rate_unit' => $item->price,
                            'discount_percent' => $item->discount_percent,
                            'discount_amount' => $item->discount_amount,
                            'amount' => $item->amount,
                            'amount_with_tax' => $item->amount_with_tax,
                            'customized_size'=>optional($item->MasterItemsVariousInformation)->customized_size,
                            'cnc_bs'=>optional($item->MasterItemsVariousInformation)->cnc_bs,
                            'cnc_no'=>optional($item->MasterItemsVariousInformation)->cnc_no,
                            'cnc_colour'=>optional($item->MasterItemsVariousInformation)->cnc_colour,
                            'lacquer_color'=>optional($item->MasterItemsVariousInformation)->lacquer_color,
                            'handle_position'=>optional($item->MasterItemsVariousInformation)->handle_position,
                            'under_clear'=>optional($item->MasterItemsVariousInformation)->under_clear
                        ];
                    }) : null,
                    
                    'mode_termsofpayment' => optional($sales->SalesDetails)->mode_termsofpayment,
                    'other_references' => optional($sales->SalesDetails)->other_reference,
                    'termsofdelivery' => optional($sales->SalesDetails)->termsofdelivery,
                    'despatch_docno' => optional($sales->SalesDespatchDetails)->despatch_docno,
                    'despatched_through' => optional($sales->SalesDespatchDetails)->despatched_through,
                    'destination' => optional($sales->SalesDespatchDetails)->destination ,
                    'bill_lading_lr_rr_no' => optional($sales->SalesDespatchDetails)->bill_lading_lr_rr_no,
                    'motor_vehicle_no' => optional($sales->SalesDespatchDetails)->motor_vehicle_no
                    // Add other fields as needed
                ];
            })
            ->toArray();
            
            // foreach ($data as $key=>$row){
            //     foreach ($row['sales_items'] as $key2=>$row2){
            //         $data[$key]['sales_items'][$key2]['voucher_no'] = $row['voucher_no'];
            //     }
            //     //dd($row['voucher_no']);
            // }
            // dd($data);
            // $data['sales_items']['voucher_no'] = $data['voucher_no'];
        return response()->json(['success'=> true,'SalesObj'=> $data]);
    }*/
    
    public function unpostedlist(){
        /*$receipts = DB::table('sales_order as So')
                ->select([
                    'So.id',
                    'So.voucher_no',
                    DB::raw('DATE_FORMAT(So.date, "%d-%m-%Y") as date'),
                    DB::raw('DATE_FORMAT(So.due_on, "%d-%m-%Y") as due_on'),
                    'So.narration',
                    
                    'Lgr.name as ledger_name',
                    'Vt.voucher_name as voucher_type',
                    'Ct.name as cost_center',
                    
                    'So.gross_amount',
                    
                    'Od.mode_termsofpayment',
                    'Od.other_reference as other_references',
                    'Od.termsofdelivery',
                    
                    'Dd.despatch_docno',
                    'Dd.despatched_through',
                    'Dd.destination',
                    'Dd.bill_lading_lr_rr_no',
                    'Dd.motor_vehicle_no',
                ])
                ->leftJoin('mst_ledger as Lgr', 'Lgr.id', '=', 'So.ledger_party')
                ->leftJoin('voucher_types as Vt', 'Vt.id', '=', 'So.voucher_type')
                ->leftJoin('mst_cost_centre as Ct', 'Ct.id', '=', 'So.cost_center')
                ->leftJoin('sales_orderdetails_subdealerdetails as Od', 'Od.sales_order_id', '=', 'So.id')
                ->leftJoin('sales_despatch_details as Dd', 'Dd.sales_order_id', '=', 'So.id')
                ->where(['So.is_tally_synced'=>0])
                ->get();
                */
            
            // echo "<pre>";
            // print_r($receipts);
            // exit;
                
        // foreach ($receipts as $key=>$row){   
        // $receipts[$key]->sales_items = DB::table('sales_items as Si')
        $receipts = DB::table('sales_items as Si')
                ->select([
                    'So.id',
                    'So.voucher_no',
                    DB::raw('DATE_FORMAT(So.date, "%d-%m-%Y") as date'),
                    DB::raw('DATE_FORMAT(So.due_on, "%d-%m-%Y") as due_on'),
                    'So.narration',
                    
                    'Lgr.name as ledger_name',
                    'Vt.voucher_name as voucher_type',
                    'Vt.goddown as goddown',
                    'Vt.sales_account as sales_account',
                    'Ct.name as cost_center',
                    
                    'Sti.name as name',
                    'Si.item_description',
                    
                    'Si.quantity',
                    'U.name as unit',
                    'Si.rate',
                    // 'Si.rate_unit as price',
                    'Si.discount_percent',
                    // 'Si.discount_amount',
                    'Si.amount_with_tax as amount',
                    'So.gross_amount as voucher_amount',
                    'Iv.customized_size',
                    'Iv.cnc_bs',
                    'Iv.cnc_no',
                    'Iv.cnc_colour',
                    'Iv.lacquer_color',
                    'Iv.handle_position',
                    'Iv.under_clear',
                    
                    'Od.mode_termsofpayment',
                    'Od.other_reference as other_references',
                    'Od.termsofdelivery',
                    
                    'Dd.despatch_docno',
                    'Dd.despatched_through',
                    'Dd.destination',
                    'Dd.bill_lading_lr_rr_no',
                    'Dd.motor_vehicle_no',
                ])
                ->leftJoin('sales_order as So', 'Si.sales_order_id', '=', 'So.id')
                ->leftJoin('mst_ledger as Lgr', 'Lgr.id', '=', 'So.ledger_party')
                ->leftJoin('voucher_types as Vt', 'Vt.id', '=', 'So.voucher_type')
                ->leftJoin('mst_cost_centre as Ct', 'Ct.id', '=', 'So.cost_center')
                ->leftJoin('stock_items as Sti', 'Sti.id', '=', 'Si.item_id')
                ->leftJoin('units as U', 'U.id', '=', 'Si.unit')
                ->leftJoin('sales_items_various_information as Iv', 'Iv.sales_items_id', '=', 'Si.id')
                ->leftJoin('sales_orderdetails_subdealerdetails as Od', 'Od.sales_order_id', '=', 'So.id')
                ->leftJoin('sales_despatch_details as Dd', 'Dd.sales_order_id', '=', 'So.id')
                // ->where(['So.id'=>$row->id])
                ->where(['So.is_tally_synced'=>1])
                ->orderBy('Si.sales_order_id', 'ASC')
                ->get();
            // }
        if(empty($receipts)){
            return response()->json(['success'=> false,'SOEntries'=> []]);
        }

       /* 
       $response = [
            'success' => true,
            'SOEntries'    => $receipts
        ];
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        */
        
        return response()->json(['success'=> true,'SOEntries'=> $receipts]);
    }

    public function salesDetails($id){
        //  new 
        $data = Sales::with('Ledger','MasterItems.StockItem')
            ->where('id',$id)
            ->get()
            ->map(function($sales) {

                $MasterItems = $sales->MasterItems;

                return [
                    'sales_id' => $sales->id,
                    'voucher_no' => $sales->voucher_no,
                    'debit_head' => $sales->Ledger->id,
                    'total_amount' => $sales->total_amount,
                    'discount_amount' => $sales->discount_amount,
                    'gross_amount' => $sales->gross_amount,
                    'paid_amount' => $sales->paid_amount,
                    'narration' => $sales->narration,
                    'date' => date('Y-m-d', strtotime($sales->date)),
                    'sales_items' => $sales->MasterItems != null ? 
                    $sales->MasterItems->map(function($item) {
                        return [
                            'id' => $item->id,
                            'item_id' => $item->StockItem->id,
                            'name' => $item->StockItem->name,
                            'sales_quantity' => $item->sales_quantity,
                            'rate' => $item->rate,
                            'amount' => $item->amount,
                            'discount_percent' => $item->discount_percent,
                            'discount_amount' => $item->discount_amount,
                            'net_amount' => $item->net_amount
                        ];
                    }) : null
                ];
            })
            ->toArray();
            // dd($data);
        return $data;
        // end
        $data = Sales::with('Ledger','VoucherType','CostCenter','SalesDetails','MasterItems','SalesDespatchDetails','MasterItemsVariousInformation')
            ->where('id',$id)
            ->get()
            
            ->map(function($sales) {

                $MasterItems = $sales->MasterItems;

                return [
                    'sales_id' => $sales->id,
                    'date' => date('d-m-Y', strtotime($sales->date)),
                    'due_on' => date('d-m-Y', strtotime($sales->due_on)),
                    'voucher_no' => $sales->voucher_no,
                    'total_amount' => $sales->total_amount,
                    'discount_amount' => $sales->discount_amount,
                    'gross_amount' => $sales->gross_amount,
                    'narration' => $sales->narration,
                    'ledger_id' => $sales->Ledger->id,
                    'ledger_name' => $sales->Ledger->name,
                    'voucher_type_id' => $sales->VoucherType->id,
                    'voucher_type' => $sales->VoucherType->voucher_name,
                    'cost_center_id' => optional($sales->CostCenter)->id,
                    'cost_center' => optional($sales->CostCenter)->name,
                    'text' => $sales,
                    'sales_items' => $sales->MasterItems != null ? 
                    $sales->MasterItems->map(function($item) {

                        // echo '<pre>';
                        // print_r($item);
                        
                        return [
                            'id' => $item->id,
                            'item_id' => $item->item_id,
                            'voucher_no' => Sales::select('voucher_no')->where('id',$item->sales_order_id)->first()->voucher_no,
                            'name' => StockItem::select('name')->where('id',$item->item_id)->first()->name,
                            'item_description' => $item->item_description,
                            'quantity' => $item->quantity,
                            'unit_id' => $item->unit,
                            'unit' => Unit::select('name')->where('id',$item->unit)->first()->name,
                            'rate' => $item->rate,
                            'rate_unit' => $item->price,
                            'discount_percent' => $item->discount_percent,
                            'discount_amount' => $item->discount_amount,
                            'amount' => $item->amount,
                            'amount_with_tax' => $item->amount_with_tax,
                            'extraField' => MasterItemsVariousInformation::where('sales_order_id',$item->sales_order_id)
                                ->where('sales_items_id',$item->item_id)->get()->toArray()
                        ];
                    }) : null,
                    
                    'mode_termsofpayment' => optional($sales->SalesDetails)->mode_termsofpayment,
                    'other_references' => optional($sales->SalesDetails)->other_reference,
                    'termsofdelivery' => optional($sales->SalesDetails)->termsofdelivery,
                    'despatch_docno' => optional($sales->SalesDespatchDetails)->despatch_docno,
                    'despatched_through' => optional($sales->SalesDespatchDetails)->despatched_through,
                    'destination' => optional($sales->SalesDespatchDetails)->destination ,
                    'bill_lading_lr_rr_no' => optional($sales->SalesDespatchDetails)->bill_lading_lr_rr_no,
                    'motor_vehicle_no' => optional($sales->SalesDespatchDetails)->motor_vehicle_no
                    // Add other fields as needed
                ];
            });
        // end 

        return $data;
    }

    
    public function details(Request $request){
        if(empty($request->id)){exit('ID is Null!');}
        // $data= Sales::with(['Ledger','VoucherType','CostCenter'])
        //     ->where('id',$request->id)
        //     ->first();
            // dd($data);
            
        //  new 
        $data = Sales::with('Ledger','MasterItems.StockItem.Unit')
            ->where('id',$request->id)
            ->get()
            ->map(function($sales) {

                $MasterItems = $sales->MasterItems;

                return [
                    'sales_id' => $sales->id,
                    'voucher_no' => $sales->voucher_no,
                    'debit_head' => $sales->Ledger->name,
                    'total_amount' => $sales->total_amount,
                    'discount_amount' => $sales->discount_amount,
                    'gross_amount' => $sales->gross_amount,
                    'paid_amount' => $sales->paid_amount,
                    'narration' => $sales->narration,
                    'date' => date('d-m-Y', strtotime($sales->date)),
                    'sales_items' => $sales->MasterItems != null ? 
                    $sales->MasterItems->map(function($item) {
                        return [
                            'name' => $item->StockItem->name,
                            'sales_quantity' => $item->sales_quantity,
                            'unit' => $item->StockItem->Unit->name,
                            'rate' => $item->rate,
                            'amount' => $item->amount,
                            'discount_amount' => $item->discount_amount,
                            'net_amount' => $item->net_amount
                        ];
                    }) : null
                ];
            })
            ->toArray();
            
            // dd($data);
        // end
        return view('application.sales.details', compact('data'));
    }

    
    public function detailsPp(Request $request){
        if(empty($request->id)){exit('ID is Null!');}
        //  new 
        $data = Sales::with([
                'Ledger',
                'MasterItems.WorkName',
                'MasterItems.WorkType',
                'MasterItems.Size',
                'MasterItems.Color',
                'MasterItems.Weight',
                'MasterItems.Paper',
                'MasterItems.Lamination',
            ])
            ->where('id',$request->id)
            ->get()->toArray();
            /*->map(function($sales) {

                $MasterItems = $sales->MasterItems;

                return [
                    'sales_id' => $sales->id,
                    'voucher_no' => $sales->voucher_no,
                    'debit_head' => $sales->Ledger->name,
                    'total_amount' => $sales->total_amount,
                    'discount_amount' => $sales->discount_amount,
                    'gross_amount' => $sales->gross_amount,
                    'paid_amount' => $sales->paid_amount,
                    'narration' => $sales->narration,
                    'date' => date('d-m-Y', strtotime($sales->date)),
                    'sales_items' => $sales->MasterItems != null ? 
                    $sales->MasterItems->map(function($item) {
                        return [
                            'name' => $item->StockItem->name,
                            'sales_quantity' => $item->sales_quantity,
                            'unit' => $item->StockItem->Unit->name,
                            'rate' => $item->rate,
                            'amount' => $item->amount,
                            'discount_amount' => $item->discount_amount,
                            'net_amount' => $item->net_amount
                        ];
                    }) : null
                ];
            })
            ->toArray();*/
            
            // dd($data);
        // end
        return view('application.sales.details-pp', compact('data'));
    }
    
    public function salDelete(Request $request){
        if(empty($request->id)){exit('ID is Null!');}
        $info = Sales::where('id',$request->id)->first();
        if(Sales::where('id',$request->id)->where('is_tally_synced','!=', 2)->delete()){
            MasterItems::where('sales_order_id', $request->id)->delete();
            SalesDetails::where('sales_order_id', $request->id)->delete();
            SalesDespatchDetails::where('sales_order_id', $request->id)->delete();
            MasterItemsVariousInformation::where('sales_order_id', $request->id)->delete();

            // Delete Log ===
            $myRequest = new Request();
            $myRequest->voucher_no = $info->voucher_no;
            $myRequest->voucher_type = 1;//1:for Sales

            $homeCon = new HomeController;
            $homeCon->voucherDeleteLog($myRequest);
            // ---

            exit('Successfully Deleted!');
        }
        exit('Not Delete!');
    }

    public function postedSales2tally(Request $request) {
        // $voucher_ids = explode(',',$request->voucher_ids);//multi voucher nos.
        $voucher_ids = [];
        foreach($request->voucher_ids as $key=>$row){
            if(!empty($row['id'])){
                $voucher_ids[] = $row['id'];
            }
        }
        // dd($voucher_ids);
        
        if(Sales::whereIn('voucher_no', $voucher_ids)->update(['is_tally_synced'=>2])){
            return response()->json(['success'=> true,'msg'=> 'Updated!']);
        }
        return response()->json(['success'=> false,'msg'=> 'Not Updated!']);
    }
    
    public function changeStatus(Request $request) {
        $id = $request->id;
        $saleOrder = Sales::findOrFail($id);
        $saleOrder->is_tally_synced = 1 ;
        $saleOrder->save();
        return redirect('sales/list');
    }

}
