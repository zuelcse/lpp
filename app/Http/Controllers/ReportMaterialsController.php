<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Materialitems;
use App\Models\MaterialGroups;
use App\Models\MasterMaterials;
use App\Models\MaterialsReceivedMrr;
use App\Models\MaterialsIssue2production;
use App\Models\MaterialsProduction;

class ReportMaterialsController extends Controller
{
    /**
     * Display the Raw Materials Ledger Report Panel (filter form).
     */
    public function rawMaterialsLedger(Request $request)
    {
        $materialGroups = MaterialGroups::orderBy('name', 'ASC')->get();
        $rawMaterials = Materialitems::with(['unitDetail', 'materialGroup'])
            ->orderBy('name', 'ASC')
            ->get();

        $s_date = $request->input('s_date', date('Y-01-01'));
        $e_date = $request->input('e_date', date('Y-m-d'));
        $selected_item = $request->input('material_item_id');
        $selected_group = $request->input('group_id');
        $pdf = 0;

        return view('application.report.materials.raw_materials_ledger_index', compact(
            'materialGroups',
            'rawMaterials',
            's_date',
            'e_date',
            'selected_item',
            'selected_group',
            'pdf'
        ));
    }

    /**
     * Generate and display the Raw Materials Ledger Report.
     */
    public function rawMaterialsLedgerReport(Request $request)
    {
        $request->validate([
            'material_item_id' => 'required|integer|exists:material_items,id',
            's_date' => 'required|date',
            'e_date' => 'required|date|after_or_equal:s_date',
        ], [
            'material_item_id.required' => 'Please select a Raw Material item.',
            'material_item_id.exists' => 'Selected Raw Material item does not exist.',
            's_date.required' => 'Start date is required.',
            'e_date.required' => 'End date is required.',
            'e_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ]);

        $materialId = (int)$request->material_item_id;
        $s_date = $request->s_date;
        $e_date = $request->e_date;

        $materialItem = Materialitems::with(['unitDetail', 'materialGroup'])->findOrFail($materialId);
        $unitName = $materialItem->unitDetail ? ($materialItem->unitDetail->name ?? '') : '';

        // 1. Calculate Opening Balances before $s_date
        $itemOpeningStock = (float)($materialItem->opening_stock ?? 0);
        $itemOpeningValue = (float)($materialItem->opening_value ?? 0);
        $initialRate = $itemOpeningStock > 0 ? ($itemOpeningValue / $itemOpeningStock) : 0.0;

        // Check if item was created before $s_date
        $itemCreatedAtDate = $materialItem->created_at ? $materialItem->created_at->format('Y-m-d') : '2000-01-01';

        $storBal = 0.0;
        $florBal = 0.0;
        $totalBal = 0.0;
        $totalValue = 0.0;
        $currentRate = $initialRate;

        // If the item's initial opening stock is recorded before $s_date, include it in opening
        $includeInitialInOpening = ($itemOpeningStock > 0 && $itemCreatedAtDate < $s_date);
        if ($includeInitialInOpening) {
            $storBal += $itemOpeningStock;
            $totalBal = $storBal + $florBal;
            $totalValue += $itemOpeningValue;
            if ($totalBal > 0) {
                $currentRate = $totalValue / $totalBal;
            }
        }

        // Transactions prior to $s_date
        $priorTransactions = MasterMaterials::where('item_id', $materialId)
            ->where('date', '<', $s_date)
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        foreach ($priorTransactions as $tx) {
            $vType = (int)$tx->voucher_type;
            if ($vType === 14) { // MRR
                $rcvQty = (float)$tx->received_qty;
                $rcvVal = (float)$tx->value;
                if ($rcvVal <= 0 && (float)$tx->rate > 0) {
                    $rcvVal = $rcvQty * (float)$tx->rate;
                }
                $storBal += $rcvQty;
                $totalBal = $storBal + $florBal;
                $totalValue += $rcvVal;
                if ($totalBal > 0) {
                    $currentRate = $totalValue / $totalBal;
                }
            } elseif ($vType === 15) { // Issue to Production
                $isuQty = (float)$tx->issue2production_qty;
                $storBal -= $isuQty;
                $florBal += $isuQty;
                $totalBal = $storBal + $florBal;
                $totalValue = $totalBal * $currentRate;
            } elseif ($vType === 16) { // Production Consumption
                $conQty = (float)$tx->production_qty;
                $conVal = $conQty * $currentRate;
                $florBal -= $conQty;
                $totalBal = $storBal + $florBal;
                $totalValue -= $conVal;
                if ($totalValue < 0 && $totalBal <= 0) {
                    $totalValue = 0;
                }
            }
        }

        $openingRow = [
            'date' => $s_date,
            'voucher_no' => 'Opening',
            'particulars' => 'Opening Balance --',
            'rcv_qty' => 0.0,
            'rcv_val' => 0.0,
            'isu_qty' => 0.0,
            'con_qty' => 0.0,
            'con_val' => 0.0,
            'flor_bal' => $florBal,
            'stor_bal' => $storBal,
            'total_bal' => $totalBal,
            'unit' => $unitName,
            'rate' => $currentRate,
            'value' => $totalValue,
        ];

        // 2. Range Transactions [$s_date, $e_date]
        $rangeTransactions = MasterMaterials::where('item_id', $materialId)
            ->whereBetween('date', [$s_date, $e_date])
            ->orderBy('date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        // Bulk load metadata for display particulars
        $mrrVouchers = $rangeTransactions->where('voucher_type', 14)->pluck('voucher_no')->filter()->unique()->all();
        $mrrs = !empty($mrrVouchers)
            ? MaterialsReceivedMrr::whereIn('voucher_no', $mrrVouchers)->with('supplier')->get()->keyBy('voucher_no')
            : collect();

        $prdVouchers = $rangeTransactions->where('voucher_type', 16)->pluck('voucher_no')->filter()->unique()->all();
        $productions = !empty($prdVouchers)
            ? MaterialsProduction::whereIn('voucher_no', $prdVouchers)->with('stockItem')->get()->keyBy('voucher_no')
            : collect();

        $rows = [];
        $totalRcvQty = 0.0;
        $totalRcvVal = 0.0;
        $totalIsuQty = 0.0;
        $totalConQty = 0.0;
        $totalConVal = 0.0;

        // If item's opening stock is within range (e.g. item created within range or never counted prior)
        if (!$includeInitialInOpening && $itemOpeningStock > 0 && $itemCreatedAtDate >= $s_date && $itemCreatedAtDate <= $e_date) {
            $storBal += $itemOpeningStock;
            $totalBal = $storBal + $florBal;
            $totalValue += $itemOpeningValue;
            if ($totalBal > 0) {
                $currentRate = $totalValue / $totalBal;
            }

            $totalRcvQty += $itemOpeningStock;
            $totalRcvVal += $itemOpeningValue;

            $rows[] = [
                'date' => $itemCreatedAtDate,
                'voucher_no' => 'Opening',
                'particulars' => 'Opening Stock',
                'rcv_qty' => $itemOpeningStock,
                'rcv_val' => $itemOpeningValue,
                'isu_qty' => 0.0,
                'con_qty' => 0.0,
                'con_val' => 0.0,
                'flor_bal' => $florBal,
                'stor_bal' => $storBal,
                'total_bal' => $totalBal,
                'unit' => $unitName,
                'rate' => $currentRate,
                'value' => $totalValue,
            ];
        }

        foreach ($rangeTransactions as $tx) {
            $vType = (int)$tx->voucher_type;
            $rcvQty = 0.0;
            $rcvVal = 0.0;
            $isuQty = 0.0;
            $conQty = 0.0;
            $conVal = 0.0;
            $particulars = '';

            if ($vType === 14) { // MRR
                $rcvQty = (float)$tx->received_qty;
                $rcvVal = (float)$tx->value;
                if ($rcvVal <= 0 && (float)$tx->rate > 0) {
                    $rcvVal = $rcvQty * (float)$tx->rate;
                }

                $mrr = $mrrs->get($tx->voucher_no);
                if ($mrr && $mrr->supplier) {
                    $particulars = $mrr->supplier->name;
                    if ($mrr->supplier->alias) {
                        $particulars .= ' (' . $mrr->supplier->alias . ')';
                    }
                } else {
                    $particulars = $tx->note ?: 'Material Receive Report';
                }

                $storBal += $rcvQty;
                $totalBal = $storBal + $florBal;
                $totalValue += $rcvVal;
                if ($totalBal > 0) {
                    $currentRate = $totalValue / $totalBal;
                }

                $totalRcvQty += $rcvQty;
                $totalRcvVal += $rcvVal;

            } elseif ($vType === 15) { // Issue to Production
                $isuQty = (float)$tx->issue2production_qty;
                $particulars = $tx->note ?: '';

                $storBal -= $isuQty;
                $florBal += $isuQty;
                $totalBal = $storBal + $florBal;
                $totalValue = $totalBal * $currentRate;

                $totalIsuQty += $isuQty;

            } elseif ($vType === 16) { // Production Consumption
                $conQty = (float)$tx->production_qty;
                $conVal = $conQty * $currentRate;

                $prod = $productions->get($tx->voucher_no);
                if ($prod && $prod->stockItem) {
                    $particulars = $prod->stockItem->name;
                } else {
                    $particulars = $tx->note ?: 'Production';
                }

                $florBal -= $conQty;
                $totalBal = $storBal + $florBal;
                $totalValue -= $conVal;
                if ($totalValue < 0 && $totalBal <= 0) {
                    $totalValue = 0;
                }

                $totalConQty += $conQty;
                $totalConVal += $conVal;
            }

            $rows[] = [
                'date' => $tx->date,
                'voucher_no' => $tx->voucher_no,
                'particulars' => $particulars,
                'rcv_qty' => $rcvQty,
                'rcv_val' => $rcvVal,
                'isu_qty' => $isuQty,
                'con_qty' => $conQty,
                'con_val' => $conVal,
                'flor_bal' => $florBal,
                'stor_bal' => $storBal,
                'total_bal' => $totalBal,
                'unit' => $unitName,
                'rate' => $currentRate,
                'value' => $totalValue,
            ];
        }

        $totals = [
            'rcv_qty' => $totalRcvQty,
            'rcv_val' => $totalRcvVal,
            'isu_qty' => $totalIsuQty,
            'con_qty' => $totalConQty,
            'con_val' => $totalConVal,
            'final_flor_bal' => $florBal,
            'final_stor_bal' => $storBal,
            'final_total_bal' => $totalBal,
            'final_rate' => $currentRate,
            'final_value' => $totalValue,
        ];

        return view('application.report.materials.raw_materials_ledger_report', compact(
            'materialItem',
            's_date',
            'e_date',
            'openingRow',
            'rows',
            'totals',
            'unitName'
        ));
    }

    /**
     * AJAX endpoint to filter Raw Material items by Group.
     */
    public function getMaterialItems($group_id = null)
    {
        $query = Materialitems::with('unitDetail')->orderBy('name', 'ASC');
        if (!empty($group_id) && $group_id !== 'all') {
            $query->where('group', $group_id);
        }

        $items = $query->get()->map(function ($item) {
            $unit = $item->unitDetail ? $item->unitDetail->name : '';
            $text = ($item->barcode ? $item->barcode . ' - ' : '') . $item->name . ($unit ? ' (' . $unit . ')' : '');
            return [
                'id' => $item->id,
                'name' => $item->name,
                'barcode' => $item->barcode,
                'text' => $text,
                'unit' => $unit,
                'group' => $item->group,
            ];
        });

        return response()->json($items);
    }
}
