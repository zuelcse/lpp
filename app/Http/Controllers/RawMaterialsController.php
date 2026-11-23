<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\Unit;
use App\Models\Purchase;
use App\Models\MasterItems;
use App\Models\MasterVoucher;
use App\Models\Voucher;
use App\Models\StockItem;
use App\Models\MaterialGroups;
use App\Models\Materialitems;
use Auth;

class RawMaterialsController extends Controller
{
    protected $voucherType = 1; // Purchase

    public function newEntryForm() {}

    public function materialGroups()
    {
        $groups = MaterialGroups::orderBy('name', 'ASC')->get();
        return view('application.material.groups', compact('groups'));
    }

    public function itemCreate(Request $request)
    {
        // Store
        if ($request->isMethod('post')) {
            $request->validate(
                [
                    'alias' => 'nullable|string|max:255|unique:material_items,alias',
                    'name' => 'required|string|max:255|unique:material_items,name',
                    'name_bn' => 'nullable|string|max:255',
                    'unit' => 'nullable|integer|exists:units,id',
                    'group' => 'nullable|integer|exists:material_groups,id',
                    'barcode' => 'nullable|string|max:255|unique:material_items,barcode',
                    'quantity' => 'nullable|numeric|min:0',
                    'opening_stock' => 'nullable|numeric|min:0',
                    'opening_value' => 'nullable|numeric|min:0',
                ],
                [
                    'name.required' => 'Item name is required.',
                    'name.unique' => 'This item name already exists.',
                    'alias.unique' => 'This alias already exists.',
                    'barcode.unique' => 'This barcode already exists.',
                    'unit.exists' => 'Selected unit is invalid.',
                    'group.exists' => 'Selected group is invalid.',
                ]
            );

            $data = $request->only([
                'alias',
                'name',
                'name_bn',
                'unit',
                'group',
                'barcode',
                'quantity',
                'opening_stock',
                'opening_value',
            ]);

            $data['alias'] = !empty($data['alias']) ? trim($data['alias']) : null;
            $data['name'] = trim($data['name']);
            $data['name_bn'] = !empty($data['name_bn']) ? trim($data['name_bn']) : null;
            $data['barcode'] = !empty($data['barcode']) ? trim($data['barcode']) : null;
            $data['unit'] = !empty($data['unit']) ? $data['unit'] : null;
            $data['group'] = !empty($data['group']) ? $data['group'] : null;
            $data['opening_stock'] = (isset($data['opening_stock']) && $data['opening_stock'] !== '') ? $data['opening_stock'] : 0;
            $data['opening_value'] = (isset($data['opening_value']) && $data['opening_value'] !== '') ? $data['opening_value'] : 0;
            $data['quantity'] = (isset($data['quantity']) && $data['quantity'] !== '') ? $data['quantity'] : $data['opening_stock'];

            Materialitems::create($data);

            if ($request->input('action') === 'save_and_new') {
                return redirect()
                    ->route('inventory-material-item-create')
                    ->with('success', 'Raw Material Item created successfully! You can add another item.');
            }

            return redirect()
                ->route('inventory-material-item-lists')
                ->with('success', 'Raw Material Item created successfully!');
        }

        // Create Form
        $allUnits = Unit::orderBy('name', 'ASC')->pluck('name', 'id');
        $allGroups = MaterialGroups::orderBy('name', 'ASC')->pluck('name', 'id');

        return view(
            'application.material.item-create',
            compact('allUnits', 'allGroups')
        );
    }

    public function itemEdit(Request $request, $id)
    {
        $item = Materialitems::findOrFail($id);

        // Update
        if ($request->isMethod('post')) {
            $request->validate(
                [
                    'alias' => 'nullable|string|max:255|unique:material_items,alias,' . $id,
                    'name' => 'required|string|max:255|unique:material_items,name,' . $id,
                    'name_bn' => 'nullable|string|max:255',
                    'unit' => 'nullable|integer|exists:units,id',
                    'group' => 'nullable|integer|exists:material_groups,id',
                    'barcode' => 'nullable|string|max:255|unique:material_items,barcode,' . $id,
                    'quantity' => 'nullable|numeric|min:0',
                    'opening_stock' => 'nullable|numeric|min:0',
                    'opening_value' => 'nullable|numeric|min:0',
                ],
                [
                    'name.required' => 'Item name is required.',
                    'name.unique' => 'This item name already exists.',
                    'alias.unique' => 'This alias already exists.',
                    'barcode.unique' => 'This barcode already exists.',
                    'unit.exists' => 'Selected unit is invalid.',
                    'group.exists' => 'Selected group is invalid.',
                ]
            );

            $data = $request->only([
                'alias',
                'name',
                'name_bn',
                'unit',
                'group',
                'barcode',
                'quantity',
                'opening_stock',
                'opening_value',
            ]);

            $data['alias'] = !empty($data['alias']) ? trim($data['alias']) : null;
            $data['name'] = trim($data['name']);
            $data['name_bn'] = !empty($data['name_bn']) ? trim($data['name_bn']) : null;
            $data['barcode'] = !empty($data['barcode']) ? trim($data['barcode']) : null;
            $data['unit'] = !empty($data['unit']) ? $data['unit'] : null;
            $data['group'] = !empty($data['group']) ? $data['group'] : null;
            $data['opening_stock'] = (isset($data['opening_stock']) && $data['opening_stock'] !== '') ? $data['opening_stock'] : 0;
            $data['opening_value'] = (isset($data['opening_value']) && $data['opening_value'] !== '') ? $data['opening_value'] : 0;
            $data['quantity'] = (isset($data['quantity']) && $data['quantity'] !== '') ? $data['quantity'] : $data['opening_stock'];

            $item->update($data);

            return redirect()
                ->route('inventory-material-item-lists')
                ->with('success', 'Raw Material Item updated successfully!');
        }

        // Edit Form
        $allUnits = Unit::orderBy('name', 'ASC')->pluck('name', 'id');
        $allGroups = MaterialGroups::orderBy('name', 'ASC')->pluck('name', 'id');

        return view(
            'application.material.item-update',
            compact('item', 'allUnits', 'allGroups')
        );
    }

    public function itemList()
    {
        $allUnits = Unit::pluck('name', 'id');
        $allGroups = MaterialGroups::pluck('name', 'id');

        $material_items = Materialitems::leftJoin(
                'material_groups',
                'material_items.group',
                '=',
                'material_groups.id'
            )
            ->select('material_items.*', 'material_groups.name as group_name')
            ->orderBy('material_items.name', 'ASC')
            ->paginate(60);

        return view('application.material.item-lists', compact('material_items', 'allUnits', 'allGroups'));
    }
}
