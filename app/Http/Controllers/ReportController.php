<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Subgroups;
use App\Models\Ledger;
use App\Models\AllVoucher;
use App\Models\MasterVoucher;
use App\Models\LedgerPermission;
use App\Models\MasterItems;
use App\Models\Category;
use App\Models\StockItem;
use App\Models\Unit;
use PDF;
use Auth;

class ReportController extends Controller
{
    public function index() {
        // $ledger= LedgerPermission::with('Ledger')->where('user_id', Auth::user()->id)->get();
        $group= Group::get();
        $subgroups= Subgroups::get();
        $ledger= Ledger::get();
        $itemgroups= Category::get();
        $items= StockItem::get();
        // dd($ledger);
        $AllVoucher=[];
        $info=[];
        $pdf=0;
        return view('application.report.index',compact('group','subgroups','ledger','itemgroups','items','AllVoucher','info','pdf'));
    }
    
    public function report(Request $request) {
        $info=$request->all();
        // dd($info);
        $s_date=!empty($request->s_date)?$request->s_date:NULL;
        $e_date=!empty($request->e_date)?$request->e_date:NULL;

        if($request->report_name == 'cash_flow') {
            $info['ledger'] = 1;
            $data['ledger'] = Ledger::select('name')->where('id',$info['ledger'])->first();
            $data['info'] = $info;
            $data['data'] = $this->cashFlow($info);
            return view('application.report.cash_flow',compact('data'));
        }elseif($request->report_name == 'ledger_statement') {
            // $info->ledger = 1;
            $data['ledger'] = Ledger::select('name')->where('id',$info['ledger'])->first();
            $data['info'] = $info;
            $data['data'] = $this->cashFlow($info);
            // dd($data);
            return view('application.report.ledger_statement',compact('data','info'));
        }elseif($request->report_name == 'stock_position') {
            $data = $this->stockPosition($info);
            return view('application.report.stock_position',compact('data','info'));
        }elseif($request->report_name == 'sales_statement') {
            $data = $this->salesStatement($info);
            return view('application.report.sales_statement',compact('data','info'));
        }elseif($request->report_name == 'item_history_') {
            $data = $this->itemHistory($info);
            return view('application.report.item_history',compact('data','info'));
        }elseif($request->report_name == 'party_sales') {
            $data['ledger'] = Ledger::select('name')->where('id',$info['ledger'])->first();
            $data['info'] = $info;
            $data['data'] = $this->partySales($info);
            return view('application.report.party_sales',compact('data'));
        }elseif($request->report_name == 'sub_group_statement') {
            $data['sub_group'] = Subgroups::select('alias','name')->where('id',$info['sub_group'])->first();
            $data['info'] = $info;
            $data['data'] = $this->subGroupStatement($info);
            // dd($data);
            return view('application.report.sub_group_statement',compact('data'));
        }elseif($request->report_name == 'main_group_statement') {
            $data['group'] = Group::select('alias','name')->where('id',$info['group'])->first();
            $data['info'] = $info;
            $data['data'] = $this->mainGroupStatement($info);
            // dd($data);
            return view('application.report.main_group_statement',compact('data'));
        }else{
            exit('This report is coming soon!');
        }


        $specificLedger= [];
        $AllVoucher= [];
        $openingBalance=0;
        $IsOpeningBalance=0;
        $IsClosingBalance=0;
        $pdf=0;
        if($request->input('action')=='pdf'){
            $data = [
                'AllVoucher' => $AllVoucher,
                'info' => $info,
                'ledger' => $ledger,
                'specificLedger' => $specificLedger,
                'IsOpeningBalance' => $IsOpeningBalance,
                'IsClosingBalance' => $IsClosingBalance,
                'pdf' => 1
            ];

            $pdf = PDF::loadView('application.report.index', $data);
            return $pdf->stream('document.pdf');
        }
    }

    public function cashFlow($info){
        $ledgerId   = $info['ledger'];
        $from       = $info['s_date'];
        $to         = $info['e_date'];

        $debit_total = MasterVoucher::where('debit_head', $ledgerId)
            ->where('date', '<', $from)
            ->sum('amount');

        $credit_total = MasterVoucher::where('credit_head', $ledgerId)
            ->where('date', '<', $from)
            ->sum('amount');

        $data['openingBalance'] = $debit_total - $credit_total;

        $data['masterData'] = MasterVoucher::with(['DebitLedger', 'CreditLedger'])
        ->where(function($query) use ($ledgerId) {
            $query->where('debit_head', $ledgerId)
                  ->orWhere('credit_head', $ledgerId);
        })
        ->whereBetween('date', [$from, $to])
        ->orderBy('date','ASC')
        ->get()
        ->map(function ($row) use ($ledgerId) {
            if ($row->debit_head == $ledgerId) {
                $particulars = $row->CreditLedger->name ?? 'N/A';
                $debit = $row->amount;
                $credit = 0;
            } else {
                $particulars = $row->DebitLedger->name ?? 'N/A';
                $debit = 0;
                $credit = $row->amount;
            }

            return [
                'id' => $row->id,
                'date' => $row->date,
                'voucher_no' => $row->voucher_no,
                'particulars' => $particulars,
                'debit' => $debit,
                'credit' => $credit,
                'note' => $row->note,
            ];
        })
        // ->toArray()
        ;

        // dd($data);
        return $data;
    }

    public function stockPosition($info){
        $data['data'] = StockItem::select("id","name","quantity","unit")
            ->with([
                'Unit' => function ($q) {
                    $q->select('id','name');
                }
            ])

            // ->withCount('MasterItems')
            ->withSum('MasterItems as total_purchase_qty', 'purchase_quantity')
            ->withSum('MasterItems as total_sales_qty', 'sales_quantity')
            // ->withSum('MasterItems as total_amount', 'amount')
            ->get()

            ->map(function ($row){
                return [
                    'name' => $row->name,
                    'quantity' => $row->quantity,
                    'unit' => $row->Unit->name,
                    'total_purchase_qty' => $row->total_purchase_qty,
                    'total_sales_qty' => $row->total_sales_qty,
                ];
            });


        // dd($data);
        return $data;
    }

    public function salesStatement($info){
        $ledgerId   = $info['ledger'];
        $from       = $info['s_date'];
        $to         = $info['e_date'];

        $data['data'] = StockItem::select('id', 'name', 'unit')
            ->with([
                'Unit' => function ($q) {
                    $q->select('id', 'name');
                },
                'MasterItems' => function ($q) use ($from, $to) {
                    $q->select(
                        'item_id',
                        DB::raw('SUM(sales_quantity) AS qty'),
                        DB::raw('SUM(CASE WHEN sales_quantity > 0 THEN net_amount ELSE 0 END) AS value')
                    )
                    ->whereBetween('date', [$from, $to])
                    ->groupBy('item_id');
                }
            ])
            ->get()
            ->map(function ($row){
                return [
                    'name' => $row->name,
                    'unit' => $row->Unit->name,
                    'sales_qty' => $row->MasterItems->sum('qty'),
                    'sales_value' => $row->MasterItems->sum('value'),
                ];
            })
            ->toArray();


        // dd($data);
        return $data;
    }

    public function itemHistory_($info){
        $ledgerId   = $info['ledger'];
        $from       = $info['s_date'];
        $to         = $info['e_date'];

        $data['masterData'] = MasterVoucher::with(['DebitLedger', 'CreditLedger'])
        ->where(function($query) use ($ledgerId) {
            $query->where('debit_head', $ledgerId)
                  ->orWhere('credit_head', $ledgerId);
        })
        ->whereBetween('date', [$from, $to])
        ->orderBy('date','ASC')
        ->get()
        ->map(function ($row) use ($ledgerId) {
            if ($row->debit_head == $ledgerId) {
                $particulars = $row->CreditLedger->name ?? 'N/A';
                $debit = $row->amount;
                $credit = 0;
            } else {
                $particulars = $row->DebitLedger->name ?? 'N/A';
                $debit = 0;
                $credit = $row->amount;
            }

            return [
                'id' => $row->id,
                'date' => $row->date,
                'voucher_no' => $row->voucher_no,
                'particulars' => $particulars,
                'debit' => $debit,
                'credit' => $credit,
                'note' => $row->note,
            ];
        })
        // ->toArray()
        ;

        // dd($data);
        return $data;
    }

    public function partySales($info){
        $ledgerId   = $info['ledger'];
        $from       = $info['s_date'];
        $to         = $info['e_date'];

        $data = MasterItems::with(['StockItem'])
        ->whereBetween('date', [$from, $to])
        ->where('debit_head', $ledgerId)
        ->orderBy('date','ASC')
        ->get()
        ->map(function ($row){
            return [
                'date' => $row->date,
                'voucher_no' => $row->voucher_no,
                'item_name' => $row->StockItem->name,
                'sales_quantity' => $row->sales_quantity,
                'net_amount' => $row->net_amount
            ];
        })
        ->toArray()
        ;

        // dd($data);
        return $data;
    }

    public function subGroupStatement($info){
        $sub_group  = $info['sub_group'];
        $from       = $info['s_date'];
        $to         = $info['e_date'];

    
        $data = Ledger::where('sub_group_id', $sub_group)
            ->with([
                'DebitMasterVouchersBeforeRange' => function ($q) use ($from, $to) {
                    $q->select('debit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->where("date",'<', $from)
                    ->groupBy('debit_head')
                    ;
                },
                'DebitMasterVouchersInRange' => function ($q) use ($from, $to) {
                    $q->select('debit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->whereBetween('date', [$from, $to])
                    ->groupBy('debit_head')
                    ;
                },
                'CreditMasterVouchersBeforeRange' => function ($q) use ($from, $to) {
                    $q->select('credit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->where("date",'<', $from)
                    ->groupBy('credit_head')
                    ;
                },
                'CreditMasterVouchersInRange' => function ($q) use ($from, $to) {
                    $q->select('credit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->whereBetween('date', [$from, $to])
                    ->groupBy('credit_head')
                    ;
                },
            ])
        // ->with(['StockItem'])
        // ->whereBetween('date', [$from, $to])
        // ->orderBy('date','ASC')
        ->get()
        ->map(function ($row) {
            $opening_debit  = $row->DebitMasterVouchersBeforeRange->sum('amount');
            $opening_credit = $row->CreditMasterVouchersBeforeRange->sum('amount');
            $debit          = $row->DebitMasterVouchersInRange->sum('amount');
            $credit         = $row->CreditMasterVouchersInRange->sum('amount');

            $opening_balance = $opening_debit - $opening_credit;
            $closing_balance = $opening_balance + ($debit - $credit);

            return [
                'id'               => $row->id,
                'alias'            => $row->alias,
                'name'             => $row->name,
                'mobile'           => $row->mobile,
                'opening_balance'  => $opening_balance,
                'debit'            => $debit,
                'credit'           => $credit,
                'closing_balance'  => $closing_balance,
            ];
        })
        ->toArray()
        ;

        // dd($data);
        return $data;
    }

    public function mainGroupStatement($info){
        $group  = $info['group'];
        $from   = $info['s_date'];
        $to     = $info['e_date'];

        $data = Group::with([
                'Subgroups.Ledger.DebitMasterVouchersBeforeRange' => function ($q) use ($from, $to) {
                    $q->select('debit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->where("date",'<', $from)
                    ->groupBy('debit_head')
                    ;
                },
                'Subgroups.Ledger.DebitMasterVouchersInRange' => function ($q) use ($from, $to) {
                    $q->select('debit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->whereBetween('date', [$from, $to])
                    ->groupBy('debit_head')
                    ;
                },
                'Subgroups.Ledger.CreditMasterVouchersBeforeRange' => function ($q) use ($from, $to) {
                    $q->select('credit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->where("date",'<', $from)
                    ->groupBy('credit_head')
                    ;
                },
                'Subgroups.Ledger.CreditMasterVouchersInRange' => function ($q) use ($from, $to) {
                    $q->select('credit_head',
                        DB::raw('SUM(amount) AS amount')
                    )
                    ->whereBetween('date', [$from, $to])
                    ->groupBy('credit_head')
                    ;
                }
            ])
            ->where('id', $group)->get()
            /*->map(function ($row) {
                $opening_debit  = $row->DebitMasterVouchersBeforeRange->sum('amount');
                $opening_credit = $row->CreditMasterVouchersBeforeRange->sum('amount');
                $debit          = $row->DebitMasterVouchersInRange->sum('amount');
                $credit         = $row->CreditMasterVouchersInRange->sum('amount');

                $opening_balance = $opening_debit - $opening_credit;
                $closing_balance = $opening_balance + ($debit - $credit);

                return [
                    'id'               => $row->id,
                    'alias'            => $row->alias,
                    'name'             => $row->name,
                    'mobile'           => $row->mobile,
                    'opening_balance'  => $opening_balance,
                    'debit'            => $debit,
                    'credit'           => $credit,
                    'closing_balance'  => $closing_balance,
                ];
            })*/
            ->map(function($row) {
                return [
                    'id'    => $row->id,
                    'alias' => $row->alias,
                    'name'  => $row->name,
                    'subgroups' => $row->Subgroups != null ? 
                        $row->Subgroups->map(function($subs) {
                        return [
                            'id'    => $subs->id,
                            'alias' => $subs->alias,
                            'name'  => $subs->name,
                            'ledger' => $subs->Ledger != null ? 
                                $subs->Ledger->map(function($item) {
                                    $opening_debit  = $item->DebitMasterVouchersBeforeRange->sum('amount');
                                    $opening_credit = $item->CreditMasterVouchersBeforeRange->sum('amount');
                                    $debit          = $item->DebitMasterVouchersInRange->sum('amount');
                                    $credit         = $item->CreditMasterVouchersInRange->sum('amount');

                                    $opening_balance = $opening_debit - $opening_credit;
                                    $closing_balance = $opening_balance + ($debit - $credit);
                                return [
                                    'id'    => $item->id,
                                    'alias' => $item->alias,
                                    'name'  => $item->name,
                                    'mobile'  => $item->mobile,
                                    'opening_balance'  => $opening_balance,
                                    'debit'  => $debit,
                                    'credit'  => $credit,
                                    'closing_balance'  => $closing_balance
                                ];
                            }) : null,
                        ];
                    }) : null
                ];
            })
            ->toArray();

        // dd($data);
        return $data;
    }

    public function partySales_251015($info){
        $ledgerId   = $info['ledger'];
        $from       = $info['s_date'];
        $to         = $info['e_date'];

        $data = Ledger::select('id', 'name')
            ->with([
                'DebitMasterVouchersInRange' => function ($q)  use ($from, $to){
                    $q->select('voucher_no','debit_head','credit_head','note','date', 'amount')
                    ->with(['DebitLedger', 'CreditLedger'])
                    ->whereBetween('date', [$from, $to])
                    ;
                },
                'CreditMasterVouchersInRange' => function ($q)  use ($from, $to){
                    $q->select('voucher_no','debit_head','credit_head','note','date', 'amount')
                    ->with(['DebitLedger', 'CreditLedger'])
                    ->whereBetween('date', [$from, $to])
                    ;
                },
                'DebitMasterItems' => function ($q) use ($from, $to) {
                    $q->select('voucher_no','item_id','debit_head','credit_head','purchase_quantity','sales_quantity','net_amount'
                    )
                    ->with('StockItem')
                    ->whereBetween('date', [$from, $to])
                    ;
                },
                'CreditMasterItems' => function ($q) use ($from, $to) {
                    $q->select(
                        'voucher_no','item_id','debit_head','credit_head','purchase_quantity','sales_quantity','net_amount'
                    )
                    ->whereBetween('date', [$from, $to])
                    ;
                },
            ])
            ->where('id', $ledgerId)
            ->get()
            ->toArray()
            ;
            dd($data);
        $data['data'] = StockItem::select('id', 'name', 'unit')
            ->with([
                'Unit' => function ($q) {
                    $q->select('id', 'name');
                },
                'MasterItems' => function ($q) use ($from, $to) {
                    $q->select(
                        'item_id',
                        DB::raw('SUM(sales_quantity) AS qty'),
                        DB::raw('SUM(CASE WHEN sales_quantity > 0 THEN net_amount ELSE 0 END) AS value')
                    )
                    ->whereBetween('date', [$from, $to])
                    ->groupBy('item_id');
                }
            ])
            ->get()
            ->map(function ($row){
                return [
                    'name' => $row->name,
                    'unit' => $row->Unit->name,
                    'sales_qty' => $row->MasterItems->sum('qty'),
                    'sales_value' => $row->MasterItems->sum('value'),
                ];
            })
            ->toArray();


        // dd($data);
        return $data;
    }

}
