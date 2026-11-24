@php
    $isEdit = isset($challan);
    $formAction = $isEdit ? route('challan-update', $challan->id) : route('challan-store');

    $defaultChallanNo = old('challan_no', $isEdit ? $challan->challan_no : ($challan_no ?? ''));
    $defaultDate = old('challan_date', $isEdit ? $challan->challan_date : date('Y-m-d'));
    $defaultSalesId = old('sales_id', $isEdit ? $challan->sales_id : ($selectedSale['id'] ?? ''));
    $defaultLedgerId = old('ledger_id', $isEdit ? $challan->ledger_id : ($selectedSale['ledger_id'] ?? ''));
    $defaultAddress = old('delivery_address', $isEdit ? $challan->delivery_address : ($selectedSale['delivery_address'] ?? ''));
    $defaultContactPerson = old('contact_person', $isEdit ? $challan->contact_person : '');
    $defaultContactPhone = old('contact_phone', $isEdit ? $challan->contact_phone : ($selectedSale['contact_phone'] ?? ''));
    $defaultDriver = old('driver_name', $isEdit ? $challan->driver_name : '');
    $defaultVehicle = old('vehicle_no', $isEdit ? $challan->vehicle_no : '');
    $defaultTransport = old('transport_name', $isEdit ? $challan->transport_name : '');
    $defaultReference = old('reference_no', $isEdit ? $challan->reference_no : ($selectedSale['voucher_no'] ?? ''));
    $defaultStatus = old('status', $isEdit ? $challan->status : 'Pending');
    $defaultNarration = old('narration', $isEdit ? $challan->narration : '');

    if (old('_token') !== null) {
        $defaultDeductStock = (bool) old('deduct_stock');
    } elseif ($isEdit) {
        $defaultDeductStock = (bool) $challan->deduct_stock;
    } else {
        $defaultDeductStock = empty($selectedSale);
    }

    if (old('item')) {
        $initialItems = array_values(old('item'));
    } elseif ($isEdit && $challan->items->count() > 0) {
        $initialItems = $challan->items->map(function ($it) {
            return [
                'item_id' => $it->item_id,
                'item_name' => $it->item_name,
                'unit_name' => $it->unit_name,
                'quantity' => (float) $it->quantity,
                'note' => $it->note,
            ];
        })->all();
    } elseif (!empty($selectedSale['items'])) {
        $initialItems = $selectedSale['items'];
    } else {
        $initialItems = [
            ['item_id' => '', 'item_name' => '', 'unit_name' => '', 'quantity' => '', 'note' => ''],
        ];
    }
@endphp

<form method="POST" action="{{ $formAction }}" id="challanForm">
    @csrf

    <!-- Master Info -->
    <div class="p-3 mb-3 border rounded bg-light">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="challan_no">Challan No <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control form-control-sm @error('challan_no') is-invalid @enderror"
                       id="challan_no"
                       name="challan_no"
                       value="{{ $defaultChallanNo }}"
                       placeholder="Challan No"
                       required />
                @error('challan_no')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="challan_date">Challan Date <span class="text-danger">*</span></label>
                <input type="date"
                       class="form-control form-control-sm @error('challan_date') is-invalid @enderror"
                       id="challan_date"
                       name="challan_date"
                       value="{{ $defaultDate }}"
                       required />
                @error('challan_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="sales_id">Link Sales Invoice (Optional)</label>
                <select class="form-select form-select-sm js-example-basic-single @error('sales_id') is-invalid @enderror"
                        id="sales_id"
                        name="sales_id">
                    <option value="">-- Standalone Challan --</option>
                    @foreach($salesList as $sale)
                        <option value="{{ $sale->id }}" {{ (string) $defaultSalesId === (string) $sale->id ? 'selected' : '' }}>
                            {{ $sale->voucher_no }}{{ $sale->Ledger ? ' - ' . $sale->Ledger->name : '' }} ({{ $sale->date }})
                        </option>
                    @endforeach
                </select>
                @error('sales_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="status">Delivery Status</label>
                <select class="form-select form-select-sm @error('status') is-invalid @enderror" id="status" name="status">
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ $defaultStatus === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label fw-bold" for="ledger_id">Customer / Party <span class="text-danger">*</span></label>
                <select class="form-select form-select-sm js-example-basic-single @error('ledger_id') is-invalid @enderror"
                        id="ledger_id"
                        name="ledger_id"
                        required>
                    <option value="">-- Select Customer / Party --</option>
                    @foreach($ledgers as $ledger)
                        <option value="{{ $ledger->id }}"
                                data-address="{{ $ledger->address }}"
                                data-mobile="{{ $ledger->mobile }}"
                                {{ (string) $defaultLedgerId === (string) $ledger->id ? 'selected' : '' }}>
                            {{ $ledger->alias ? $ledger->alias . ' - ' : '' }}{{ $ledger->name }}
                        </option>
                    @endforeach
                </select>
                @error('ledger_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="contact_person">Contact Person</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="contact_person"
                       name="contact_person"
                       value="{{ $defaultContactPerson }}"
                       placeholder="Receiver Name" />
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="contact_phone">Contact Phone</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="contact_phone"
                       name="contact_phone"
                       value="{{ $defaultContactPhone }}"
                       placeholder="Phone / Mobile" />
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label fw-bold" for="delivery_address">Delivery Address</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="delivery_address"
                       name="delivery_address"
                       value="{{ $defaultAddress }}"
                       placeholder="Delivery Destination Address" />
            </div>

            <div class="col-12 col-sm-4 col-md-2">
                <label class="form-label fw-bold" for="vehicle_no">Vehicle No</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="vehicle_no"
                       name="vehicle_no"
                       value="{{ $defaultVehicle }}"
                       placeholder="e.g. Dhaka-Ta-11..." />
            </div>

            <div class="col-12 col-sm-4 col-md-2">
                <label class="form-label fw-bold" for="driver_name">Driver Name</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="driver_name"
                       name="driver_name"
                       value="{{ $defaultDriver }}"
                       placeholder="Driver Name" />
            </div>

            <div class="col-12 col-sm-4 col-md-2">
                <label class="form-label fw-bold" for="transport_name">Transport / Courier</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="transport_name"
                       name="transport_name"
                       value="{{ $defaultTransport }}"
                       placeholder="Transport Name" />
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-bold" for="reference_no">Reference / PO No</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="reference_no"
                       name="reference_no"
                       value="{{ $defaultReference }}"
                       placeholder="Order / PO / Ref No" />
            </div>

            <div class="col-12 col-sm-6 col-md-5">
                <label class="form-label fw-bold" for="narration">Narration / Remarks</label>
                <input type="text"
                       class="form-control form-control-sm"
                       id="narration"
                       name="narration"
                       value="{{ $defaultNarration }}"
                       placeholder="Delivery instructions or remarks" />
            </div>

            <div class="col-12 col-md-4 d-flex align-items-end">
                <div class="form-check mb-1">
                    <input class="form-check-input"
                           type="checkbox"
                           id="deduct_stock"
                           name="deduct_stock"
                           value="1"
                           {{ $defaultDeductStock ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="deduct_stock">
                        Deduct Stock from Inventory
                        <small class="d-block text-muted">Uncheck when linked to an Invoice that already deducted stock.</small>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Challan Items Table -->
    <div class="card mb-3 border">
        <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="bx bx-package me-1"></i> Delivery Items
            </h6>
            <button type="button" class="btn btn-sm btn-primary" id="addRowBtn">
                <i class="bx bx-plus me-1"></i> Add Row
            </button>
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="challanItemsTable">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 45px;">#</th>
                            <th style="width: 28%;">Stock Item (Optional)</th>
                            <th style="width: 28%;">Description <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Unit</th>
                            <th style="width: 14%;">Quantity <span class="text-danger">*</span></th>
                            <th style="width: 14%;">Note</th>
                            <th style="width: 55px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="challanItemsBody">
                        @foreach($initialItems as $idx => $row)
                            <tr class="challan-row">
                                <td class="text-center row-sl">{{ $idx + 1 }}</td>
                                <td>
                                    <select name="item[{{ $idx }}][item_id]" class="form-select form-select-sm item-select js-example-basic-single">
                                        <option value="">-- Custom / Select Stock Item --</option>
                                        @foreach($stockItems as $sItem)
                                            <option value="{{ $sItem->id }}"
                                                    data-name="{{ $sItem->name }}"
                                                    data-unit="{{ $sItem->unitDetail->name ?? '' }}"
                                                    {{ (string) ($row['item_id'] ?? '') === (string) $sItem->id ? 'selected' : '' }}>
                                                {{ $sItem->barcode ? $sItem->barcode . ' - ' : '' }}{{ $sItem->name }}{{ !empty($sItem->unitDetail->name) ? ' (' . $sItem->unitDetail->name . ')' : '' }} [Stock: {{ number_format((float) $sItem->quantity, 2) }}]
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text"
                                           name="item[{{ $idx }}][item_name]"
                                           class="form-control form-control-sm item-name"
                                           value="{{ $row['item_name'] ?? '' }}"
                                           placeholder="Item Name / Description"
                                           required />
                                </td>
                                <td>
                                    <input type="text"
                                           name="item[{{ $idx }}][unit_name]"
                                           class="form-control form-control-sm item-unit"
                                           value="{{ $row['unit_name'] ?? '' }}"
                                           placeholder="Unit" />
                                </td>
                                <td>
                                    <input type="number"
                                           step="any"
                                           min="0.01"
                                           name="item[{{ $idx }}][quantity]"
                                           class="form-control form-control-sm text-end item-qty"
                                           value="{{ $row['quantity'] ?? '' }}"
                                           placeholder="0.00"
                                           required />
                                </td>
                                <td>
                                    <input type="text"
                                           name="item[{{ $idx }}][note]"
                                           class="form-control form-control-sm"
                                           value="{{ $row['note'] ?? '' }}"
                                           placeholder="Remarks" />
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" title="Remove">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total Items: <span id="totalItemsCount">{{ count($initialItems) }}</span></th>
                            <th class="text-end">Total Qty: <span id="totalQtySum">0.00</span></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('challan-list') }}" class="btn btn-outline-secondary">
            Cancel
        </a>
        <div>
            @if(!$isEdit)
                <button type="submit" name="action" value="save_and_new" class="btn btn-secondary me-2">
                    <i class="bx bx-plus-circle me-1"></i> Save &amp; Add Another
                </button>
            @endif
            <button type="submit" name="action" value="save" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this challan?')">
                <i class="bx bx-check me-1"></i> {{ $isEdit ? 'Update Challan' : 'Save Challan' }}
            </button>
        </div>
    </div>
</form>

<template id="challanRowTemplate">
    <tr class="challan-row">
        <td class="text-center row-sl">1</td>
        <td>
            <select name="item[__IDX__][item_id]" class="form-select form-select-sm item-select">
                <option value="">-- Custom / Select Stock Item --</option>
                @foreach($stockItems as $sItem)
                    <option value="{{ $sItem->id }}"
                            data-name="{{ $sItem->name }}"
                            data-unit="{{ $sItem->unitDetail->name ?? '' }}">
                        {{ $sItem->barcode ? $sItem->barcode . ' - ' : '' }}{{ $sItem->name }}{{ !empty($sItem->unitDetail->name) ? ' (' . $sItem->unitDetail->name . ')' : '' }} [Stock: {{ number_format((float) $sItem->quantity, 2) }}]
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="item[__IDX__][item_name]" class="form-control form-control-sm item-name" placeholder="Item Name / Description" required />
        </td>
        <td>
            <input type="text" name="item[__IDX__][unit_name]" class="form-control form-control-sm item-unit" placeholder="Unit" />
        </td>
        <td>
            <input type="number" step="any" min="0.01" name="item[__IDX__][quantity]" class="form-control form-control-sm text-end item-qty" placeholder="0.00" required />
        </td>
        <td>
            <input type="text" name="item[__IDX__][note]" class="form-control form-control-sm" placeholder="Remarks" />
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" title="Remove">
                <i class="bx bx-trash"></i>
            </button>
        </td>
    </tr>
</template>
