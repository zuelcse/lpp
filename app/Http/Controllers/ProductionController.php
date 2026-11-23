<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MaterialsProduction;
use App\Models\MasterMaterials;
use App\Models\Materialitems;
use App\Models\StockItem;
use App\Models\Voucher;
use App\Models\MasterVoucher;
use App\Models\Ledger;

class ProductionController extends Controller
{
    protected $voucherType = 16;      // Production voucher type (PRD)
    protected $cogsLedgerId = 222;    // Cost of Goods Sold / Consumption Raw Materials
    protected $purchaseLedgerId = 2;  // Purchase / Raw Materials

    public function index(Request $request)
    {
        $query = MaterialsProduction::with('stockItem.unitDetail');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhereHas('stockItem', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('barcode', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $productions = $query->orderBy('id', 'DESC')->paginate(25);

        // Fetch aggregated values from master_materials for each voucher_no
        $voucherNos = $productions->pluck('voucher_no')->filter()->all();
        $aggregates = [];
        $vouchers = [];
        if (!empty($voucherNos)) {
            $aggregates = MasterMaterials::select(
                    'voucher_no',
                    DB::raw('COUNT(*) as total_items'),
                    DB::raw('SUM(production_qty) as total_prod_qty'),
                    DB::raw('SUM(value) as total_prod_value')
                )
                ->whereIn('voucher_no', $voucherNos)
                ->groupBy('voucher_no')
                ->get()
                ->keyBy('voucher_no');

            $vouchers = Voucher::whereIn('voucher_no', $voucherNos)
                ->get()
                ->keyBy('voucher_no');
        }

        return view('application.production.list', compact('productions', 'aggregates', 'vouchers'));
    }

    public function create()
    {
        $voucher_no = self::generateVoucherNo();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();
        $rawMaterials = Materialitems::with('unitDetail')->orderBy('name', 'ASC')->get();

        // Compute available floor stock (Total Issued - Total Consumed) for all raw materials
        $floorStocks = MasterMaterials::select(
                'item_id',
                DB::raw('(COALESCE(SUM(issue2production_qty), 0) - COALESCE(SUM(production_qty), 0)) as floor_stock')
            )
            ->whereNotNull('item_id')
            ->groupBy('item_id')
            ->pluck('floor_stock', 'item_id')
            ->toArray();

        // Get latest MRR rates for raw materials
        $latestRates = MasterMaterials::where('voucher_type', 14)
            ->where('rate', '>', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('item_id')
            ->pluck('rate', 'item_id')
            ->toArray();

        foreach ($rawMaterials as $mat) {
            $mat->floor_stock = max(0, (float)($floorStocks[$mat->id] ?? 0));
            $rate = (float)($latestRates[$mat->id] ?? 0);
            if ($rate <= 0 && (float)$mat->opening_stock > 0 && (float)$mat->opening_value > 0) {
                $rate = round((float)$mat->opening_value / (float)$mat->opening_stock, 3);
            }
            $mat->latest_rate = $rate;
        }

        return view('application.production.entry', compact('voucher_no', 'stockItems', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_no' => 'required|string|max:20|unique:materials_production,voucher_no',
            'date' => 'required|date',
            'production_item' => 'required|integer|exists:stock_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string|max:500',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'required|integer|exists:material_items,id',
            'item.*.production_qty' => 'required|numeric|min:0.01',
            'item.*.rate' => 'nullable|numeric|min:0',
            'item.*.value' => 'nullable|numeric|min:0',
            'item.*.note' => 'nullable|string|max:200',
        ], [
            'voucher_no.required' => 'Voucher number is required.',
            'voucher_no.unique' => 'This voucher number already exists.',
            'production_item.required' => 'Please select the production item (finished good).',
            'production_item.exists' => 'Selected production item does not exist.',
            'quantity.required' => 'Produced quantity is required.',
            'quantity.min' => 'Produced quantity must be greater than 0.',
            'item.required' => 'Please add at least one material item.',
            'item.min' => 'Please add at least one material item.',
            'item.*.item_id.required' => 'Material item is required.',
            'item.*.production_qty.required' => 'Production quantity is required.',
            'item.*.production_qty.min' => 'Production quantity must be greater than 0.',
        ]);

        // Max validation: Verify requested production_qty does not exceed available Floor Stock (Issue to Production quantity)
        $requestedTotals = [];
        foreach ($request->item as $row) {
            $itemId = (int)$row['item_id'];
            $qty = (float)$row['production_qty'];
            $requestedTotals[$itemId] = ($requestedTotals[$itemId] ?? 0) + $qty;
        }

        foreach ($requestedTotals as $itemId => $totQty) {
            $floorStock = self::getFloorStock($itemId);
            if ($totQty > $floorStock) {
                $mat = Materialitems::with('unitDetail')->find($itemId);
                $unitName = ($mat && $mat->unitDetail) ? $mat->unitDetail->name : '';
                $itemName = $mat ? $mat->name : "Item #{$itemId}";
                return back()->withInput()->withErrors([
                    'item' => "Cannot consume {$totQty} {$unitName} for '{$itemName}'. Available issued quantity on production floor is only {$floorStock} {$unitName}. Please issue materials to production first."
                ]);
            }
        }

        DB::transaction(function () use ($request) {
            $voucherNo = trim($request->voucher_no);
            $prodQty = (float)$request->quantity;
            $stockItem = StockItem::with('unitDetail')->find($request->production_item);
            $finishedGoodName = $stockItem ? $stockItem->name : 'Finished Good';
            $fgUnit = ($stockItem && $stockItem->unitDetail) ? $stockItem->unitDetail->name : '';

            $narration = !empty($request->narration) 
                ? trim($request->narration) 
                : "Production of {$prodQty} {$fgUnit} {$finishedGoodName} (Voucher: {$voucherNo})";

            // 1. Create Production Master Record
            $production = MaterialsProduction::create([
                'voucher_no' => $voucherNo,
                'production_item' => $request->production_item,
                'quantity' => $prodQty,
                'date' => $request->date,
                'narration' => $narration,
            ]);

            // 2. Increment finished product stock in stock_items
            StockItem::where('id', $request->production_item)->increment('quantity', $prodQty);

            // 3. Insert child items in master_materials and accumulate total consumption value
            $totalProdValue = 0;
            foreach ($request->item as $row) {
                $itemId = $row['item_id'];
                $itemQty = (float)$row['production_qty'];
                $rate = isset($row['rate']) && $row['rate'] !== '' ? (float)$row['rate'] : 0;
                $value = isset($row['value']) && $row['value'] !== '' ? (float)$row['value'] : round($itemQty * $rate, 3);
                $note = !empty($row['note']) ? trim($row['note']) : null;
                $totalProdValue += $value;

                MasterMaterials::create([
                    'voucher_no' => $voucherNo,
                    'voucher_type' => $this->voucherType,
                    'item_id' => $itemId,
                    'received_qty' => 0,
                    'issue2production_qty' => 0,
                    'production_qty' => $itemQty,
                    'rate' => $rate,
                    'value' => $value,
                    'note' => $note,
                    'date' => $request->date,
                ]);
            }

            // 4. Create Accounting Voucher Entry (Debit: Cost of Goods Sold [222], Credit: Raw Materials/Purchase [2])
            $voucherItems = [
                [
                    'voucherType' => $this->voucherType, // 16: PRD
                    'amount' => $totalProdValue,
                    'drname' => $this->cogsLedgerId,     // 222: Cost of Goods Sold / Consumption
                    'crname' => $this->purchaseLedgerId, // 2: Purchase / Raw Materials
                    'note'   => "Production: {$voucherNo} - {$finishedGoodName} ({$prodQty} {$fgUnit})",
                ]
            ];

            $vCon = new VoucherController;
            $myVoucherRequest = new Request();
            $myVoucherRequest->merge([
                'date' => $request->date,
                'voucherType' => $this->voucherType, // 16
                'voucher_no' => $voucherNo,
                'narration_remarks' => $narration,
                'item' => $voucherItems,
            ]);
            $vCon->newCreate($myVoucherRequest);
        });

        if ($request->input('action') === 'save_and_new') {
            return redirect()
                ->route('inventory-production-entry')
                ->with('success', 'Production entry and accounting voucher saved successfully! You can enter another.');
        }

        return redirect()
            ->route('inventory-production-list')
            ->with('success', 'Production entry and accounting voucher saved successfully!');
    }

    public function edit($id)
    {
        $production = MaterialsProduction::with(['stockItem.unitDetail', 'masterMaterials.materialItem.unitDetail'])->findOrFail($id);
        $prodItems = MasterMaterials::with('materialItem.unitDetail')
            ->where('voucher_no', $production->voucher_no)
            ->get();

        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();
        $rawMaterials = Materialitems::with('unitDetail')->orderBy('name', 'ASC')->get();

        // Floor stocks across system
        $floorStocks = MasterMaterials::select(
                'item_id',
                DB::raw('(COALESCE(SUM(issue2production_qty), 0) - COALESCE(SUM(production_qty), 0)) as floor_stock')
            )
            ->whereNotNull('item_id')
            ->groupBy('item_id')
            ->pluck('floor_stock', 'item_id')
            ->toArray();

        // Old consumption for this specific voucher
        $existingProdByItem = $prodItems->groupBy('item_id')->map->sum('production_qty');

        // Latest MRR rates
        $latestRates = MasterMaterials::where('voucher_type', 14)
            ->where('rate', '>', 0)
            ->orderBy('id', 'desc')
            ->get()
            ->unique('item_id')
            ->pluck('rate', 'item_id')
            ->toArray();

        foreach ($rawMaterials as $mat) {
            $currentFloor = max(0, (float)($floorStocks[$mat->id] ?? 0));
            $usedInThisVoucher = (float)($existingProdByItem[$mat->id] ?? 0);
            $mat->floor_stock = $currentFloor + $usedInThisVoucher;

            $rate = (float)($latestRates[$mat->id] ?? 0);
            if ($rate <= 0 && (float)$mat->opening_stock > 0 && (float)$mat->opening_value > 0) {
                $rate = round((float)$mat->opening_value / (float)$mat->opening_stock, 3);
            }
            $mat->latest_rate = $rate;
        }

        return view('application.production.edit', compact('production', 'prodItems', 'stockItems', 'rawMaterials'));
    }

    public function update(Request $request, $id)
    {
        $production = MaterialsProduction::findOrFail($id);

        $request->validate([
            'voucher_no' => 'required|string|max:20|unique:materials_production,voucher_no,' . $id,
            'date' => 'required|date',
            'production_item' => 'required|integer|exists:stock_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'narration' => 'nullable|string|max:500',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'required|integer|exists:material_items,id',
            'item.*.production_qty' => 'required|numeric|min:0.01',
            'item.*.rate' => 'nullable|numeric|min:0',
            'item.*.value' => 'nullable|numeric|min:0',
            'item.*.note' => 'nullable|string|max:200',
        ], [
            'voucher_no.required' => 'Voucher number is required.',
            'voucher_no.unique' => 'This voucher number already exists.',
            'production_item.required' => 'Please select the production item.',
            'production_item.exists' => 'Selected production item does not exist.',
            'quantity.required' => 'Produced quantity is required.',
            'quantity.min' => 'Produced quantity must be greater than 0.',
            'item.required' => 'Please add at least one material item.',
            'item.min' => 'Please add at least one material item.',
            'item.*.item_id.required' => 'Material item is required.',
            'item.*.production_qty.required' => 'Production quantity is required.',
            'item.*.production_qty.min' => 'Production quantity must be greater than 0.',
        ]);

        // Max validation: Verify requested production_qty does not exceed floor stock (including this voucher's existing consumption)
        $requestedTotals = [];
        foreach ($request->item as $row) {
            $itemId = (int)$row['item_id'];
            $qty = (float)$row['production_qty'];
            $requestedTotals[$itemId] = ($requestedTotals[$itemId] ?? 0) + $qty;
        }

        foreach ($requestedTotals as $itemId => $totQty) {
            $floorStock = self::getFloorStock($itemId, $production->voucher_no);
            if ($totQty > $floorStock) {
                $mat = Materialitems::with('unitDetail')->find($itemId);
                $unitName = ($mat && $mat->unitDetail) ? $mat->unitDetail->name : '';
                $itemName = $mat ? $mat->name : "Item #{$itemId}";
                return back()->withInput()->withErrors([
                    'item' => "Cannot consume {$totQty} {$unitName} for '{$itemName}'. Available issued quantity on production floor is only {$floorStock} {$unitName}."
                ]);
            }
        }

        DB::transaction(function () use ($request, $production) {
            $newVoucherNo = trim($request->voucher_no);
            $newProdItem = $request->production_item;
            $newQty = (float)$request->quantity;

            $stockItem = StockItem::with('unitDetail')->find($newProdItem);
            $finishedGoodName = $stockItem ? $stockItem->name : 'Finished Good';
            $fgUnit = ($stockItem && $stockItem->unitDetail) ? $stockItem->unitDetail->name : '';

            $narration = !empty($request->narration) 
                ? trim($request->narration) 
                : "Production of {$newQty} {$fgUnit} {$finishedGoodName} (Voucher: {$newVoucherNo})";

            // 1. Revert previous accounting voucher entries & closing balances
            $vCon = new VoucherController;
            $oldMasterVouchers = MasterVoucher::where('voucher_no', $production->voucher_no)->get();
            foreach ($oldMasterVouchers as $omv) {
                $vCon->closingBalanceUpdate($omv->debit_head, $omv->amount, '-');
                $vCon->closingBalanceUpdate($omv->credit_head, $omv->amount, '+');
                $omv->delete();
            }
            Voucher::where('voucher_no', $production->voucher_no)->delete();

            // 2. Revert old finished goods stock and apply new finished goods stock
            StockItem::where('id', $production->production_item)->decrement('quantity', $production->quantity);
            StockItem::where('id', $newProdItem)->increment('quantity', $newQty);

            // 3. Delete old master_materials rows
            MasterMaterials::where('voucher_no', $production->voucher_no)->delete();

            // 4. Update materials_production
            $production->update([
                'voucher_no' => $newVoucherNo,
                'production_item' => $newProdItem,
                'quantity' => $newQty,
                'date' => $request->date,
                'narration' => $narration,
            ]);

            // 5. Insert new master_materials rows and calculate total consumed value
            $totalProdValue = 0;
            foreach ($request->item as $row) {
                $itemId = $row['item_id'];
                $itemQty = (float)$row['production_qty'];
                $rate = isset($row['rate']) && $row['rate'] !== '' ? (float)$row['rate'] : 0;
                $value = isset($row['value']) && $row['value'] !== '' ? (float)$row['value'] : round($itemQty * $rate, 3);
                $note = !empty($row['note']) ? trim($row['note']) : null;
                $totalProdValue += $value;

                MasterMaterials::create([
                    'voucher_no' => $newVoucherNo,
                    'voucher_type' => $this->voucherType,
                    'item_id' => $itemId,
                    'received_qty' => 0,
                    'issue2production_qty' => 0,
                    'production_qty' => $itemQty,
                    'rate' => $rate,
                    'value' => $value,
                    'note' => $note,
                    'date' => $request->date,
                ]);
            }

            // 6. Create new accounting voucher entry
            $voucherItems = [
                [
                    'voucherType' => $this->voucherType, // 16: PRD
                    'amount' => $totalProdValue,
                    'drname' => $this->cogsLedgerId,     // 222: Cost of Goods Sold / Consumption
                    'crname' => $this->purchaseLedgerId, // 2: Purchase / Raw Materials
                    'note'   => "Production: {$newVoucherNo} - {$finishedGoodName} ({$newQty} {$fgUnit})",
                ]
            ];

            $myVoucherRequest = new Request();
            $myVoucherRequest->merge([
                'date' => $request->date,
                'voucherType' => $this->voucherType, // 16
                'voucher_no' => $newVoucherNo,
                'narration_remarks' => $narration,
                'item' => $voucherItems,
            ]);
            $vCon->newCreate($myVoucherRequest);
        });

        return redirect()
            ->route('inventory-production-list')
            ->with('success', 'Production entry and accounting voucher updated successfully!');
    }

    public function show($id)
    {
        $production = MaterialsProduction::with(['stockItem.unitDetail', 'masterMaterials.materialItem.unitDetail'])->findOrFail($id);
        $prodItems = $production->masterMaterials;
        $voucher = Voucher::where('voucher_no', $production->voucher_no)->with('MasterVoucher')->first();

        return view('application.production.details', compact('production', 'prodItems', 'voucher'));
    }

    public function destroy($id)
    {
        $production = MaterialsProduction::findOrFail($id);

        DB::transaction(function () use ($production) {
            $vCon = new VoucherController;

            // 1. Revert previous accounting voucher entries & closing balances
            $oldMasterVouchers = MasterVoucher::where('voucher_no', $production->voucher_no)->get();
            foreach ($oldMasterVouchers as $omv) {
                $vCon->closingBalanceUpdate($omv->debit_head, $omv->amount, '-');
                $vCon->closingBalanceUpdate($omv->credit_head, $omv->amount, '+');
                $omv->delete();
            }
            Voucher::where('voucher_no', $production->voucher_no)->delete();

            // 2. Decrement finished good stock
            StockItem::where('id', $production->production_item)->decrement('quantity', $production->quantity);

            // 3. Delete detail rows
            MasterMaterials::where('voucher_no', $production->voucher_no)->delete();

            // 4. Delete production row
            $production->delete();
        });

        return redirect()
            ->route('inventory-production-list')
            ->with('success', 'Production record and accounting voucher deleted successfully!');
    }

    public static function getFloorStock($itemId, $excludeVoucherNo = null)
    {
        $query = MasterMaterials::where('item_id', $itemId);
        if ($excludeVoucherNo) {
            $query->where('voucher_no', '!=', $excludeVoucherNo);
        }
        $res = $query->selectRaw('COALESCE(SUM(issue2production_qty), 0) - COALESCE(SUM(production_qty), 0) as balance')
            ->value('balance');

        return max(0, (float)$res);
    }

    public static function generateVoucherNo($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $ym = date('ym', strtotime($date));
        $prefix = 'PRD';

        $pattern = $prefix . $ym . '-%';
        $lastRecord = MaterialsProduction::where('voucher_no', 'like', $pattern)
            ->orderByRaw('CAST(SUBSTRING_INDEX(voucher_no, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $sl = 1;
        if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->voucher_no, $matches)) {
            $sl = (int)$matches[1] + 1;
        }

        return $prefix . $ym . '-' . $sl;
    }
}
