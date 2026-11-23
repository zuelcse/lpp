<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialsReceivedMrr;
use App\Models\MasterMaterials;
use App\Models\Materialitems;
use App\Models\Ledger;
use App\Models\VoucherType;
use App\Models\Voucher;
use App\Models\MasterVoucher;
use App\Http\Controllers\VoucherController;

class MrrController extends Controller
{
    protected $voucherType = 14; // MRR voucher type
    protected $purchaseLedgerId = 2; // Default Purchase ledger ID
    protected $cashLedgerId = 1; // Cash in Hand ledger ID

    public function index(Request $request)
    {
        $query = MaterialsReceivedMrr::with('supplier');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('voucher_no', 'like', "%{$search}%")
                  ->orWhere('challan_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('alias', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $mrrs = $query->orderBy('id', 'DESC')->paginate(25);

        // Fetch aggregated values from master_materials for each MRR voucher_no
        $voucherNos = $mrrs->pluck('voucher_no')->filter()->all();
        $aggregates = [];
        if (!empty($voucherNos)) {
            $aggregates = MasterMaterials::select(
                    'voucher_no',
                    DB::raw('COUNT(*) as total_items'),
                    DB::raw('SUM(received_qty) as total_qty'),
                    DB::raw('SUM(value) as total_value')
                )
                ->whereIn('voucher_no', $voucherNos)
                ->groupBy('voucher_no')
                ->get()
                ->keyBy('voucher_no');
        }

        $suppliers = Ledger::orderBy('name', 'ASC')->get();

        return view('application.mrr.list', compact('mrrs', 'aggregates', 'suppliers'));
    }

    public function create()
    {
        $voucher_no = self::generateVoucherNo();
        $suppliers = Ledger::orderBy('name', 'ASC')->get();
        $rawMaterials = Materialitems::with('unitDetail')->orderBy('name', 'ASC')->get();

        return view('application.mrr.entry', compact('voucher_no', 'suppliers', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_no' => 'required|string|max:50|unique:materials_received_mrr,voucher_no|unique:voucher,voucher_no',
            'date' => 'required|date',
            'supplier_id' => 'required|integer|exists:ledgers,id',
            'challan_no' => 'nullable|string|max:50',
            'challan_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'narration_remarks' => 'nullable|string|max:500',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'required|integer|exists:material_items,id',
            'item.*.received_qty' => 'required|numeric|min:0.01',
            'item.*.rate' => 'nullable|numeric|min:0',
            'item.*.value' => 'nullable|numeric|min:0',
            'item.*.note' => 'nullable|string|max:200',
        ], [
            'voucher_no.required' => 'Voucher number is required.',
            'voucher_no.unique' => 'This voucher number already exists.',
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier does not exist.',
            'item.required' => 'Please add at least one material item.',
            'item.min' => 'Please add at least one material item.',
            'item.*.item_id.required' => 'Item is required.',
            'item.*.received_qty.required' => 'Received quantity is required.',
            'item.*.received_qty.min' => 'Received quantity must be greater than 0.',
        ]);

        DB::transaction(function () use ($request) {
            $voucherNo = trim($request->voucher_no);
            $paidAmount = isset($request->paid_amount) && $request->paid_amount !== '' ? (float)$request->paid_amount : 0;
            $narration = !empty($request->narration_remarks) 
                ? trim($request->narration_remarks) 
                : (!empty($request->narration) ? trim($request->narration) : 'Material Receive Report');

            // 1. Create MRR Record
            $mrr = MaterialsReceivedMrr::create([
                'voucher_no' => $voucherNo,
                'supplier_id' => $request->supplier_id,
                'challan_no' => !empty($request->challan_no) ? trim($request->challan_no) : null,
                'challan_date' => !empty($request->challan_date) ? $request->challan_date : null,
                'date' => $request->date,
                'paid_amount' => $paidAmount,
                'narration' => $narration,
            ]);

            $totalValue = 0;

            // 2. Insert items into master_materials and update raw material stock
            foreach ($request->item as $row) {
                $itemId = $row['item_id'];
                $qty = (float)$row['received_qty'];
                $rate = isset($row['rate']) && $row['rate'] !== '' ? (float)$row['rate'] : 0;
                $value = isset($row['value']) && $row['value'] !== '' ? (float)$row['value'] : round($qty * $rate, 3);
                $note = !empty($row['note']) ? trim($row['note']) : null;
                $totalValue += $value;

                MasterMaterials::create([
                    'voucher_no' => $voucherNo,
                    'voucher_type' => $this->voucherType,
                    'item_id' => $itemId,
                    'received_qty' => $qty,
                    'issue2production_qty' => 0,
                    'production_qty' => 0,
                    'rate' => $rate,
                    'value' => $value,
                    'note' => $note,
                    'date' => $request->date,
                ]);

                // Update stock in material_items
                Materialitems::where('id', $itemId)->increment('quantity', $qty);
            }

            // 3. Create Accounting Voucher Entry (matching Sales/Purchase entry)
            $voucherItems = [];

            // A. Material Purchase Entry: Debit Purchase Ledger, Credit Supplier Ledger
            $voucherItems[] = [
                'amount' => $totalValue,
                'drname' => $this->purchaseLedgerId, // Purchase Ledger (ID: 2)
                'crname' => $request->supplier_id,   // Supplier Ledger
                'note'   => !empty($request->challan_no) 
                    ? ('MRR: ' . $voucherNo . ' (Challan: ' . $request->challan_no . ')') 
                    : ('MRR: ' . $voucherNo)
            ];

            // B. If Paid Amount > 0: Cash Payment Entry (Debit Supplier, Credit Cash in Hand)
            if ($paidAmount > 0) {
                $voucherItems[] = [
                    'voucherType' => 9, // 9: Cash Payment (CPV)
                    'amount' => $paidAmount,
                    'drname' => $request->supplier_id, // Supplier Ledger
                    'crname' => $this->cashLedgerId,   // Cash in Hand (ID: 1)
                    'note'   => 'Cash Payment to Supplier for MRR: ' . $voucherNo
                ];
            }

            $vCon = new VoucherController;
            $myVoucherRequest = new Request();
            $myVoucherRequest->merge([
                'date' => $request->date,
                'voucherType' => $this->voucherType, // 14: MRR
                'voucher_no' => $voucherNo,
                'narration_remarks' => $narration,
                'item' => $voucherItems
            ]);
            $vCon->newCreate($myVoucherRequest);
        });

        if ($request->input('action') === 'save_and_new') {
            return redirect()
                ->route('inventory-mrr-entry')
                ->with('success', 'MRR and Voucher entry saved successfully! You can enter another MRR.');
        }

        return redirect()
            ->route('inventory-mrr-list')
            ->with('success', 'MRR and Voucher entry saved successfully!');
    }

    public function edit($id)
    {
        $mrr = MaterialsReceivedMrr::with('supplier')->findOrFail($id);
        $mrrItems = MasterMaterials::with('materialItem.unitDetail')
            ->where('voucher_no', $mrr->voucher_no)
            ->get();

        $suppliers = Ledger::orderBy('name', 'ASC')->get();
        $rawMaterials = Materialitems::with('unitDetail')->orderBy('name', 'ASC')->get();

        return view('application.mrr.edit', compact('mrr', 'mrrItems', 'suppliers', 'rawMaterials'));
    }

    public function update(Request $request, $id)
    {
        $mrr = MaterialsReceivedMrr::findOrFail($id);

        $request->validate([
            'voucher_no' => 'required|string|max:50|unique:materials_received_mrr,voucher_no,' . $id,
            'date' => 'required|date',
            'supplier_id' => 'required|integer|exists:ledgers,id',
            'challan_no' => 'nullable|string|max:50',
            'challan_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'narration_remarks' => 'nullable|string|max:500',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'required|integer|exists:material_items,id',
            'item.*.received_qty' => 'required|numeric|min:0.01',
            'item.*.rate' => 'nullable|numeric|min:0',
            'item.*.value' => 'nullable|numeric|min:0',
            'item.*.note' => 'nullable|string|max:200',
        ], [
            'voucher_no.required' => 'Voucher number is required.',
            'voucher_no.unique' => 'This voucher number already exists.',
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier does not exist.',
            'item.required' => 'Please add at least one material item.',
            'item.min' => 'Please add at least one material item.',
            'item.*.item_id.required' => 'Item is required.',
            'item.*.received_qty.required' => 'Received quantity is required.',
            'item.*.received_qty.min' => 'Received quantity must be greater than 0.',
        ]);

        DB::transaction(function () use ($request, $mrr) {
            $vCon = new VoucherController;

            // 1. Revert previous voucher and master_voucher entries & closing balances
            $oldMasterVouchers = MasterVoucher::where('voucher_no', $mrr->voucher_no)->get();
            foreach ($oldMasterVouchers as $omv) {
                $vCon->closingBalanceUpdate($omv->debit_head, $omv->amount, '-');
                $vCon->closingBalanceUpdate($omv->credit_head, $omv->amount, '+');
                $omv->delete();
            }
            Voucher::where('voucher_no', $mrr->voucher_no)->delete();

            // 2. Revert previous stock quantities from material_items
            $oldMaterials = MasterMaterials::where('voucher_no', $mrr->voucher_no)->get();
            foreach ($oldMaterials as $old) {
                Materialitems::where('id', $old->item_id)->decrement('quantity', $old->received_qty);
            }

            // 3. Delete old detail records
            MasterMaterials::where('voucher_no', $mrr->voucher_no)->delete();

            // 4. Update master MRR record
            $newVoucherNo = trim($request->voucher_no);
            $paidAmount = isset($request->paid_amount) && $request->paid_amount !== '' ? (float)$request->paid_amount : 0;
            $narration = !empty($request->narration_remarks) 
                ? trim($request->narration_remarks) 
                : (!empty($request->narration) ? trim($request->narration) : 'Material Receive Report');

            $mrr->update([
                'voucher_no' => $newVoucherNo,
                'supplier_id' => $request->supplier_id,
                'challan_no' => !empty($request->challan_no) ? trim($request->challan_no) : null,
                'challan_date' => !empty($request->challan_date) ? $request->challan_date : null,
                'date' => $request->date,
                'paid_amount' => $paidAmount,
                'narration' => $narration,
            ]);

            // 5. Create new detail records and update stock
            $totalValue = 0;
            foreach ($request->item as $row) {
                $itemId = $row['item_id'];
                $qty = (float)$row['received_qty'];
                $rate = isset($row['rate']) && $row['rate'] !== '' ? (float)$row['rate'] : 0;
                $value = isset($row['value']) && $row['value'] !== '' ? (float)$row['value'] : round($qty * $rate, 3);
                $note = !empty($row['note']) ? trim($row['note']) : null;
                $totalValue += $value;

                MasterMaterials::create([
                    'voucher_no' => $newVoucherNo,
                    'voucher_type' => $this->voucherType,
                    'item_id' => $itemId,
                    'received_qty' => $qty,
                    'issue2production_qty' => 0,
                    'production_qty' => 0,
                    'rate' => $rate,
                    'value' => $value,
                    'note' => $note,
                    'date' => $request->date,
                ]);

                Materialitems::where('id', $itemId)->increment('quantity', $qty);
            }

            // 6. Create new Voucher entries
            $voucherItems = [];
            $voucherItems[] = [
                'amount' => $totalValue,
                'drname' => $this->purchaseLedgerId, // Purchase Ledger (ID: 2)
                'crname' => $request->supplier_id,   // Supplier Ledger
                'note'   => !empty($request->challan_no) 
                    ? ('MRR: ' . $newVoucherNo . ' (Challan: ' . $request->challan_no . ')') 
                    : ('MRR: ' . $newVoucherNo)
            ];

            if ($paidAmount > 0) {
                $voucherItems[] = [
                    'voucherType' => 9, // 9: Cash Payment
                    'amount' => $paidAmount,
                    'drname' => $request->supplier_id, // Supplier Ledger
                    'crname' => $this->cashLedgerId,   // Cash in Hand (ID: 1)
                    'note'   => 'Cash Payment to Supplier for MRR: ' . $newVoucherNo
                ];
            }

            $myVoucherRequest = new Request();
            $myVoucherRequest->merge([
                'date' => $request->date,
                'voucherType' => $this->voucherType, // 14: MRR
                'voucher_no' => $newVoucherNo,
                'narration_remarks' => $narration,
                'item' => $voucherItems
            ]);
            $vCon->newCreate($myVoucherRequest);
        });

        return redirect()
            ->route('inventory-mrr-list')
            ->with('success', 'MRR and Voucher updated successfully!');
    }

    public function show($id)
    {
        $mrr = MaterialsReceivedMrr::with(['supplier', 'masterMaterials.materialItem.unitDetail'])->findOrFail($id);
        $mrrItems = $mrr->masterMaterials;
        $voucher = Voucher::where('voucher_no', $mrr->voucher_no)->with('MasterVoucher')->first();

        return view('application.mrr.details', compact('mrr', 'mrrItems', 'voucher'));
    }

    public function destroy($id)
    {
        $mrr = MaterialsReceivedMrr::findOrFail($id);

        DB::transaction(function () use ($mrr) {
            $vCon = new VoucherController;

            // 1. Revert previous voucher and master_voucher entries & closing balances
            $oldMasterVouchers = MasterVoucher::where('voucher_no', $mrr->voucher_no)->get();
            foreach ($oldMasterVouchers as $omv) {
                $vCon->closingBalanceUpdate($omv->debit_head, $omv->amount, '-');
                $vCon->closingBalanceUpdate($omv->credit_head, $omv->amount, '+');
                $omv->delete();
            }
            Voucher::where('voucher_no', $mrr->voucher_no)->delete();

            // 2. Revert raw materials stock
            $items = MasterMaterials::where('voucher_no', $mrr->voucher_no)->get();
            foreach ($items as $item) {
                Materialitems::where('id', $item->item_id)->decrement('quantity', $item->received_qty);
            }
            MasterMaterials::where('voucher_no', $mrr->voucher_no)->delete();

            // 3. Delete MRR
            $mrr->delete();
        });

        return redirect()
            ->route('inventory-mrr-list')
            ->with('success', 'MRR and Voucher deleted successfully!');
    }

    public static function generateVoucherNo($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $ym = date('ym', strtotime($date));
        $prefix = 'MRR';

        $pattern = $prefix . $ym . '-%';
        $lastMrr = MaterialsReceivedMrr::where('voucher_no', 'like', $pattern)
            ->orderByRaw('CAST(SUBSTRING_INDEX(voucher_no, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $sl = 1;
        if ($lastMrr && preg_match('/-(\d+)$/', $lastMrr->voucher_no, $matches)) {
            $sl = (int)$matches[1] + 1;
        }

        return $prefix . $ym . '-' . $sl;
    }
}
