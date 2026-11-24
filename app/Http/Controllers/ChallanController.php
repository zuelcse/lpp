<?php

namespace App\Http\Controllers;

use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Ledger;
use App\Models\Sales;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ChallanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Challan::with(['ledger', 'sales'])
            ->withCount('items')
            ->withSum('items as total_qty', 'quantity');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('challan_no', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%")
                    ->orWhere('vehicle_no', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhereHas('ledger', function ($lq) use ($search) {
                        $lq->where('name', 'like', "%{$search}%")
                            ->orWhere('alias', 'like', "%{$search}%");
                    })
                    ->orWhereHas('sales', function ($sq) use ($search) {
                        $sq->where('voucher_no', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('ledger_id')) {
            $query->where('ledger_id', $request->ledger_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('challan_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('challan_date', '<=', $request->date_to);
        }

        $challans = $query->orderBy('challan_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(25);

        $ledgers = Ledger::orderBy('name', 'ASC')->get();
        $statuses = Challan::STATUSES;

        return view('application.challan.list', compact('challans', 'ledgers', 'statuses'));
    }

    public function create(Request $request)
    {
        $challan_no = self::generateChallanNo();
        $ledgers = Ledger::orderBy('name', 'ASC')->get();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();
        $salesList = Sales::with('Ledger')->orderBy('id', 'DESC')->limit(200)->get();
        $statuses = Challan::STATUSES;

        $selectedSale = null;
        if ($request->filled('sales_id')) {
            $selectedSale = $this->formatSalePayload($request->sales_id);
        }

        return view('application.challan.create', compact(
            'challan_no',
            'ledgers',
            'stockItems',
            'salesList',
            'statuses',
            'selectedSale'
        ));
    }

    public function getSalesInfo($salesId)
    {
        $payload = $this->formatSalePayload($salesId);

        if (!$payload) {
            return response()->json(['status' => false, 'message' => 'Sales invoice not found.'], 404);
        }

        return response()->json(['status' => true, 'data' => $payload]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateChallan($request);
        $preparedItems = $this->prepareItems($validated['item']);

        $challan = DB::transaction(function () use ($request, $validated, $preparedItems) {
            $status = $validated['status'] ?? 'Pending';
            $deductStock = ($status !== 'Cancelled') && $request->boolean('deduct_stock') ? 1 : 0;

            $challan = Challan::create([
                'challan_no' => trim($validated['challan_no']),
                'challan_date' => $validated['challan_date'],
                'sales_id' => !empty($validated['sales_id']) ? (int) $validated['sales_id'] : null,
                'ledger_id' => (int) $validated['ledger_id'],
                'delivery_address' => $this->nullIfEmpty($validated['delivery_address'] ?? null),
                'contact_person' => $this->nullIfEmpty($validated['contact_person'] ?? null),
                'contact_phone' => $this->nullIfEmpty($validated['contact_phone'] ?? null),
                'driver_name' => $this->nullIfEmpty($validated['driver_name'] ?? null),
                'vehicle_no' => $this->nullIfEmpty($validated['vehicle_no'] ?? null),
                'transport_name' => $this->nullIfEmpty($validated['transport_name'] ?? null),
                'reference_no' => $this->nullIfEmpty($validated['reference_no'] ?? null),
                'status' => $status,
                'deduct_stock' => $deductStock,
                'narration' => $this->nullIfEmpty($validated['narration'] ?? null),
                'user_id' => Auth::id(),
            ]);

            $this->syncChallanItems($challan, $preparedItems, (bool) $deductStock);

            return $challan;
        });

        if ($request->input('action') === 'save_and_new') {
            return redirect()
                ->route('challan-create')
                ->with('success', 'Challan ' . $challan->challan_no . ' saved successfully! You can create another.');
        }

        return redirect()
            ->route('challan-details', $challan->id)
            ->with('success', 'Challan ' . $challan->challan_no . ' created successfully!');
    }

    public function show($id)
    {
        $challan = Challan::with(['ledger', 'sales', 'items.stockItem.unitDetail', 'user'])->findOrFail($id);
        $company = DB::table('general_settings')->first() ?? (object) [
            'site_name' => config('variables.templateName', 'Trading Solutions'),
            'company_address' => '',
            'company_phone' => '',
            'company_email' => '',
        ];
        $statuses = Challan::STATUSES;

        return view('application.challan.details', compact('challan', 'company', 'statuses'));
    }

    public function edit($id)
    {
        $challan = Challan::with(['items.stockItem.unitDetail', 'ledger', 'sales'])->findOrFail($id);
        $ledgers = Ledger::orderBy('name', 'ASC')->get();
        $stockItems = StockItem::with('unitDetail')->orderBy('name', 'ASC')->get();
        $salesList = Sales::with('Ledger')->orderBy('id', 'DESC')->limit(200)->get();
        $statuses = Challan::STATUSES;

        return view('application.challan.edit', compact(
            'challan',
            'ledgers',
            'stockItems',
            'salesList',
            'statuses'
        ));
    }

    public function update(Request $request, $id)
    {
        $challan = Challan::with('items')->findOrFail($id);
        $validated = $this->validateChallan($request, $id);
        $preparedItems = $this->prepareItems($validated['item']);

        DB::transaction(function () use ($request, $challan, $validated, $preparedItems) {
            if ((int) $challan->deduct_stock === 1) {
                $this->adjustStock($challan->items, 'increment');
            }

            $status = $validated['status'] ?? $challan->status;
            $deductStock = ($status !== 'Cancelled') && $request->boolean('deduct_stock') ? 1 : 0;

            $challan->update([
                'challan_no' => trim($validated['challan_no']),
                'challan_date' => $validated['challan_date'],
                'sales_id' => !empty($validated['sales_id']) ? (int) $validated['sales_id'] : null,
                'ledger_id' => (int) $validated['ledger_id'],
                'delivery_address' => $this->nullIfEmpty($validated['delivery_address'] ?? null),
                'contact_person' => $this->nullIfEmpty($validated['contact_person'] ?? null),
                'contact_phone' => $this->nullIfEmpty($validated['contact_phone'] ?? null),
                'driver_name' => $this->nullIfEmpty($validated['driver_name'] ?? null),
                'vehicle_no' => $this->nullIfEmpty($validated['vehicle_no'] ?? null),
                'transport_name' => $this->nullIfEmpty($validated['transport_name'] ?? null),
                'reference_no' => $this->nullIfEmpty($validated['reference_no'] ?? null),
                'status' => $status,
                'deduct_stock' => $deductStock,
                'narration' => $this->nullIfEmpty($validated['narration'] ?? null),
            ]);

            $challan->items()->delete();
            $this->syncChallanItems($challan, $preparedItems, (bool) $deductStock);
        });

        return redirect()
            ->route('challan-details', $challan->id)
            ->with('success', 'Challan ' . $challan->challan_no . ' updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(Challan::STATUSES)],
        ]);

        $challan = Challan::with('items')->findOrFail($id);

        DB::transaction(function () use ($challan, $request) {
            $newStatus = $request->status;
            $updates = ['status' => $newStatus];

            if ($newStatus === 'Cancelled' && (int) $challan->deduct_stock === 1) {
                $this->adjustStock($challan->items, 'increment');
                $updates['deduct_stock'] = 0;
            }

            $challan->update($updates);
        });

        return redirect()
            ->back()
            ->with('success', 'Challan status updated to ' . $request->status . '.');
    }

    public function destroy($id)
    {
        $challan = Challan::with('items')->findOrFail($id);

        DB::transaction(function () use ($challan) {
            if ((int) $challan->deduct_stock === 1) {
                $this->adjustStock($challan->items, 'increment');
            }
            $challan->delete();
        });

        return redirect()
            ->route('challan-list')
            ->with('success', 'Challan deleted successfully!');
    }

    public static function generateChallanNo($date = null): string
    {
        $date = $date ?: date('Y-m-d');
        $ym = date('ym', strtotime($date));
        $prefix = 'CHN';
        $pattern = $prefix . $ym . '-%';

        $last = Challan::where('challan_no', 'like', $pattern)
            ->orderByRaw('CAST(SUBSTRING_INDEX(challan_no, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $sl = 1;
        if ($last && preg_match('/-(\d+)$/', $last->challan_no, $matches)) {
            $sl = (int) $matches[1] + 1;
        }

        return $prefix . $ym . '-' . $sl;
    }

    protected function validateChallan(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'challan_no' => [
                'required',
                'string',
                'max:50',
                Rule::unique('challans', 'challan_no')->ignore($ignoreId),
            ],
            'challan_date' => 'required|date',
            'sales_id' => 'nullable|integer|exists:sales,id',
            'ledger_id' => 'required|integer|exists:ledgers,id',
            'delivery_address' => 'nullable|string|max:500',
            'contact_person' => 'nullable|string|max:150',
            'contact_phone' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:150',
            'vehicle_no' => 'nullable|string|max:100',
            'transport_name' => 'nullable|string|max:150',
            'reference_no' => 'nullable|string|max:100',
            'status' => ['nullable', Rule::in(Challan::STATUSES)],
            'narration' => 'nullable|string|max:1000',
            'item' => 'required|array|min:1',
            'item.*.item_id' => 'nullable|integer|exists:stock_items,id',
            'item.*.item_name' => 'required_without:item.*.item_id|nullable|string|max:255',
            'item.*.unit_name' => 'nullable|string|max:50',
            'item.*.quantity' => 'required|numeric|min:0.01',
            'item.*.note' => 'nullable|string|max:255',
        ], [
            'challan_no.required' => 'Challan number is required.',
            'challan_no.unique' => 'This challan number already exists.',
            'ledger_id.required' => 'Please select a customer / party.',
            'item.required' => 'Please add at least one item.',
            'item.min' => 'Please add at least one item.',
            'item.*.item_name.required_without' => 'Item selection or description is required.',
            'item.*.quantity.required' => 'Quantity is required.',
            'item.*.quantity.min' => 'Quantity must be greater than 0.',
        ]);
    }

    protected function prepareItems(array $rawItems): array
    {
        $itemIds = collect($rawItems)->pluck('item_id')->filter()->unique()->all();
        $stockMap = !empty($itemIds)
            ? StockItem::with('unitDetail')->whereIn('id', $itemIds)->get()->keyBy('id')
            : collect();

        $prepared = [];
        foreach ($rawItems as $row) {
            $itemId = !empty($row['item_id']) ? (int) $row['item_id'] : null;
            $stock = $itemId ? $stockMap->get($itemId) : null;

            $itemName = trim((string) ($row['item_name'] ?? ''));
            if ($itemName === '' && $stock) {
                $itemName = $stock->name;
            }

            $unitName = trim((string) ($row['unit_name'] ?? ''));
            if ($unitName === '' && $stock && $stock->unitDetail) {
                $unitName = $stock->unitDetail->name;
            }

            $prepared[] = [
                'item_id' => $itemId,
                'item_name' => $itemName,
                'unit_name' => $unitName !== '' ? $unitName : null,
                'quantity' => (float) $row['quantity'],
                'note' => $this->nullIfEmpty($row['note'] ?? null),
            ];
        }

        return $prepared;
    }

    protected function syncChallanItems(Challan $challan, array $preparedItems, bool $deductStock): void
    {
        $createdItems = [];
        foreach ($preparedItems as $row) {
            $createdItems[] = ChallanItem::create(array_merge($row, ['challan_id' => $challan->id]));
        }

        if ($deductStock) {
            $this->adjustStock($createdItems, 'decrement');
        }
    }

    protected function adjustStock($items, string $direction): void
    {
        foreach ($items as $item) {
            $itemId = is_array($item) ? ($item['item_id'] ?? null) : $item->item_id;
            $qty = is_array($item) ? (float) $item['quantity'] : (float) $item->quantity;

            if ($itemId && $qty > 0) {
                if ($direction === 'increment') {
                    StockItem::where('id', $itemId)->increment('quantity', $qty);
                } else {
                    StockItem::where('id', $itemId)->decrement('quantity', $qty);
                }
            }
        }
    }

    protected function formatSalePayload($salesId): ?array
    {
        $sale = Sales::with([
            'Ledger',
            'MasterItems.StockItem.unitDetail',
            'MasterItems.WorkName',
            'MasterItems.WorkType',
            'MasterItems.Size',
            'MasterItems.Color',
            'MasterItems.Weight',
            'MasterItems.Paper',
            'MasterItems.Lamination',
        ])->find($salesId);

        if (!$sale) {
            return null;
        }

        $items = $sale->MasterItems->map(function ($mi) {
            $stock = $mi->StockItem;
            if ($stock) {
                $itemName = $stock->name;
                $unitName = $stock->unitDetail ? $stock->unitDetail->name : '';
            } else {
                $parts = array_filter([
                    optional($mi->WorkName)->name,
                    optional($mi->WorkType)->name,
                    optional($mi->Size)->name,
                    optional($mi->Color)->name,
                    optional($mi->Paper)->name,
                    optional($mi->Weight)->name,
                    optional($mi->Lamination)->name,
                    $mi->note,
                ]);
                $itemName = !empty($parts) ? implode(' ', $parts) : 'Invoice Item #' . $mi->id;
                $unitName = '';
            }

            return [
                'item_id' => $mi->item_id ?: null,
                'item_name' => $itemName,
                'unit_name' => $unitName,
                'quantity' => (float) $mi->sales_quantity,
                'note' => $mi->note ?? '',
            ];
        })->values()->all();

        return [
            'id' => $sale->id,
            'voucher_no' => $sale->voucher_no,
            'date' => $sale->date,
            'ledger_id' => $sale->debit_head,
            'delivery_address' => optional($sale->Ledger)->address ?? '',
            'contact_phone' => optional($sale->Ledger)->mobile ?? '',
            'items' => $items,
        ];
    }

    protected function nullIfEmpty($value): ?string
    {
        $trimmed = trim((string) $value);
        return $trimmed !== '' ? $trimmed : null;
    }
}
