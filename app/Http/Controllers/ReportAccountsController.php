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

class ReportAccountsController extends Controller
{
    // All
    public function rAll() {
        $group= Group::get();
        $subgroups= Subgroups::get();
        $ledger= Ledger::get();
        $itemgroups= Category::get();
        $items= StockItem::get();
        $AllVoucher=[];
        $info=[];
        $pdf=0;
        // dd('RR');
        return view('application.report.index',compact('group','subgroups','ledger','itemgroups','items','AllVoucher','info','pdf'));
    }

    public function ledgerStatement() {
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
        return view('application.report.accounts.ledger_statement_index',compact('group','subgroups','ledger','itemgroups','items','AllVoucher','info','pdf'));
    }

    public function cashflowStatement() {
        // $group= Group::get();
        // $subgroups= Subgroups::get();
        // $ledger= Ledger::get();
        // $itemgroups= Category::get();
        // $items= StockItem::get();
        // dd($ledger);
        $AllVoucher=[];
        $info=[];
        $pdf=0;
        return view('application.report.accounts.cashflow_statement_index',compact('info','pdf'));
    }

    public function maingroupSummary() {
        $group= Group::get();
        // $subgroups= Subgroups::get();
        // $ledger= Ledger::get();
        // $itemgroups= Category::get();
        // $items= StockItem::get();
        // dd($ledger);
        $AllVoucher=[];
        $info=[];
        $pdf=0;
        return view('application.report.accounts.maingroup_summary_index',compact('group','info','pdf'));
    }

    public function periodicPlAccount() {
        $AllVoucher=[];
        $info=[];
        $pdf=0;
        return view('application.report.accounts.periodic_pl_account_index',compact('info','pdf'));
    }

    public function subgroupSummary() {
        $group= Group::get();
        $subgroups= Subgroups::get();
        // $ledger= Ledger::get();
        // $itemgroups= Category::get();
        // $items= StockItem::get();
        // dd($ledger);
        $AllVoucher=[];
        $info=[];
        $pdf=0;
        return view('application.report.accounts.subgroup_summary_index',compact('group','subgroups','info','pdf'));
    }
    
    public function reportAccounts(Request $request) {
        $info=$request->all();
        // dd($info);
        $s_date=!empty($request->s_date)?$request->s_date:NULL;
        $e_date=!empty($request->e_date)?$request->e_date:NULL;

        if($request->report_name == 'cash_flow') {
            $info['ledger'] = 1;
            $data['ledger'] = Ledger::select('name')->where('id',$info['ledger'])->first();
            $data['info'] = $info;
            $data['data'] = $this->cashFlow($info);
            return view('application.report.accounts.cash_flow',compact('data'));
        }elseif($request->report_name == 'ledger_statement') {
            // $info->ledger = 1;
            $data['ledger'] = Ledger::select('name')->where('id',$info['ledger'])->first();
            $data['info'] = $info;
            $data['data'] = $this->cashFlow($info);
            // dd($data);
            return view('application.report.accounts.ledger_statement_report',compact('data','info'));
        }elseif($request->report_name == 'stock_position') {
            return app(\App\Http\Controllers\ReportSalesController::class)->stockPositionReport($request);
        }elseif($request->report_name == 'sales_statement') {
            return app(\App\Http\Controllers\ReportSalesController::class)->salesStatementReport($request);
        }elseif($request->report_name == 'item_history' || $request->report_name == 'item_history_') {
            return app(\App\Http\Controllers\ReportSalesController::class)->stockItemHistoryReport($request);
        }elseif($request->report_name == 'purchase_statement') {
            return app(\App\Http\Controllers\ReportSalesController::class)->purchaseStatementReport($request);
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
            return view('application.report.accounts.sub_group_statement',compact('data'));
        }elseif($request->report_name == 'main_group_statement') {
            $data['group'] = Group::select('alias','name')->where('id',$info['group'])->first();
            $data['info'] = $info;
            $data['data'] = $this->mainGroupStatement($info);
            // dd($data);
            return view('application.report.accounts.main_group_statement',compact('data'));
        }elseif($request->report_name == 'periodic_pl_account') {
            $data['info'] = $info;
            $data['data'] = $this->periodicPlAccountReport($info);
            // dd($data);
            return view('application.report.accounts.periodic_pl_account',compact('data'));
        }elseif($request->report_name == 'balance_sheet') {
            $data['info'] = $info;
            $data['data'] = $this->balanceSheetReport($info);
            return view('application.report.accounts.balance_sheet',compact('data'));
        }elseif($request->report_name == 'raw_materials_ledger') {
            return app(\App\Http\Controllers\ReportMaterialsController::class)->rawMaterialsLedgerReport($request);
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

    public function periodicPlAccountReport($info){
        $from   = $info['s_date'];
        $to     = $info['e_date'];
        // return null;
        $groupIds = [33,35,47,49,50,51,52,53];
        $groups = Group::with([
            'Subgroups.Ledger' => function ($query) use ($from, $to) {

                $query->with([
                    'DebitMasterVouchersInRange' => function ($q) use ($from, $to) {
                        $q->whereBetween('date', [$from, $to])
                          // ->where('status', 1)
                          ;
                    },

                    'CreditMasterVouchersInRange' => function ($q) use ($from, $to) {
                        $q->whereBetween('date', [$from, $to])
                          // ->where('status', 1)
                          ;
                    }
                ]);

            }
        ])
        ->whereIn("id",$groupIds)
        ->get();

        $groupSummary = $groups->map(function ($group) {

            $debit = 0;
            $credit = 0;

            foreach ($group->Subgroups as $subgroup) {

                foreach ($subgroup->Ledger as $ledger) {

                    $debit += $ledger->DebitMasterVouchersInRange
                        ->sum('amount');

                    $credit += $ledger->CreditMasterVouchersInRange
                        ->sum('amount');
                }
            }

            return [
                'group_id'    => $group->id,
                'group_name'  => $group->name,
                'debit'       => $debit,
                'credit'      => $credit,
                'balance'     => $debit - $credit,
            ];
        })->keyBy('group_id');
        // dd($groupSummary);

        /*$data['voucherData'] = MasterVoucher::select("voucher_type", DB::raw('SUM(amount) AS amount'))
                        ->whereBetween('date', [$from, $to])
                        ->whereIn('voucher_type', $vTypes)
                        ->groupBy("voucher_type")
                        ->pluck("amount","voucher_type")
                        ->toArray();*/
        // dd($data);
        
        // dd($data);
        return $groupSummary;
    }

    public function balanceSheetReport($info) {
        $to = !empty($info['e_date']) ? $info['e_date'] : (!empty($info['s_date']) ? $info['s_date'] : date('Y-m-d'));

        $vouchersDr = DB::table('master_voucher')
            ->join('ledgers', 'master_voucher.debit_head', '=', 'ledgers.id')
            ->where('master_voucher.date', '<=', $to)
            ->groupBy('ledgers.main_group_id')
            ->select('ledgers.main_group_id', DB::raw('SUM(master_voucher.amount) as total_debit'))
            ->pluck('total_debit', 'main_group_id')
            ->toArray();

        $vouchersCr = DB::table('master_voucher')
            ->join('ledgers', 'master_voucher.credit_head', '=', 'ledgers.id')
            ->where('master_voucher.date', '<=', $to)
            ->groupBy('ledgers.main_group_id')
            ->select('ledgers.main_group_id', DB::raw('SUM(master_voucher.amount) as total_credit'))
            ->pluck('total_credit', 'main_group_id')
            ->toArray();

        $getDrNet = function($groupIds) use ($vouchersDr, $vouchersCr) {
            if (!is_array($groupIds)) $groupIds = [$groupIds];
            $sum = 0;
            foreach ($groupIds as $gid) {
                $dr = (float)($vouchersDr[$gid] ?? 0);
                $cr = (float)($vouchersCr[$gid] ?? 0);
                $sum += ($dr - $cr);
            }
            return $sum;
        };

        $getCrNet = function($groupIds) use ($vouchersDr, $vouchersCr) {
            if (!is_array($groupIds)) $groupIds = [$groupIds];
            $sum = 0;
            foreach ($groupIds as $gid) {
                $dr = (float)($vouchersDr[$gid] ?? 0);
                $cr = (float)($vouchersCr[$gid] ?? 0);
                $sum += ($cr - $dr);
            }
            return $sum;
        };

        // 1. Non-Current Assets
        $tangibleFixedAssets = $getDrNet([13, 15]) - $getCrNet(14); // Fixed Assets (13) + WIP (15) - Acc Dep (14)
        $deferredExpenses = $getDrNet(16);
        $investment = $getDrNet(17);
        $totalNonCurrentAssets = $tangibleFixedAssets + $deferredExpenses + $investment;

        // 2. Inter Company Current A/C
        $interCompany = $getDrNet(32); // Note 9A (if Dr: positive, if Cr: negative)

        // 3. Current Assets
        // Split Debtors (25) into Trade Debtors (>0) and Advance Against Sales (<0)
        $debtorDrVouchers = DB::table('master_voucher')
            ->join('ledgers', 'master_voucher.debit_head', '=', 'ledgers.id')
            ->where('ledgers.main_group_id', 25)
            ->where('master_voucher.date', '<=', $to)
            ->groupBy('master_voucher.debit_head')
            ->select('master_voucher.debit_head as ledger_id', DB::raw('SUM(master_voucher.amount) as dr_amount'))
            ->pluck('dr_amount', 'ledger_id')
            ->toArray();

        $debtorCrVouchers = DB::table('master_voucher')
            ->join('ledgers', 'master_voucher.credit_head', '=', 'ledgers.id')
            ->where('ledgers.main_group_id', 25)
            ->where('master_voucher.date', '<=', $to)
            ->groupBy('master_voucher.credit_head')
            ->select('master_voucher.credit_head as ledger_id', DB::raw('SUM(master_voucher.amount) as cr_amount'))
            ->pluck('cr_amount', 'ledger_id')
            ->toArray();

        $allDebtorIds = array_unique(array_merge(array_keys($debtorDrVouchers), array_keys($debtorCrVouchers)));
        $tradeDebtors = 0;
        $advanceAgainstSales = 0;
        foreach ($allDebtorIds as $lid) {
            $bal = ($debtorDrVouchers[$lid] ?? 0) - ($debtorCrVouchers[$lid] ?? 0);
            if ($bal > 0) {
                $tradeDebtors += $bal;
            } elseif ($bal < 0) {
                $advanceAgainstSales += abs($bal);
            }
        }

        // Split Creditors (7) into Creditors (>0) and Advance to Suppliers (<0)
        $creditorDrVouchers = DB::table('master_voucher')
            ->join('ledgers', 'master_voucher.debit_head', '=', 'ledgers.id')
            ->where('ledgers.main_group_id', 7)
            ->where('master_voucher.date', '<=', $to)
            ->groupBy('master_voucher.debit_head')
            ->select('master_voucher.debit_head as ledger_id', DB::raw('SUM(master_voucher.amount) as dr_amount'))
            ->pluck('dr_amount', 'ledger_id')
            ->toArray();

        $creditorCrVouchers = DB::table('master_voucher')
            ->join('ledgers', 'master_voucher.credit_head', '=', 'ledgers.id')
            ->where('ledgers.main_group_id', 7)
            ->where('master_voucher.date', '<=', $to)
            ->groupBy('master_voucher.credit_head')
            ->select('master_voucher.credit_head as ledger_id', DB::raw('SUM(master_voucher.amount) as cr_amount'))
            ->pluck('cr_amount', 'ledger_id')
            ->toArray();

        $allCreditorIds = array_unique(array_merge(array_keys($creditorDrVouchers), array_keys($creditorCrVouchers)));
        $creditors = 0;
        $advanceToSuppliers = 0;
        foreach ($allCreditorIds as $lid) {
            $bal = ($creditorCrVouchers[$lid] ?? 0) - ($creditorDrVouchers[$lid] ?? 0);
            if ($bal > 0) {
                $creditors += $bal;
            } elseif ($bal < 0) {
                $advanceToSuppliers += abs($bal);
            }
        }

        $inventories = $getDrNet([18, 19, 20, 21, 22, 23, 24]);
        $accountsReceivable = $getDrNet(26);
        $advanceDepositsPrepayments = $getDrNet([27, 28, 29]) + $advanceToSuppliers;
        $cashCashEquivalents = $getDrNet([30, 31]);

        $totalCurrentAssets = $inventories + $tradeDebtors + $accountsReceivable + $advanceDepositsPrepayments + $cashCashEquivalents;

        // 4. Current Liabilities & Provisions
        $loans = $getCrNet(5);
        $interestPayable = $getCrNet(6);
        $accruedExpenses = $getCrNet(8);
        $liabilitiesOtherFinance = $getCrNet(9);
        $liabilitiesCapitalExpenditure = $getCrNet(10);
        $provisionTaxation = $getCrNet(11);
        $dividendPayable = $getCrNet(12);

        $totalCurrentLiabilities = $loans + $interestPayable + $creditors + $accruedExpenses + $advanceAgainstSales 
            + $liabilitiesOtherFinance + $liabilitiesCapitalExpenditure + $provisionTaxation + $dividendPayable;

        // 5. Net Current Assets
        $netCurrentAssets = $totalCurrentAssets - $totalCurrentLiabilities;

        // 6. Deferred Liabilities
        $deferredLiabilities = $getCrNet(4);

        // 7. TOTAL (ASSET SIDE)
        $totalAssetSide = $totalNonCurrentAssets + $interCompany + $netCurrentAssets - $deferredLiabilities;

        // 8. FINANCED BY
        $shareCapital = $getCrNet(1);

        // Cumulative Net Profit up to $to:
        // Income groups: 33, 34, 39, 40, 41, 42, 53
        $totalIncome = $getCrNet([33, 34, 39, 40, 41, 42, 53]);
        // Expense groups: 35, 36, 37, 38, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52
        $totalExpense = $getDrNet([35, 36, 37, 38, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52]);
        $netProfit = $totalIncome - $totalExpense;

        $reservesSurplus = $getCrNet(2);
        $retainedEarnings = $reservesSurplus + $netProfit;

        $totalShareholdersEquity = $shareCapital + $retainedEarnings;

        $longTermLoan = $getCrNet(3);
        $totalNonCurrentLiabilities = $longTermLoan;

        $totalLiabilitySide = $totalShareholdersEquity + $totalNonCurrentLiabilities;

        return [
            'non_current_assets' => [
                'tangible_fixed_assets' => $tangibleFixedAssets,
                'deferred_expenses' => $deferredExpenses,
                'investment' => $investment,
                'total' => $totalNonCurrentAssets,
            ],
            'inter_company_current_ac' => $interCompany,
            'current_assets' => [
                'inventories' => $inventories,
                'trade_debtors' => $tradeDebtors,
                'accounts_receivable' => $accountsReceivable,
                'advance_deposits_prepayments' => $advanceDepositsPrepayments,
                'cash_cash_equivalents' => $cashCashEquivalents,
                'total' => $totalCurrentAssets,
            ],
            'current_liabilities' => [
                'loans' => $loans,
                'interest_payable' => $interestPayable,
                'creditors' => $creditors,
                'accrued_expenses' => $accruedExpenses,
                'advance_against_sales' => $advanceAgainstSales,
                'liabilities_other_finance' => $liabilitiesOtherFinance,
                'liabilities_capital_expenditure' => $liabilitiesCapitalExpenditure,
                'provision_taxation' => $provisionTaxation,
                'dividend_payable' => $dividendPayable,
                'total' => $totalCurrentLiabilities,
            ],
            'net_current_assets' => $netCurrentAssets,
            'deferred_liabilities' => $deferredLiabilities,
            'total_asset_side' => $totalAssetSide,
            'shareholders_equity' => [
                'share_capital' => $shareCapital,
                'retained_earnings' => $retainedEarnings,
                'total' => $totalShareholdersEquity,
            ],
            'non_current_liabilities' => [
                'long_term_loan' => $longTermLoan,
                'total' => $totalNonCurrentLiabilities,
            ],
            'total_liability_side' => $totalLiabilitySide,
        ];
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
