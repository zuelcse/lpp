<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challan;
use App\Models\Materialitems;
use App\Models\MaterialsIssue2production;
use App\Models\MaterialsProduction;
use App\Models\MaterialsReceivedMrr;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\StockItem;
use App\Models\Voucher;
use DB;
use Auth;

class Analytics extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except('syncfromtallysyncnodemodule');
    }

  	public function index(Request $request)
  	{
  	    $today = date('Y-m-d');
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $selectedYear = (int) ($request->input('year') ?: date('Y'));

        // Available years from sales & purchase
        $salesYears = Sales::whereNotNull('date')->selectRaw('DISTINCT YEAR(date) as y')->pluck('y')->toArray();
        $purchaseYears = Purchase::whereNotNull('date')->selectRaw('DISTINCT YEAR(date) as y')->pluck('y')->toArray();
        $availableYears = collect(array_merge($salesYears, $purchaseYears, [(int) date('Y')]))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        // Today KPIs
  	    $sales = Sales::select(
  	            DB::raw('COUNT(id) as sales_qty'),
  	            DB::raw('COALESCE(SUM(gross_amount), 0) as sales_value')
  	        )
  	        ->where('date', $today)
  	        ->first();

  	    $receipt = Voucher::select(
  	            DB::raw('COUNT(id) as receipt_qty'),
  	            DB::raw('COALESCE(SUM(total_amount), 0) as receipt_value')
  	        )
  	        ->where('date', $today)
  	        ->first();

        $todayPurchase = Purchase::select(
                DB::raw('COUNT(id) as qty'),
                DB::raw('COALESCE(SUM(gross_amount), 0) as value')
            )
            ->where('date', $today)
            ->first();

        // Monthly KPIs
        $monthSales = Sales::select(
                DB::raw('COUNT(id) as qty'),
                DB::raw('COALESCE(SUM(gross_amount), 0) as value'),
                DB::raw('COALESCE(SUM(paid_amount), 0) as paid')
            )
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->first();

        $monthPurchase = Purchase::select(
                DB::raw('COUNT(id) as qty'),
                DB::raw('COALESCE(SUM(gross_amount), 0) as value')
            )
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->first();

        // Cash & Bank Receipts (10, 12) vs Payments (9, 11) for selected year & current month
        $monthCashReceive = (float) Voucher::whereIn('voucher_type', [10, 12])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('total_amount');
        $monthCashPayment = (float) Voucher::whereIn('voucher_type', [9, 11])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('total_amount');

        $yearSalesTotal = (float) Sales::whereYear('date', $selectedYear)->sum('gross_amount');
        $yearSalesPaid = (float) Sales::whereYear('date', $selectedYear)->sum('paid_amount');
        $yearPurchaseTotal = (float) Purchase::whereYear('date', $selectedYear)->sum('gross_amount');
        $yearReceiptsTotal = (float) Voucher::whereIn('voucher_type', [10, 12])->whereYear('date', $selectedYear)->sum('total_amount');
        $yearPaymentsTotal = (float) Voucher::whereIn('voucher_type', [9, 11])->whereYear('date', $selectedYear)->sum('total_amount');

        // Previous year sales for growth comparison
        $prevYear = $selectedYear - 1;
        $prevYearSalesTotal = (float) Sales::whereYear('date', $prevYear)->sum('gross_amount');
        $collectionRate = $yearSalesTotal > 0 ? min(100, round(($yearSalesPaid / $yearSalesTotal) * 100, 1)) : 0;

        // Monthly series (Jan - Dec)
        $monthlySales = $this->monthlySum(Sales::query(), 'date', 'gross_amount', $selectedYear);
        $monthlyPurchase = $this->monthlySum(Purchase::query(), 'date', 'gross_amount', $selectedYear);
        $monthlyReceipts = $this->monthlySum(Voucher::whereIn('voucher_type', [10, 12]), 'date', 'total_amount', $selectedYear);
        $monthlyPayments = $this->monthlySum(Voucher::whereIn('voucher_type', [9, 11]), 'date', 'total_amount', $selectedYear);

        // Challan Status Breakdown
        $challanStatusCounts = Challan::select('status', DB::raw('COUNT(id) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();
        $challanStats = [
            'total' => array_sum($challanStatusCounts),
            'Pending' => $challanStatusCounts['Pending'] ?? 0,
            'Dispatched' => $challanStatusCounts['Dispatched'] ?? 0,
            'Delivered' => $challanStatusCounts['Delivered'] ?? 0,
            'Cancelled' => $challanStatusCounts['Cancelled'] ?? 0,
        ];

        // Inventory & Production Summary
        $inventoryStats = [
            'stock_items_count' => StockItem::count(),
            'stock_total_qty' => (float) StockItem::sum('quantity'),
            'low_stock_count' => StockItem::where('quantity', '<=', 5)->count(),
            'material_items_count' => Materialitems::count(),
            'material_total_qty' => (float) Materialitems::sum('quantity'),
            'low_material_count' => Materialitems::where('quantity', '<=', 5)->count(),
            'mrr_count' => MaterialsReceivedMrr::count(),
            'issue_count' => MaterialsIssue2production::count(),
            'production_count' => MaterialsProduction::count(),
        ];

        // Category stock breakdown for Donut chart
        $categoryBreakdown = DB::table('stock_items as si')
            ->leftJoin('category as c', 'c.id', '=', 'si.category')
            ->select(
                DB::raw("COALESCE(c.name, 'Uncategorized') as category_name"),
                DB::raw('COUNT(si.id) as item_count'),
                DB::raw('COALESCE(SUM(si.quantity), 0) as total_qty')
            )
            ->groupBy('category_name')
            ->orderByDesc('item_count')
            ->get();

        // Top 5 Customers by Sales in selected year
        $topCustomers = DB::table('sales as s')
            ->join('ledgers as l', 'l.id', '=', 's.debit_head')
            ->select(
                'l.id',
                'l.name',
                'l.alias',
                DB::raw('COUNT(s.id) as invoice_count'),
                DB::raw('COALESCE(SUM(s.gross_amount), 0) as total_sales')
            )
            ->whereYear('s.date', $selectedYear)
            ->groupBy('l.id', 'l.name', 'l.alias')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Top 5 Selling Stock Items in selected year
        $topSellingItems = DB::table('master_items as mi')
            ->join('stock_items as si', 'si.id', '=', 'mi.item_id')
            ->leftJoin('units as u', 'u.id', '=', 'si.unit')
            ->select(
                'si.id',
                'si.name',
                'u.name as unit_name',
                DB::raw('COALESCE(SUM(mi.sales_quantity), 0) as total_qty'),
                DB::raw('COALESCE(SUM(mi.net_amount), 0) as total_amount')
            )
            ->where('mi.sales_quantity', '>', 0)
            ->whereYear('mi.date', $selectedYear)
            ->groupBy('si.id', 'si.name', 'u.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Low stock alerts (Finished Goods & Raw Materials)
        $lowStockItems = StockItem::with('unitDetail')
            ->orderBy('quantity', 'ASC')
            ->limit(5)
            ->get();

        $lowMaterialItems = Materialitems::with('unitDetail')
            ->orderBy('quantity', 'ASC')
            ->limit(5)
            ->get();

        // Recent Activity Lists
        $recentSales = Sales::with('Ledger')
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        $recentChallans = Challan::with('ledger')
            ->orderBy('challan_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        $recentVouchers = Voucher::with('VoucherType')
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(6)
            ->get();

        $chartData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'sales' => $monthlySales,
            'purchase' => $monthlyPurchase,
            'receipts' => $monthlyReceipts,
            'payments' => $monthlyPayments,
            'collectionRate' => $collectionRate,
            'categoryLabels' => $categoryBreakdown->pluck('category_name')->values()->all(),
            'categoryCounts' => $categoryBreakdown->pluck('item_count')->map(fn ($v) => (int) $v)->values()->all(),
            'categoryQtys' => $categoryBreakdown->pluck('total_qty')->map(fn ($v) => round((float) $v, 2))->values()->all(),
        ];

    	return view('content.dashboard.dashboards-analytics', compact(
            'sales',
            'receipt',
            'todayPurchase',
            'monthSales',
            'monthPurchase',
            'monthCashReceive',
            'monthCashPayment',
            'selectedYear',
            'availableYears',
            'yearSalesTotal',
            'yearSalesPaid',
            'yearPurchaseTotal',
            'yearReceiptsTotal',
            'yearPaymentsTotal',
            'prevYearSalesTotal',
            'collectionRate',
            'challanStats',
            'inventoryStats',
            'categoryBreakdown',
            'topCustomers',
            'topSellingItems',
            'lowStockItems',
            'lowMaterialItems',
            'recentSales',
            'recentChallans',
            'recentVouchers',
            'chartData'
        ));
  	}

    protected function monthlySum($query, string $dateCol, string $sumCol, int $year): array
    {
        $rows = $query
            ->selectRaw("MONTH($dateCol) as m, COALESCE(SUM($sumCol), 0) as total")
            ->whereYear($dateCol, $year)
            ->groupByRaw("MONTH($dateCol)")
            ->pluck('total', 'm');

        $series = [];
        for ($m = 1; $m <= 12; $m++) {
            $series[] = round((float) ($rows[$m] ?? 0), 2);
        }
        return $series;
    }

    // This Method Run from the run2.bat file at customer Local server Pc. ZuelAli (+8801738051123), 250109
    // This link will execute as run2.bat file execute or open.
    // Link: curl https://smartpolymerbms.com/syncfromtallysyncnodemodule
    // Link: curl https://tallyweb.smartpolymerbms.com/syncfromtallysyncnodemodule
    public function syncfromtallysyncnodemodule(){

        DB::select("INSERT INTO units ( name,isSimpleUnit,alterId,decimalPlaces,numberOfUnits)
            SELECT mu.name, mu.isSimpleUnit, mu.alterId, mu.decimalPlaces, mu.numberOfUnits
            FROM mst_units mu
            LEFT JOIN units u
            ON mu.name = u.name 
            WHERE u.name  IS NULL");

        DB::select("INSERT INTO mst_cost_centre ( name,parent,category)
            SELECT ct.name, ct.parent, ct.category
            FROM mst_cost_centre_tally ct
            LEFT JOIN mst_cost_centre c
            ON ct.name = c.name 
            WHERE c.name  IS NULL");

        DB::select("INSERT INTO stock_items (`name`, `parent`, `alias`, `baseUnits`, `openingBalance`, `openingValue`, `openingRate`, `closing_balance`, `standard_price`,`masterId`)
            SELECT msi.`name`, msi.`parent`, msi.`alias`, u.`id`, msi.`opening_balance`, msi.`opening_value`, msi.`opening_rate`, msi.`closing_balance`, msi.`standard_price`, msi.`masterId`
            FROM  mst_stock_item msi
            LEFT JOIN  units u
            ON msi.base_units = u.name
            ON DUPLICATE KEY UPDATE
            name = VALUES(`name`),
            parent = VALUES(`parent`),
            alias = VALUES(`alias`),
            baseUnits = VALUES(`baseUnits`),
            closing_balance = VALUES(`closing_balance`),
            standard_price = VALUES(`standard_price`)
        ");

        DB::select("INSERT INTO mst_ledger (`guid`, `name`, `CreditLimit`, `parent`, `alias`, `is_revenue`, `is_deemedpositive`, `opening_balance`,`closing_balance`, `description`, `mailing_name`, `mailing_address`, `mailing_state`, `mailing_country`, `mailing_pincode`, `email`, `it_pan`, `gstn`, `gst_registration_type`, `gst_supply_type`, `gst_duty_head`, `tax_rate`, `bank_account_holder`, `bank_account_number`, `bank_ifsc`, `bank_swift`, `bank_name`, `bank_branch`,`masterId`)
            SELECT mlt.`guid`, mlt.`name`, mlt.`CreditLimit`, mlt.`parent`, mlt.`alias`, mlt.`is_revenue`, mlt.`is_deemedpositive`, mlt.`opening_balance`,mlt.`closing_balance`, mlt.`description`, mlt.`mailing_name`, mlt.`mailing_address`, mlt.`mailing_state`, mlt.`mailing_country`, mlt.`mailing_pincode`, mlt.`email`, mlt.`it_pan`, mlt.`gstn`, mlt.`gst_registration_type`, mlt.`gst_supply_type`, mlt.`gst_duty_head`, mlt.`tax_rate`, mlt.`bank_account_holder`, mlt.`bank_account_number`, mlt.`bank_ifsc`, mlt.`bank_swift`, mlt.`bank_name`, mlt.`bank_branch`,mlt.`masterId`
            FROM  mst_ledger_tally mlt
            ON DUPLICATE KEY UPDATE
            name = VALUES(`name`),
            alias = VALUES(`alias`),
            parent = VALUES(`parent`),
            mailing_name = VALUES(`mailing_name`),
            mailing_address = VALUES(`mailing_address`),
            bank_account_number = VALUES(`bank_account_number`),
            bank_name = VALUES(`bank_name`),
            opening_balance = VALUES(`opening_balance`),
            closing_balance = VALUES(`closing_balance`),
            CreditLimit = VALUES(`CreditLimit`)
        ");

        DB::select("INSERT IGNORE INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`) SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance` FROM all_voucher_list_tally avlt WHERE 1");

        DB::select("INSERT IGNORE INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`) SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance` FROM all_voucher_list_tally_journal avlt WHERE 1");
        
        
            
        echo "Web synced!";
        exit();
    }
}
