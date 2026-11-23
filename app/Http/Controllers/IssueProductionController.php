<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialsIssue2production;
use App\Models\MasterMaterials;
use App\Models\Materialitems;

class IssueProductionController extends Controller
{
    protected $voucherType = 15; // Issue to Production voucher type

    public function index(Request $request)
    {
        $query = MaterialsIssue2production::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('voucher_no', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $issues = $query->orderBy('id', 'DESC')->paginate(25);

        // Fetch aggregated values from master_materials for each voucher_no
        $voucherNos = $issues->pluck('voucher_no')->filter()->all();
        $aggregates = [];
        if (!empty($voucherNos)) {
            $aggregates = MasterMaterials::select(
                    'voucher_no',
                    DB::raw('COUNT(*) as total_items'),
                    DB::raw('SUM(issue2production_qty) as total_issue_qty')
                )
                ->whereIn('voucher_no', $voucherNos)
                ->groupBy('voucher_no')
                ->get()
                ->keyBy('voucher_no');
        }

        return view('application.issue_production.list', compact('issues', 'aggregates'));
    }

    public function create()
    {
        $voucher_no = self::generateVoucherNo();
        $rawMaterials = Materialitems::with('unitDetail')->orderBy('name', 'ASC')->get();

        return view('application.issue_production.entry', compact('voucher_no', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_no' => 'required|string|max:20|unique:materials_issue2production,voucher_no',
            'date' => 'required|date',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'required|integer|exists:material_items,id',
            'item.*.issue2production_qty' => 'required|numeric|min:0.01',
            'item.*.note' => 'nullable|string|max:200',
        ], [
            'voucher_no.required' => 'Voucher number is required.',
            'voucher_no.unique' => 'This voucher number already exists.',
            'item.required' => 'Please add at least one material item.',
            'item.min' => 'Please add at least one material item.',
            'item.*.item_id.required' => 'Material item is required.',
            'item.*.issue2production_qty.required' => 'Issue quantity is required.',
            'item.*.issue2production_qty.min' => 'Issue quantity must be greater than 0.',
        ]);

        // Validate available store stock for each item
        $requestedTotals = [];
        foreach ($request->item as $row) {
            $itemId = (int)$row['item_id'];
            $qty = (float)$row['issue2production_qty'];
            $requestedTotals[$itemId] = ($requestedTotals[$itemId] ?? 0) + $qty;
        }

        foreach ($requestedTotals as $itemId => $totQty) {
            $mat = Materialitems::with('unitDetail')->find($itemId);
            $currentStock = $mat ? (float)$mat->quantity : 0;
            if ($totQty > $currentStock) {
                $unitName = ($mat && $mat->unitDetail) ? $mat->unitDetail->name : '';
                $itemName = $mat ? $mat->name : "Item #{$itemId}";
                return back()->withInput()->withErrors([
                    'item' => "Cannot issue {$totQty} {$unitName} for '{$itemName}'. Available store stock is only {$currentStock} {$unitName}."
                ]);
            }
        }

        DB::transaction(function () use ($request) {
            $voucherNo = trim($request->voucher_no);

            $issue = MaterialsIssue2production::create([
                'voucher_no' => $voucherNo,
                'date' => $request->date,
            ]);

            // Insert child items in master_materials and deduct stock from raw materials
            foreach ($request->item as $row) {
                $itemId = $row['item_id'];
                $qty = (float)$row['issue2production_qty'];
                $note = !empty($row['note']) ? trim($row['note']) : null;

                MasterMaterials::create([
                    'voucher_no' => $voucherNo,
                    'voucher_type' => $this->voucherType,
                    'item_id' => $itemId,
                    'received_qty' => 0,
                    'issue2production_qty' => $qty,
                    'production_qty' => 0,
                    'rate' => 0,
                    'value' => 0,
                    'note' => $note,
                    'date' => $request->date,
                ]);

                // Deduct stock from material_items
                Materialitems::where('id', $itemId)->decrement('quantity', $qty);
            }
        });

        if ($request->input('action') === 'save_and_new') {
            return redirect()
                ->route('inventory-issue-production-entry')
                ->with('success', 'Issue to Production entry saved successfully! You can enter another.');
        }

        return redirect()
            ->route('inventory-issue-production-list')
            ->with('success', 'Issue to Production entry saved successfully!');
    }

    public function edit($id)
    {
        $issue = MaterialsIssue2production::with('masterMaterials.materialItem.unitDetail')->findOrFail($id);
        $issueItems = MasterMaterials::with('materialItem.unitDetail')
            ->where('voucher_no', $issue->voucher_no)
            ->get();

        $existingVoucherIssues = $issueItems->groupBy('item_id')->map->sum('issue2production_qty');

        $rawMaterials = Materialitems::with('unitDetail')->orderBy('name', 'ASC')->get();
        foreach ($rawMaterials as $mat) {
            $mat->effective_stock = (float)$mat->quantity + (float)($existingVoucherIssues[$mat->id] ?? 0);
        }

        return view('application.issue_production.edit', compact('issue', 'issueItems', 'rawMaterials'));
    }

    public function update(Request $request, $id)
    {
        $issue = MaterialsIssue2production::findOrFail($id);

        $request->validate([
            'voucher_no' => 'required|string|max:20|unique:materials_issue2production,voucher_no,' . $id,
            'date' => 'required|date',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'required|integer|exists:material_items,id',
            'item.*.issue2production_qty' => 'required|numeric|min:0.01',
            'item.*.note' => 'nullable|string|max:200',
        ], [
            'voucher_no.required' => 'Voucher number is required.',
            'voucher_no.unique' => 'This voucher number already exists.',
            'item.required' => 'Please add at least one material item.',
            'item.min' => 'Please add at least one material item.',
            'item.*.item_id.required' => 'Material item is required.',
            'item.*.issue2production_qty.required' => 'Issue quantity is required.',
            'item.*.issue2production_qty.min' => 'Issue quantity must be greater than 0.',
        ]);

        // Validate available stock including items previously issued in this voucher
        $oldMaterials = MasterMaterials::where('voucher_no', $issue->voucher_no)->get();
        $oldVoucherIssues = $oldMaterials->groupBy('item_id')->map->sum('issue2production_qty');

        $requestedTotals = [];
        foreach ($request->item as $row) {
            $itemId = (int)$row['item_id'];
            $qty = (float)$row['issue2production_qty'];
            $requestedTotals[$itemId] = ($requestedTotals[$itemId] ?? 0) + $qty;
        }

        foreach ($requestedTotals as $itemId => $totQty) {
            $mat = Materialitems::with('unitDetail')->find($itemId);
            $currentStock = $mat ? (float)$mat->quantity : 0;
            $availableStock = $currentStock + (float)($oldVoucherIssues[$itemId] ?? 0);
            if ($totQty > $availableStock) {
                $unitName = ($mat && $mat->unitDetail) ? $mat->unitDetail->name : '';
                $itemName = $mat ? $mat->name : "Item #{$itemId}";
                return back()->withInput()->withErrors([
                    'item' => "Cannot issue {$totQty} {$unitName} for '{$itemName}'. Available store stock is only {$availableStock} {$unitName}."
                ]);
            }
        }

        DB::transaction(function () use ($request, $issue) {
            $newVoucherNo = trim($request->voucher_no);

            // 1. Revert previous raw material stock deductions
            $oldMaterials = MasterMaterials::where('voucher_no', $issue->voucher_no)->get();
            foreach ($oldMaterials as $old) {
                Materialitems::where('id', $old->item_id)->increment('quantity', $old->issue2production_qty);
            }

            // 2. Delete old detail records
            MasterMaterials::where('voucher_no', $issue->voucher_no)->delete();

            // 3. Update master record
            $issue->update([
                'voucher_no' => $newVoucherNo,
                'date' => $request->date,
            ]);

            // 4. Create new detail records and deduct stock
            foreach ($request->item as $row) {
                $itemId = $row['item_id'];
                $qty = (float)$row['issue2production_qty'];
                $note = !empty($row['note']) ? trim($row['note']) : null;

                MasterMaterials::create([
                    'voucher_no' => $newVoucherNo,
                    'voucher_type' => $this->voucherType,
                    'item_id' => $itemId,
                    'received_qty' => 0,
                    'issue2production_qty' => $qty,
                    'production_qty' => 0,
                    'rate' => 0,
                    'value' => 0,
                    'note' => $note,
                    'date' => $request->date,
                ]);

                Materialitems::where('id', $itemId)->decrement('quantity', $qty);
            }
        });

        return redirect()
            ->route('inventory-issue-production-list')
            ->with('success', 'Issue to Production entry updated successfully!');
    }

    public function show($id)
    {
        $issue = MaterialsIssue2production::with('masterMaterials.materialItem.unitDetail')->findOrFail($id);
        $issueItems = $issue->masterMaterials;

        return view('application.issue_production.details', compact('issue', 'issueItems'));
    }

    public function destroy($id)
    {
        $issue = MaterialsIssue2production::findOrFail($id);

        DB::transaction(function () use ($issue) {
            // Revert raw material stock deductions
            $items = MasterMaterials::where('voucher_no', $issue->voucher_no)->get();
            foreach ($items as $item) {
                Materialitems::where('id', $item->item_id)->increment('quantity', $item->issue2production_qty);
            }

            MasterMaterials::where('voucher_no', $issue->voucher_no)->delete();
            $issue->delete();
        });

        return redirect()
            ->route('inventory-issue-production-list')
            ->with('success', 'Issue to Production record deleted successfully!');
    }

    public static function generateVoucherNo($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $ym = date('ym', strtotime($date));
        $prefix = 'ITP';

        $pattern = $prefix . $ym . '-%';
        $lastRecord = MaterialsIssue2production::where('voucher_no', 'like', $pattern)
            ->orderByRaw('CAST(SUBSTRING_INDEX(voucher_no, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $sl = 1;
        if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->voucher_no, $matches)) {
            $sl = (int)$matches[1] + 1;
        }

        return $prefix . $ym . '-' . $sl;
    }
}
