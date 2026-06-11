<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\CostCenter;
use App\Models\SalesDespatchDetails;
use App\Models\SalesItemsVariousInformation;
use App\Models\SalesOrderDetails;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\AllVoucher;
use App\Models\LedgerPermission;
use App\Models\VoucherType;
use App\Models\Unit;
use App\Models\SalesOrder;
use App\Models\SalesItems;
use App\Models\StockItemPermission;
use App\Models\SalesOrderItems;
use App\Models\StockItem;
use App\Models\Receipt;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Storage;

class ApiController extends Controller
{
    public function sendResponse($result, $message)
    {
    	$response = [
            'status'    => true,
            'message'   => $message,
            'data'      => $result,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'status'    => false,
            'message'   => $errorMessages,
            'data'      => null,
        ];


        // if(!empty($errorMessages)){
        //     $response['data'] = $errorMessages;
        // }


        return response()->json($response, $code);
    }
    public function login(Request $request)
    {

        $validation = Validator::make($request->all(),
        [ 
            'email' => ['required'],
            'password' => ['required','min:8'],
        ]);

        if($validation->fails())
        {
            return $this->sendError('error', 'All field is required',400);
        }

        if (Auth::attempt(['email'=>$request->email, 'password'=>$request->password]) or Auth::attempt(['mobile'=>$request->email, 'password'=>$request->password])) {
            // if(auth()->user()->status == 1 && auth()->user()->role_id==9 )
            // {
                // if (auth()->user()->role_id) 
                // {
                    $user = Auth::user(); 
                    $user->token =  $user->createToken('tally-api')->accessToken; 
                    if (Auth::user()->hasPermissionTo('Sales Order Approve')) {
                        $user->approve_permission =  1;
                    }else{
                       $user->approve_permission =  0; 
                    }
                    $success= $user;
           
                    return $this->sendResponse($success, 'User login successfully.');
                // }else {
                //     return $this->sendError('error', 'You Dont have any user role',400);  
                // }
            // }else{
            //     return $this->sendError('error', 'Invalid user..!',400);
            // }

        } else {
            return $this->sendError('error', 'Email or Phone or Password is invalid',400);
        }
    }

    public function changePassword(Request $request)
    {

        $validation = Validator::make($request->all(),
        [ 
            'user_id' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validation->fails())
        {
            return $this->sendError('error', 'All field is required',400);
        }

        $user=User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();
           
        return $this->sendResponse([], 'Password change successfully.');
                
    }

    public function saleDelete(Request $request){
        if(empty($request->id)){
            return $this->sendError('error', 'Sales ID field is required',400);
        }
        if(SalesOrder::where('id',$request->id)->where('is_tally_synced','!=', 2)->delete()){
            SalesItems::where('sales_order_id', $request->id)->delete();
            SalesOrderDetails::where('sales_order_id', $request->id)->delete();
            SalesDespatchDetails::where('sales_order_id', $request->id)->delete();
            SalesItemsVariousInformation::where('sales_order_id', $request->id)->delete();
            return $this->sendResponse([], 'Delete successfully.');
        }
        return $this->sendError('error', 'Not Deleted!',400);
    }

    public function receiptDelete(Request $request){
        if(empty($request->id)){
            return $this->sendError('error', 'Receipt ID field is required',400);
        }
        if(Receipt::where('id',$request->id)->where('is_tally_synced','!=', 2)->delete()){
            return $this->sendResponse([], 'Delete successfully.');
        }
        return $this->sendError('error', 'Not Deleted!',400);
    }

    public function changeStatus(Request $request) {
        if(empty($request->id)){
            return $this->sendError('error', 'Sales ID field is required',400);
        }
        $id = $request->id;
        $saleOrder = SalesOrder::findOrFail($id);
        $saleOrder->is_tally_synced = 1 ;
        $saleOrder->save();
        return $this->sendResponse([], 'Approved successfully.');
    }


	public function itemsCreateOrUpdate(Request $requests){
		foreach ($requests->all() as $key => $request) {
			$data = array(
                "name"=>$request['name'],
                "parent"=>$request['parent'],
                "category"=>$request['category'],
                "taxClassificationName"=>$request['taxClassificationName'],
                "costingMethod"=>$request['costingMethod'],
                "valuationMethod"=>$request['valuationMethod'],
                "baseUnits"=>$request['baseUnits'],
                "additionalUnits"=>$request['additionalUnits'],
                "conversion"=>$request['conversion'],
                "openingBalance"=>$request['openingBalance'],
                "openingValue"=>$request['openingValue'],
                "openingRate"=>$request['openingRate']
            );
	        $stockItems = StockItem::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
		}
   	
   		$response = [
            'success' => true,
            'data'    => '',
            'message' => 'Inserted/Updated Successfully',
        ];

        return response()->json($response, 201);
	}
	
	/*public function newSalesOrder() {
        $unit = new Unit();
        $ledger = new Ledger();
        $voucherType = new VoucherType();
        $costCentre = new CostCenter();
        $salesItem = new SalesItems();
        $allUnits = $unit->getAllUnits();
        $ledgerTypes = $ledger->getLedger();
        $voucherTypes = $voucherType->getSalesVoucherTypes();
        $costCentreType = $costCentre->getCostCenter();
        // $salesItemData = StockItem::with('StockItem')->get()->toArray();
        $stockItem = StockItem::pluck('name','id');
        // dd($salesItemData);
        return view('application.sales.create',compact('allUnits','ledgerTypes','voucherTypes','costCentreType','stockItem'));
    }
    
    */
 
    public function ledgers(Request $request){
        if(empty($request->user_id)){
            return $this->sendError('error', 'User field is required',400);
        }

        $info= LedgerPermission::with('Ledger')
                ->where('user_id', $request->user_id)
                // ->orderBy(
                //     Ledger::select('name') 
                //     ->whereColumn('mst_ledger.id','mst_ledger_permission.id'),'asc'
                // )
                ->get();
        $data = [];
        foreach ($info as $val){
            foreach ($val->Ledger as $key=>$value){
                $data[]=array(
                        'id'=>$value->id,
                        'name'=>$value->name,
                        'CreditLimit'=>!empty($value->CreditLimit)?(int) str_replace(',','',$value->CreditLimit):0,
                        // 'CreditLimit'=>!empty($value->CreditLimit)?(int) (str_replace(',','',$value->CreditLimit) + $value->closing_balance):0,
                        'credit_remaining_limit'=>!empty($value->CreditLimit)?(int) (str_replace(',','',$value->CreditLimit) + $value->closing_balance):0
                    );
            }
        }

        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function ledgerReport(Request $request){
        $info=$request->all();
        $s_date=!empty($request->s_date)?$request->s_date:NULL;
        $e_date=!empty($request->e_date)?$request->e_date:NULL;
        if(empty($request->ledger)){
            $response = [
                'success' => false,
                'msg'=>'Please select the Ledger.',
                'data'    => []
            ];
            return response()->json($response, 200);
        }
        
        $ledger= Ledger::all();
        $data['ledger']=null;
        $data['voucher']= AllVoucher::where('LedgerName',$request->ledger)
            ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '>=',$s_date);
            })
            ->when(!empty($e_date), function ($query) use ($e_date) {
                    $query->where('date', '<=',$e_date);
            })
            ->where('VoucherTypeName' , 'not like', "%Sales Order%")
            ->where('VoucherTypeName', 'not like', "%Delivery Challan%")
            ->get();

        if(count($data['voucher'])===0){
            $ledger =  Ledger::where('name',$request->ledger)->first();
            $data['ledger']=  !empty($ledger)?$ledger:null;
        }else{
            $openingBalance= AllVoucher::select(
                "IsOpeningBalance", 
                DB::raw('SUM(CAST(REPLACE(`DrAMT`, ",", "") AS DECIMAL(10, 2))) AS TotalDrAMT'), 
                DB::raw('SUM(CAST(REPLACE(`CrAMT`, ",", "") AS DECIMAL(10, 2))) AS TotalCrAMT')
            )
            ->where('LedgerName',$request->ledger)
            ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '<',$s_date);
            })
            ->where('VoucherTypeName' , 'not like', "%Sales Order%")
            ->where('VoucherTypeName', 'not like', "%Delivery Challan%")
            ->orderBy('Date','ASC')
            ->first();
            if(empty($openingBalance->IsOpeningBalance)){
                $IsOpeningBalance=!empty($data['voucher']->first())?$data['voucher']->first()->IsOpeningBalance:'0Dr';
            }else{
                if(!empty(strpos($openingBalance->IsOpeningBalance, "Dr"))){
                    $IsOpeningBalance=(str_replace(',', '',(str_replace('Dr', '',$openingBalance->IsOpeningBalance)))+$openingBalance->TotalDrAMT)-$openingBalance->TotalCrAMT;
                }

                if(!empty(strpos($openingBalance->IsOpeningBalance, "Cr"))){
                    $IsOpeningBalance=$openingBalance->TotalDrAMT-(str_replace(',', '',(str_replace('Cr', '',$openingBalance->IsOpeningBalance)))+$openingBalance->TotalCrAMT);
                }
                $IsOpeningBalance=round(abs($IsOpeningBalance),2).(strpos($IsOpeningBalance, "-")===0?'Cr':'Dr');
            }

            // clossingBalance
            $clossingBalance= AllVoucher::select(
                    "IsClosingBalance", 
                    DB::raw('SUM(CAST(REPLACE(`DrAMT`, ",", "") AS DECIMAL(10, 2))) AS TotalDrAMT'), 
                    DB::raw('SUM(CAST(REPLACE(`CrAMT`, ",", "") AS DECIMAL(10, 2))) AS TotalCrAMT')
                )
                ->where('LedgerName',$request->ledger)
                ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '>=',$s_date);
                })
                ->when(!empty($e_date), function ($query) use ($e_date) {
                        $query->where('date', '<=',$e_date);
                })
                ->where('VoucherTypeName' , 'not like', "%Sales Order%")
                ->where('VoucherTypeName', 'not like', "%Delivery Challan%")
                ->orderBy('Date','ASC')
                ->first();
            if(empty($clossingBalance->IsClosingBalance)){
                $IsClosingBalance=!empty($AllVoucher->first()->IsClosingBalance)?$AllVoucher->first()->IsClosingBalance:'0Dr';
            }else{
                if(!empty(strpos($IsOpeningBalance, "Dr"))){
                    $IsClosingBalance=(str_replace(',', '',(str_replace('Dr', '',$IsOpeningBalance)))+$clossingBalance->TotalDrAMT)-$clossingBalance->TotalCrAMT;
                }

                if(!empty(strpos($IsOpeningBalance, "Cr"))){
                    $IsClosingBalance=$clossingBalance->TotalDrAMT-(str_replace(',', '',(str_replace('Cr', '',$IsOpeningBalance)))+$clossingBalance->TotalCrAMT);
                }
                $IsClosingBalance=round(abs($IsClosingBalance),2).(strpos($IsClosingBalance, "-")===0?'Cr':'Dr');
            }
 

            $data['voucher']->first()->IsOpeningBalance=$IsOpeningBalance;
            $data['voucher']->first()->IsClosingBalance=$IsClosingBalance;
        }
        
        $response = [
            'success' => true,
            'msg'=>'Data found.',
            'data'    => $data
        ];

        return response()->json($response, 200);
    }

    public function dayBookReport(Request $request) {
        // dd($request);
        $info=$request->all();
        $s_date=!empty($request->s_date)?$request->s_date:date('Y-m-d');
        $e_date=!empty($request->e_date)?$request->e_date:date('Y-m-d');
        
        
        $data= AllVoucher::
            where(function ($query) use ($request) {
                $query->where('VoucherTypeName' , 'like', "%Sales Order%");
                $query->orwhere('VoucherTypeName', 'like', "%Payment%");
                $query->orwhere('VoucherTypeName', 'like', "%Delivery Challan%");
            })
            ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('date', '>=',$s_date);
            })
            ->when(!empty($e_date), function ($query) use ($e_date) {
                    $query->where('date', '<=',$e_date);
            })
            ->get();
            
        $response = [
            'success' => true,
            'msg'=>'Data found.',
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function receiptVoucherTypes(){
        $voucherType = new VoucherType();
        $info = $voucherType->getReceiptVoucherTypes();
        $data = [];
        foreach ($info as $key=>$value){
            $data[]=array(
                    'id'=>$key,
                    'name'=>$value
                );
        }
        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function costCenters(){
        $costCentre = new CostCenter();
        $info = $costCentre->getCostCenter();
        $data = [];
        foreach ($info as $key=>$value){
            $data[]=array(
                    'id'=>$key,
                    'name'=>$value
                );
        }
        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function ledgerBankCash(){
        $ledger = new Ledger();
        $data = Ledger::select('name','id')
                        ->where('parent', 'like', '%Bank%')
                        ->orWhere('parent', 'like', '%Cash%')
                        ->get();
        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function receiptPost(Request $request) {
        // dd($request->all());
        /*VoucherType::where('id',$request->voucherType)
                        ->update(['start_number' => DB::Raw("start_number + 1")]);
        $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix, voucher_prefix2, start_number) AS voucher_no"))
                ->where(['id' => $request->voucherType])
                ->first()->voucher_no;*/
        if(empty($request->bank_chas_name) || $request->bank_chas_name == 0 || empty($request->voucherType) || empty($request->ledger) || $request->ledger == 0 || empty($request->amount) || $request->amount == 0){
            $response = [
                'success' => false,
                'message'    => 'Voucher Type, Party Ledger Name & Bank/Cash Name need to fillup!'
            ];
            return response()->json($response, 200);
        }
        
        $date = $request->date??date('Y-m-d');
        $myRequest = new Request();
        $myRequest->date = $date;
        $myRequest->voucherType = $request->voucherType;

        $homeCon = new HomeController;
        $voucher_no = $homeCon->voucherNoForUse($myRequest);
        
        $attachment = NULL;
        // Attached ========
        
        if(!empty($request->attachment)){
            $image = explode('base64,',$request->attachment);
            $image = end($image);
            $image = str_replace(' ', '+', $image);
            $extension = explode('/', explode(':', substr($request->attachment, 0, strpos($request->attachment, ';')))[1])[1];
            
            $uploadFolder = 'receipt_attached/'.date('Y');
            $fileName = $voucher_no.'_'.time() . '.' . $extension;
            
            $attachment = $uploadFolder . $fileName;

            Storage::disk('public')->put($attachment,base64_decode($image));
        }

        $receiptpost = Receipt::create([
            "date" => $date,
            "voucher_type" => $request->voucherType,
            "ledger_party" => $request->ledger,
            "cost_center" => $request->costCenter,
            "bank_chas_name" => $request->bank_chas_name,
            "voucher_no" => $voucher_no,
            "amount" => $request->amount,
            "payment_mode" => $request->payment_mode,
            "attachment" => $attachment,
            "is_tally_synced" => 1,//no approval process so 1
            "user_id" => $request->user_id,
            "narration" => $request->narration
        ]);
        if($receiptpost){
            $response = [
                'success' => true,
                'message'    => 'Successfully Inserted!'
            ];
        }else{
            $response = [
                'success' => false,
                'message'    => 'Save failed!'
            ];
        }

        return response()->json($response, 200);
    }

    public function receiptList(Request $request){
        $s_date=!empty($request->s_date)?$request->s_date:date('Y-m-d');
        $e_date=!empty($request->e_date)?$request->e_date:date('Y-m-d');
        if(!empty($request->ledger)){
            $ledgerRequest= array($request->ledger);
        }else{
            $ledger= LedgerPermission::with('Ledger')->where('user_id', $request->user_id)->get();
            
            $ledgerRequest=[];
            foreach($ledger as $led){
                foreach($led->Ledger as $val){
                    array_push($ledgerRequest,$val->id);
                }
            }
        }
        
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', $request->user_id)->get();
        
        // $ledger_ids=[];
        // foreach($ledger as $led){
        //     foreach($led->Ledger as $val){
        //         array_push($ledger_ids,$val->id);
        //     }
        // }
        
        // $ledgerRequest= !empty($request->ledger)?array($request->ledger):$ledger_ids;
        
        $data = DB::table('receipt_voucher')
                ->select([
                    'receipt_voucher.id',
                    'receipt_voucher.is_tally_synced as status',
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
                // ->where(['receipt_voucher.is_tally_synced'=>0])
                ->whereIn('receipt_voucher.ledger_party',$ledgerRequest)
                ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('receipt_voucher.date', '>=',$s_date);
                })
                ->when(!empty($e_date), function ($query) use ($e_date) {
                        $query->where('receipt_voucher.date', '<=',$e_date);
                })
                ->orderBy('receipt_voucher.date','DESC')
                ->get();
        // dd($receipts);
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    
    // sales
    public function salesVoucherTypes(){
        $voucherType = new VoucherType();
        $info = $voucherType->getSalesVoucherTypes();
        $data = [];
        foreach ($info as $key=>$value){
            $data[]=array(
                    'id'=>$key,
                    'name'=>$value
                );
        }
        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function units(){
        $unit = new Unit();
        $info = $unit->getAllUnits();
        $data = [];
        foreach ($info as $key=>$value){
            $data[]=array(
                    'id'=>$key,
                    'name'=>$value
                );
        }
        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function stockItems(Request $request){
        if(empty($request->user_id)){
            return $this->sendError('error', 'User field is required',400);
        }

        $info = StockItemPermission::with('StockItem')->where('user_id', $request->user_id)->get();
        
        $data = [];
        foreach ($info as $key=>$value){
            foreach ($value->StockItem as $val){
                $data[]=array(
                        'id'=>$val->id,
                        'name'=>$val->name.' ('.$val->alias.')',
                        'unit'=>$val->baseUnits,
                        'rate'=>$val->standard_price,
                        'quentity'=>0
                    );
            }
        }
        
        $response = [
            'success' => true,
            'data'    => $data
        ];

        return response()->json($response, 200);
    }
    
    public function salesList(Request $request){
        // dd('Test');
        $s_date=!empty($request->s_date)?$request->s_date:date('Y-m-d');
        $e_date=!empty($request->e_date)?$request->e_date:date('Y-m-d');
        if(!empty($request->ledger)){
            $ledgerRequest= array($request->ledger);
        }else{
            $ledger= LedgerPermission::with('Ledger')->where('user_id', $request->user_id)->get();
            
            $ledgerRequest=[];
            foreach($ledger as $led){
                foreach($led->Ledger as $val){
                    array_push($ledgerRequest,$val->id);
                }
            }
        }
        $salesOrders = DB::table('sales_order as So')
                ->select([
                    'So.id',
                    'So.voucher_no',
                    'So.date',
                    'So.due_on',
                    'So.narration',
                    'So.is_tally_synced as status',
                    
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
                ->whereIn('So.ledger_party',$ledgerRequest)
                // ->where(['So.is_tally_synced'=>0])
                ->when(!empty($s_date), function ($query) use ($s_date) {
                    $query->where('So.date', '>=',$s_date);
                })
                ->when(!empty($e_date), function ($query) use ($e_date) {
                        $query->where('So.date', '<=',$e_date);
                })
                ->orderBy('So.date','DESC')
                ->orderBy('So.id','DESC')
                ->get();
            
            // echo "<pre>";
            // print_r($salesOrders);
            // exit;
                
        foreach ($salesOrders as $key=>$row){   
            $salesOrders[$key]->sales_items = DB::table('sales_items as Si')
                ->select([
                    'Si.id',
                    'Si.item_id as item_id',
                    'Sti.name as name',
                    'Si.item_description',
                    
                    'Si.quantity',
                    'U.id as unit_id',
                    'U.name as unit',
                    'Si.rate',
                    // 'Si.rate_unit as price',
                    'Si.discount_percent',
                    // 'Si.discount_amount',
                    'Si.amount_with_tax as amount',
                    'Iv.customized_size',
                    'Iv.cnc_bs',
                    'Iv.cnc_no',
                    'Iv.cnc_colour',
                    'Iv.lacquer_color',
                    'Iv.handle_position',
                    'Iv.under_clear',
                ])
                ->leftJoin('stock_items as Sti', 'Sti.id', '=', 'Si.item_id')
                ->leftJoin('units as U', 'U.id', '=', 'Si.unit')
                ->leftJoin('sales_items_various_information as Iv', 'Iv.sales_items_id', '=', 'Si.id')
                
                ->where(['Si.sales_order_id'=>$row->id])
                ->orderBy('Si.sales_order_id', 'ASC')
                ->get();
            }
        if(empty($salesOrders)){
            return response()->json(['success'=> false,'data'=> []]);
        }
        
        return response()->json(['success'=> true,'data'=> $salesOrders]);
    }
    
    public function salesPost(Request $request) {
        if(empty($request->voucherType)){
            $response = [
                'success' => false,
                'message'    => 'Save failed! Voucher Type is Blank!'
            ];return response()->json($response, 200);
        }
        if(empty($request->ledger) OR $request->ledger == 0){
            $response = [
                'success' => false,
                'message'    => 'Save failed! Ledger Name is Blank!'
            ];return response()->json($response, 200);
        }

        $date = $request->date??date('Y-m-d');
        $myRequest = new Request();
        $myRequest->date = $date;
        $myRequest->voucherType = $request->voucherType;

        $homeCon = new HomeController;
        $voucher_no = $homeCon->voucherNoForUse($myRequest);


        // return response()->json(['success'=> true,'message'=> json_encode($request->all())]);
        // VoucherType::where('id',$request->voucherType)
        //                 ->update(['start_number' => DB::Raw("start_number + 1")]);
        // $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix, voucher_prefix2, start_number) AS voucher_no"))
        //         ->where(['id' => $request->voucherType])
        //         ->first()->voucher_no;

        // if (User::find($request->user_id)->hasPermissionTo('Sales Order Approve')) {
        if($request->approve_permission == 1){
            $is_tally_synced =  1;
        }else{
           $is_tally_synced =  0; 
        }

        $sales=SalesOrder::create([
            "date" => $date,
            "user_id" => $request->user_id,
            "ledger_party" => $request->ledger,
            "voucher_type" => $request->voucherType,
            // "voucher_class" => $request->voucherClass,
            "cost_center" => $request->costCenter,
            "voucher_no" => $voucher_no,
            // "due_on" => $request->dueOn,
            "due_on" => date('Y-m-d', strtotime("+10 days")),
            "is_tally_synced" => $is_tally_synced,
            "narration" => $request->narration,
        ]);

        SalesOrderDetails::create([
            'sales_order_id' => $sales->id,
            'mode_termsofpayment' => $request->mode,
            'other_reference' => $request->otherReferences,
            'termsofdelivery' => $request->termsofdelivery
        ]);

        SalesDespatchDetails::create([
            'sales_order_id' => $sales->id,
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
            
            // $total_amount += $item->amount;
            $salesItem = SalesOrderItems::create([
                "item_id" => $item->item_id,
                "sales_order_id" => $sales->id,
                "item_description" => $item->description,
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
                SalesItemsVariousInformation::create([
                    'sales_order_id' => $sales->id,
                    'sales_items_id' => $salesItem->id,
                    'cnc_bs' => $item->cnc_bs,
                    'cnc_no' => $item->cnc_no,
                    'cnc_colour' => $item->cnc_colour,
                    'handle_position' => $item->handle_position,
                    'under_clear' => $item->under_clear,
                    'customized_size' => $item->customize_size,
                    'lacquer_color' => $item->lacquer_colour,
                ]);
            }
        }

        SalesOrder::where('id', $sales->id)
           ->update([
           'total_amount' => $total_amount,
           'gross_amount' => $total_amount,
        ]);
        
        if($sales){
            $response = [
                'success' => true,
                'message'    => 'Successfully Inserted!'
            ];
        }else{
            $response = [
                'success' => false,
                'message'    => 'Save failed!'
            ];
        }

        return response()->json($response, 200);
    }
    
    /*
    public function userDashboard(Request $request)
  	{
  	    $user_id = $request->user_id;
  	    if(empty($user_id)){
  	        $response = [
                'status' => false,
                'sales' => null,
                'receipt'    => null
            ];
  	    }else{
      	    $today = date('Y-m-d');
      	    $sales = SalesOrder::select(
      	        DB::raw('COUNT(id) as sales_qty'),
      	        DB::raw('SUM(gross_amount) as sales_value')
      	        )
      	        ->where(['date'=>$today,'user_id'=>$user_id])
      	        ->first();
      	    $receipt = Receipt::select(
      	        DB::raw('COUNT(id) as receipt_qty'),
      	        DB::raw('SUM(amount) as receipt_value')
      	        )
      	        ->where(['date'=>$today,'user_id'=>$user_id])
      	        ->first();
    
            $response = [
                'status' => true,
                'sales' => $sales,
                'receipt'    => $receipt
            ];
  	    }
        return response()->json($response, 200);
  	}*/

    public function userDashboard(Request $request)
    {
        $user_id = $request->user_id;
        if(empty($user_id)){
            $response = [
                'status' => false,
                'sales' => null,
                'receipt'    => null
            ];
        }else{
            $today = date('Y-m-d');
            $sales = SalesOrder::select(
                DB::raw('COUNT(id) as sales_qty'),
                DB::raw('SUM(gross_amount) as sales_value')
                )
                ->where(['date'=>$today,'user_id'=>$user_id])
                ->first();
            $receipt = Receipt::select(
                DB::raw('COUNT(id) as receipt_qty'),
                DB::raw('SUM(amount) as receipt_value')
                )
                ->where(['date'=>$today,'user_id'=>$user_id])
                ->first();
            $information = DB::table('general_settings')
                ->select('company_address', 'company_phone','company_email','app_version_code','android_app_version','android_app_version_msg','android_app_link')
                ->first();
            $response = [
                'status' => true,
                'sales' => $sales,
                'receipt' => $receipt,
                'information' => $information
            ];
        }
        return response()->json($response, 200);
    }
  	
    public function voucherNo(Request $request) {
        if(empty($request->voucherType)){
            $response = [
                'status' => false,
                'voucher_no' => '',
            ];
            return response()->json($response, 200);
  	    }

        $myRequest = new Request();
        $myRequest->date = $request->date??date('Y-m-d');
        $myRequest->voucherType = $request->voucherType;

        $homeCon = new HomeController;
        $voucherInfo = $homeCon->voucherNo($myRequest);
        
        /*if((VoucherType::where(['id' => $request->voucherType])->first()->voucher_prefix2) != (date('ym').'-')){
            $AllVoucher=VoucherType::whereIn('id', [1, 2, 3, 4,5,6,7])->update(
            ['voucher_prefix2'=>(date('ym').'-'),
            'start_number'=>0]);
        }

        $voucher_no = VoucherType::select(DB::Raw("CONCAT(voucher_prefix, voucher_prefix2, start_number+1) AS voucher_no"))
                ->where(['id' => $request->voucherType])
                ->first()->voucher_no;*/
                
        $voucher_no = json_decode($voucherInfo)->voucher_no;
        $response = [
            'status' => true,
            'voucher_no' => $voucher_no,
            ];
        return response()->json($response, 200);
    }
    
    public function receiptDetails(Request $request){
        if(empty($request->id)){
            $response = [
                'status' => false,
                'data' => '',
                'message' => 'ID is Null!',
            ];
            return response()->json($response, 200);
  	    }
  	    
        $data= Receipt::with(['Ledger','VoucherType','CostCenter'])
            ->where('id',$request->id)
            ->first();
        $response = [
            'status' => true,
            'data' => $data,
            'message' => ''
            ];
        return response()->json($response, 200);
    }
    
    public function salesDetails(Request $request){
        if(empty($request->id)){
            $response = [
                'status' => false,
                'data' => '',
                'message' => 'ID is Null!',
            ];
  	    }
        
        $data = SalesOrder::with('Ledger','VoucherType','CostCenter','SalesOrderDetails','SalesItems','SalesDespatchDetails','SalesItemsVariousInformation')
            ->where('id',$request->id)
            ->get()
            ->map(function($sales) {

                $salesItems = $sales->SalesItems;

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
                    'cost_center' => optional($sales->CostCenter)->name,
                    'sales_items' => $sales->SalesItems != null ? 
                    $sales->SalesItems->map(function($item) {
                        
                        return [
                            'id' => $item->id,
                            'item_id' => $item->item_id,
                            'voucher_no' => SalesOrder::select('voucher_no')->where('id',$item->sales_order_id)->first()->voucher_no,
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
                            'customized_size'=>optional($item->SalesItemsVariousInformation)->customized_size,
                            'cnc_bs'=>optional($item->SalesItemsVariousInformation)->cnc_bs,
                            'cnc_no'=>optional($item->SalesItemsVariousInformation)->cnc_no,
                            'cnc_colour'=>optional($item->SalesItemsVariousInformation)->cnc_colour,
                            'lacquer_color'=>optional($item->SalesItemsVariousInformation)->lacquer_color,
                            'handle_position'=>optional($item->SalesItemsVariousInformation)->handle_position,
                            'under_clear'=>optional($item->SalesItemsVariousInformation)->under_clear
                        ];
                    }) : null,
                    
                    'mode_termsofpayment' => optional($sales->SalesOrderDetails)->mode_termsofpayment,
                    'other_references' => optional($sales->SalesOrderDetails)->other_reference,
                    'termsofdelivery' => optional($sales->SalesOrderDetails)->termsofdelivery,
                    'despatch_docno' => optional($sales->SalesDespatchDetails)->despatch_docno,
                    'despatched_through' => optional($sales->SalesDespatchDetails)->despatched_through,
                    'destination' => optional($sales->SalesDespatchDetails)->destination ,
                    'bill_lading_lr_rr_no' => optional($sales->SalesDespatchDetails)->bill_lading_lr_rr_no,
                    'motor_vehicle_no' => optional($sales->SalesDespatchDetails)->motor_vehicle_no
                    // Add other fields as needed
                ];
            });
            
            // dd($data);
        // end
        $response = [
            'status' => true,
            'data' => $data,
            'message' => ''
            ];
        return response()->json($response, 200);
    }

    public function updateSalesAPI(Request $request) {
        // try {

        $input = json_encode($request->all());
        $sales=SalesOrder::where('id',$request->id)->where('is_tally_synced','!=', 2)->update([
            // "date" => $request->date,
            "ledger_party" => $request->ledger,
            // "voucher_class" => $request->voucherClass,
            "cost_center" => $request->costCenter,
            // "due_on" => $request->dueOn,
            "narration" => $request->narration,
        ]);

        if(!$sales){
            $response = [
                'status' => true,
                'sales' => [],
                'message' => 'Not updated!'
            ];
            return response()->json($response, 200);
        }

        SalesOrderDetails::where('sales_order_id',$request->id)->update([
            'sales_order_id' => $request->id,
            'mode_termsofpayment' => $request->mode,
            'other_reference' => $request->otherReferences,
            'termsofdelivery' => $request->termsofdelivery
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
            
            // $total_amount += $item->amountwithtax;
            $amount = $item->quantity * $item->rate;
            $damount = 0;
            if($item->discount > 0){$damount = round(($amount * $item->discount)/100, 2);}
            $amountwithtax = $amount - $damount;
            $total_amount += $amountwithtax;
            $salesItem = SalesOrderItems::updateOrCreate(['id' => $item->salesID ?? 0],
                [
                    "item_id" => $item->item_id,
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

            if($request->voucherType == 3){
                SalesItemsVariousInformation::updateOrCreate([
                        'id' => $item->salesItemsVariousInformationID ?? 0
                    ],
                    [
                        'sales_order_id' => $request->id,
                        'sales_items_id' =>  $salesItem->id,
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

        SalesOrder::where('id', $request->id)
            ->update([
                'total_amount' => $total_amount,
                'gross_amount' => $total_amount,
            ]);

        $response = [
            'status' => true,
            'sales' => $sales,
            'message' => 'Successfully updated!'
        ];

        // } catch (\Throwable $th) {
            //throw $th;
            // $response = [
            //     'status' => false,
            //     'data' => '',
            //     'message' => 'Updated failed '.$th
            // ];
        // }
                
        // return redirect('sales/list');

        return response()->json($response, 200);

    }

    public function updateReceiptAPI(Request $request) {
        
        
        $result = Receipt::where('id',$request->id)->where('is_tally_synced','!=', 2)->update([
            "voucher_type" => $request->voucherType,
            "ledger_party" => $request->ledger,
            "cost_center" => $request->costCenter,
            "bank_chas_name" => $request->bank_chas_name,
            "amount" => $request->amount,
            "payment_mode" => $request->payment_mode,
            // "user_id" => $request->user_id,
            "narration" => $request->narration
        ]);
        
        // dd($result);

        if($result){
            $status = 'true';
            $msgtype='success';
            $msg='Receipt update successfully.';
        }else{
            $status = 'false';
            $msgtype='error';
            $msg='Receipt update unsuccessfully.';
        }

        $response = [
            'status' => $status,
            'data' => '',
            'message' => $msg
        ];

        return response()->json($response, 200);    
        
    }

}