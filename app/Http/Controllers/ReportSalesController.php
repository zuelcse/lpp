<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockItem;
use App\Models\Category;
use App\Models\MasterItems;
use App\Models\MasterMaterials;
use App\Models\MaterialsProduction;
use App\Models\Ledger;

class ReportSalesController extends Controller
{
    /**
     * Get company settings helper.
     */
    protected function getCompanySettings()
    {
        return DB::table('general_settings')->first() ?? (object)[
            'site_name' => config('variables.companyName', config('variables.templateName', 'Trading Solution')),
            'company_address' => '',
            'company_phone' => '',
            'company_email' => '',
        ];
    }

    /**
     * 1. Stock Position Report - Filter Panel
     */
    public function stockPosition(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();

        $s_date = $request->input('s_date', date('Y-01-01'));
        $e_date = $request->input('e_date', date('Y-m-d'));
        $selected_category = $request->input('category_id');
        $selected_item = $request->input('stock_item_id');
        $pdf = 0;

        return view('application.report.sales.stock_position_index', compact(
            'categories',
            'stockItems',
            's_date',
            'e_date',
            'selected_category',
            'selected_item',
            'pdf'
        ));
    }

    /**
     * 1. Stock Position Report - Generate
     * Fields: Item Name, Unit, Production Qty, Sales Qty, Balance Qty
     */
    public function stockPositionReport(Request $request)
    {
        $s_date = $request->input('s_date');
        $e_date = $request->input('e_date', date('Y-m-d'));
        $categoryId = $request->input('category_id');
        $stockItemId = $request->input('stock_item_id') ?? $request->input('item');
        $showAll = $request->boolean('show_all', true);

        $query = StockItem::with('unitDetail');

        if (!empty($stockItemId)) {
            $query->where('id', $stockItemId);
        }
        if (!empty($categoryId)) {
            $query->where('category', $categoryId);
        }

        $stockItems = $query->orderBy('name', 'ASC')->get();

        // Compute Production Qty (and inward purchase if any) and Sales Qty for each stock item
        $reportData = [];
        $totalProductionQty = 0.0;
        $totalSalesQty = 0.0;
        $totalBalanceQty = 0.0;

        foreach ($stockItems as $item) {
            // Production Qty
            $prodQuery = MaterialsProduction::where('production_item', $item->id);
            if (!empty($s_date)) {
                $prodQuery->whereBetween('date', [$s_date, $e_date]);
            } else {
                $prodQuery->where('date', '<=', $e_date);
            }
            $prodQty = (float) $prodQuery->sum('quantity');

            // Inward Purchase Qty (if finished item was also purchased)
            $purQuery = MasterItems::where('item_id', $item->id)->where('voucher_type', 1);
            if (!empty($s_date)) {
                $purQuery->whereBetween('date', [$s_date, $e_date]);
            } else {
                $purQuery->where('date', '<=', $e_date);
            }
            $purQty = (float) $purQuery->sum('purchase_quantity');

            // Combined inward production/purchase
            $inwardQty = $prodQty + $purQty;

            // Sales Qty
            $salesQuery = MasterItems::where('item_id', $item->id)
                ->where(function($q) {
                    $q->where('voucher_type', 2)->orWhere('sales_quantity', '>', 0);
                });
            if (!empty($s_date)) {
                $salesQuery->whereBetween('date', [$s_date, $e_date]);
            } else {
                $salesQuery->where('date', '<=', $e_date);
            }
            $salesQty = (float) $salesQuery->sum('sales_quantity');

            // Balance Qty
            $balanceQty = $inwardQty - $salesQty;

            if (!$showAll && $inwardQty == 0 && $salesQty == 0 && $balanceQty == 0) {
                continue;
            }

            $totalProductionQty += $inwardQty;
            $totalSalesQty += $salesQty;
            $totalBalanceQty += $balanceQty;

            $reportData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'barcode' => $item->barcode,
                'unit' => $item->unitDetail->name ?? '-',
                'production_qty' => $inwardQty,
                'prod_only_qty' => $prodQty,
                'pur_qty' => $purQty,
                'sales_qty' => $salesQty,
                'balance_qty' => $balanceQty,
            ];
        }

        $company = $this->getCompanySettings();

        return view('application.report.sales.stock_position_report', compact(
            'reportData',
            's_date',
            'e_date',
            'totalProductionQty',
            'totalSalesQty',
            'totalBalanceQty',
            'company'
        ));
    }

    /**
     * 2. Stock Item History Report - Filter Panel
     */
    public function stockItemHistory(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();

        $s_date = $request->input('s_date', date('Y-01-01'));
        $e_date = $request->input('e_date', date('Y-m-d'));
        $selected_category = $request->input('category_id');
        $selected_item = $request->input('stock_item_id');
        $pdf = 0;

        return view('application.report.sales.stock_item_history_index', compact(
            'categories',
            'stockItems',
            's_date',
            'e_date',
            'selected_category',
            'selected_item',
            'pdf'
        ));
    }

    /**
     * 2. Stock Item History Report - Generate
     * Fields: Voucher Date, Voucher No, Perticulars, Production Qty, Production Value, Rate, Sales Qty, Sales Amount, Balance Qty with grand total
     */
    public function stockItemHistoryReport(Request $request)
    {
        $request->validate([
            'stock_item_id' => 'nullable|integer|exists:stock_items,id',
            'item' => 'nullable|integer',
            's_date' => 'required|date',
            'e_date' => 'required|date|after_or_equal:s_date',
        ], [
            's_date.required' => 'Start date is required.',
            'e_date.required' => 'End date is required.',
            'e_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ]);

        $stockItemId = (int) ($request->input('stock_item_id') ?: $request->input('item'));

        if (!$stockItemId) {
            return back()->withInput()->withErrors(['stock_item_id' => 'Please select a Stock Item.']);
        }

        $stockItem = StockItem::with('unitDetail')->findOrFail($stockItemId);
        $s_date = $request->s_date;
        $e_date = $request->e_date;

        // Opening Balance calculation before s_date
        $openingProdQty = (float) MaterialsProduction::where('production_item', $stockItemId)
            ->where('date', '<', $s_date)
            ->sum('quantity');

        $openingPurQty = (float) MasterItems::where('item_id', $stockItemId)
            ->where('voucher_type', 1)
            ->where('date', '<', $s_date)
            ->sum('purchase_quantity');

        $openingSalesQty = (float) MasterItems::where('item_id', $stockItemId)
            ->where(function($q) {
                $q->where('voucher_type', 2)->orWhere('sales_quantity', '>', 0);
            })
            ->where('date', '<', $s_date)
            ->sum('sales_quantity');

        $openingBalance = ($openingProdQty + $openingPurQty) - $openingSalesQty;

        // Transactions in Range
        $transactions = [];

        // 1. Production entries
        $productions = MaterialsProduction::where('production_item', $stockItemId)
            ->whereBetween('date', [$s_date, $e_date])
            ->get();

        foreach ($productions as $prod) {
            $prodValue = (float) MasterMaterials::where('voucher_no', $prod->voucher_no)->sum('value');
            if ($prodValue <= 0) {
                $vVal = DB::table('voucher')->where('voucher_no', $prod->voucher_no)->value('total_amount');
                if ($vVal > 0) {
                    $prodValue = (float)$vVal;
                }
            }

            $qty = (float)$prod->quantity;
            $rate = ($qty > 0 && $prodValue > 0) ? round($prodValue / $qty, 2) : (float)($stockItem->purchaseRate ?? 0);
            if ($prodValue <= 0 && $rate > 0) {
                $prodValue = round($qty * $rate, 2);
            }

            $particulars = !empty($prod->narration) ? $prod->narration : 'Production';

            $transactions[] = [
                'date' => $prod->date,
                'voucher_no' => $prod->voucher_no,
                'particulars' => $particulars,
                'type' => 'production',
                'prod_qty' => $qty,
                'prod_value' => $prodValue,
                'rate' => $rate,
                'sales_qty' => 0.0,
                'sales_amount' => 0.0,
                'sort_time' => strtotime($prod->date . ' ' . ($prod->created_at ? $prod->created_at->format('H:i:s') : '00:00:00')),
                'id' => $prod->id,
            ];
        }

        // 2. Sales entries
        $sales = MasterItems::where('item_id', $stockItemId)
            ->where(function($q) {
                $q->where('voucher_type', 2)->orWhere('sales_quantity', '>', 0);
            })
            ->whereBetween('date', [$s_date, $e_date])
            ->get();

        $ledgers = Ledger::pluck('name', 'id')->toArray();

        foreach ($sales as $sale) {
            $qty = (float)$sale->sales_quantity;
            $amount = (float)($sale->net_amount > 0 ? $sale->net_amount : ($qty * (float)$sale->rate));
            $rate = (float)$sale->rate > 0 ? (float)$sale->rate : ($qty > 0 ? round($amount / $qty, 2) : 0);
            $custName = isset($ledgers[$sale->debit_head]) ? $ledgers[$sale->debit_head] : 'Sales';
            if (!empty($sale->note)) {
                $custName .= ' (' . $sale->note . ')';
            }

            $transactions[] = [
                'date' => $sale->date,
                'voucher_no' => $sale->voucher_no,
                'particulars' => $custName,
                'type' => 'sales',
                'prod_qty' => 0.0,
                'prod_value' => 0.0,
                'rate' => $rate,
                'sales_qty' => $qty,
                'sales_amount' => $amount,
                'sort_time' => strtotime($sale->date . ' ' . ($sale->created_at ? $sale->created_at->format('H:i:s') : '00:00:00')),
                'id' => $sale->id,
            ];
        }

        // 3. Purchase entries (if any inward purchases)
        $purchases = MasterItems::where('item_id', $stockItemId)
            ->where('voucher_type', 1)
            ->where('purchase_quantity', '>', 0)
            ->whereBetween('date', [$s_date, $e_date])
            ->get();

        foreach ($purchases as $pur) {
            $qty = (float)$pur->purchase_quantity;
            $amount = (float)($pur->net_amount > 0 ? $pur->net_amount : ($qty * (float)$pur->rate));
            $rate = (float)$pur->rate > 0 ? (float)$pur->rate : ($qty > 0 ? round($amount / $qty, 2) : 0);
            $supplier = isset($ledgers[$pur->credit_head]) ? $ledgers[$pur->credit_head] : 'Purchase';
            $particulars = 'Purchase - ' . $supplier;

            $transactions[] = [
                'date' => $pur->date,
                'voucher_no' => $pur->voucher_no,
                'particulars' => $particulars,
                'type' => 'purchase',
                'prod_qty' => $qty,
                'prod_value' => $amount,
                'rate' => $rate,
                'sales_qty' => 0.0,
                'sales_amount' => 0.0,
                'sort_time' => strtotime($pur->date . ' ' . ($pur->created_at ? $pur->created_at->format('H:i:s') : '00:00:00')),
                'id' => $pur->id,
            ];
        }

        // Sort chronologically
        usort($transactions, function($a, $b) {
            if ($a['date'] !== $b['date']) {
                return strcmp($a['date'], $b['date']);
            }
            if ($a['sort_time'] !== $b['sort_time']) {
                return $a['sort_time'] <=> $b['sort_time'];
            }
            return $a['id'] <=> $b['id'];
        });

        // Compute running balance & grand totals
        $runningBal = $openingBalance;
        $grandProdQty = 0.0;
        $grandProdValue = 0.0;
        $grandSalesQty = 0.0;
        $grandSalesAmount = 0.0;

        foreach ($transactions as &$row) {
            $runningBal += ($row['prod_qty'] - $row['sales_qty']);
            $row['balance_qty'] = $runningBal;

            $grandProdQty += $row['prod_qty'];
            $grandProdValue += $row['prod_value'];
            $grandSalesQty += $row['sales_qty'];
            $grandSalesAmount += $row['sales_amount'];
        }
        unset($row);

        $finalBalanceQty = $runningBal;
        $company = $this->getCompanySettings();

        return view('application.report.sales.stock_item_history_report', compact(
            'stockItem',
            'openingBalance',
            'transactions',
            'grandProdQty',
            'grandProdValue',
            'grandSalesQty',
            'grandSalesAmount',
            'finalBalanceQty',
            's_date',
            'e_date',
            'company'
        ));
    }

    /**
     * 3. Sales Statement Report - Filter Panel
     */
    public function salesStatement(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();

        $s_date = $request->input('s_date', date('Y-m-01'));
        $e_date = $request->input('e_date', date('Y-m-d'));
        $selected_category = $request->input('category_id');
        $selected_item = $request->input('stock_item_id');
        $pdf = 0;

        return view('application.report.sales.sales_statement_index', compact(
            'categories',
            'stockItems',
            's_date',
            'e_date',
            'selected_category',
            'selected_item',
            'pdf'
        ));
    }

    /**
     * 3. Sales Statement Report - Generate
     * Fields: Item Name, Unit, Sales Qty, Sales value
     */
    public function salesStatementReport(Request $request)
    {
        $request->validate([
            's_date' => 'required|date',
            'e_date' => 'required|date|after_or_equal:s_date',
        ], [
            's_date.required' => 'Start date is required.',
            'e_date.required' => 'End date is required.',
            'e_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ]);

        $s_date = $request->s_date;
        $e_date = $request->e_date;
        $categoryId = $request->input('category_id');
        $stockItemId = $request->input('stock_item_id') ?? $request->input('item');
        $showAll = $request->boolean('show_all', false);

        $query = StockItem::with('unitDetail');

        if (!empty($stockItemId)) {
            $query->where('id', $stockItemId);
        }
        if (!empty($categoryId)) {
            $query->where('category', $categoryId);
        }

        $stockItems = $query->orderBy('name', 'ASC')->get();

        $reportData = [];
        $totalSalesQty = 0.0;
        $totalSalesValue = 0.0;

        foreach ($stockItems as $item) {
            $salesAgg = MasterItems::where('item_id', $item->id)
                ->where(function($q) {
                    $q->where('voucher_type', 2)->orWhere('sales_quantity', '>', 0);
                })
                ->whereBetween('date', [$s_date, $e_date])
                ->selectRaw('SUM(sales_quantity) as total_qty, SUM(net_amount) as total_value')
                ->first();

            $qty = (float)($salesAgg->total_qty ?? 0);
            $val = (float)($salesAgg->total_value ?? 0);

            if (!$showAll && $qty == 0 && $val == 0) {
                continue;
            }

            $totalSalesQty += $qty;
            $totalSalesValue += $val;

            $reportData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'barcode' => $item->barcode,
                'unit' => $item->unitDetail->name ?? '-',
                'sales_qty' => $qty,
                'sales_value' => $val,
            ];
        }

        $company = $this->getCompanySettings();

        return view('application.report.sales.sales_statement_report', compact(
            'reportData',
            's_date',
            'e_date',
            'totalSalesQty',
            'totalSalesValue',
            'company'
        ));
    }

    /**
     * 4. Purchase Statement Report - Filter Panel
     */
    public function purchaseStatement(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();

        $s_date = $request->input('s_date', date('Y-m-01'));
        $e_date = $request->input('e_date', date('Y-m-d'));
        $selected_category = $request->input('category_id');
        $selected_item = $request->input('stock_item_id');
        $pdf = 0;

        return view('application.report.sales.purchase_statement_index', compact(
            'categories',
            'stockItems',
            's_date',
            'e_date',
            'selected_category',
            'selected_item',
            'pdf'
        ));
    }

    /**
     * 4. Purchase Statement Report - Generate
     * Fields: Item Name, Unit, Purchase Qty, Purchase value
     */
    public function purchaseStatementReport(Request $request)
    {
        $request->validate([
            's_date' => 'required|date',
            'e_date' => 'required|date|after_or_equal:s_date',
        ], [
            's_date.required' => 'Start date is required.',
            'e_date.required' => 'End date is required.',
            'e_date.after_or_equal' => 'End date must be greater than or equal to start date.',
        ]);

        $s_date = $request->s_date;
        $e_date = $request->e_date;
        $categoryId = $request->input('category_id');
        $stockItemId = $request->input('stock_item_id') ?? $request->input('item');
        $showAll = $request->boolean('show_all', false);

        $query = StockItem::with('unitDetail');

        if (!empty($stockItemId)) {
            $query->where('id', $stockItemId);
        }
        if (!empty($categoryId)) {
            $query->where('category', $categoryId);
        }

        $stockItems = $query->orderBy('name', 'ASC')->get();

        $reportData = [];
        $totalPurchaseQty = 0.0;
        $totalPurchaseValue = 0.0;

        foreach ($stockItems as $item) {
            $purAgg = MasterItems::where('item_id', $item->id)
                ->where('voucher_type', 1)
                ->where('purchase_quantity', '>', 0)
                ->whereBetween('date', [$s_date, $e_date])
                ->selectRaw('SUM(purchase_quantity) as total_qty, SUM(net_amount) as total_value')
                ->first();

            $qty = (float)($purAgg->total_qty ?? 0);
            $val = (float)($purAgg->total_value ?? 0);

            if (!$showAll && $qty == 0 && $val == 0) {
                continue;
            }

            $totalPurchaseQty += $qty;
            $totalPurchaseValue += $val;

            $reportData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'barcode' => $item->barcode,
                'unit' => $item->unitDetail->name ?? '-',
                'purchase_qty' => $qty,
                'purchase_value' => $val,
            ];
        }

        $company = $this->getCompanySettings();

        return view('application.report.sales.purchase_statement_report', compact(
            'reportData',
            's_date',
            'e_date',
            'totalPurchaseQty',
            'totalPurchaseValue',
            'company'
        ));
    }

    /**
     * AJAX endpoint to get stock items filtered by category.
     */
    public function getItemsByCategory($category_id = null)
    {
        $query = StockItem::with('unitDetail');
        if (!empty($category_id)) {
            $query->where('category', $category_id);
        }
        $items = $query->orderBy('name', 'ASC')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'barcode' => $item->barcode,
                'unit' => $item->unitDetail->name ?? '',
                'category' => $item->category,
            ];
        });

        return response()->json($items);
    }
}
