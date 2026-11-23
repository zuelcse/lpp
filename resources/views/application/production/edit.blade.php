@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Production')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Edit Finished Goods Production') }}</h5>
                    <a href="{{ route('inventory-production-list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-list-ul me-1"></i> Production List
                    </a>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <form method="POST" action="{{ route('inventory-production-update', $production->id) }}" id="productionEditForm">
                        @csrf

                        <!-- Master Header Information -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row g-3">
                                <!-- Voucher No -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold" for="voucher_no">Voucher No <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('voucher_no') is-invalid @enderror" 
                                           id="voucher_no" 
                                           name="voucher_no" 
                                           value="{{ old('voucher_no', $production->voucher_no) }}" 
                                           required />
                                    @error('voucher_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold" for="date">Production Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control form-control-sm @error('date') is-invalid @enderror" 
                                           id="date" 
                                           name="date" 
                                           value="{{ old('date', date('Y-m-d', strtotime($production->date))) }}" 
                                           required />
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Production Item (Finished Good) Dropdown -->
                                <div class="col-12 col-sm-8 col-md-4">
                                    <label class="form-label fw-bold" for="production_item">Production Item (Finished Good) <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm js-example-basic-single @error('production_item') is-invalid @enderror" 
                                            id="production_item" 
                                            name="production_item" 
                                            required>
                                        <option value="">-- Select Finished Product --</option>
                                        @foreach($stockItems as $sItem)
                                            <option value="{{ $sItem->id }}" {{ old('production_item', $production->production_item) == $sItem->id ? 'selected' : '' }}>
                                                {{ $sItem->barcode ? $sItem->barcode . ' - ' : '' }}{{ $sItem->name }}{{ !empty($sItem->unitDetail->name) ? ' ('.$sItem->unitDetail->name.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('production_item')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Produced Quantity -->
                                <div class="col-12 col-sm-4 col-md-2">
                                    <label class="form-label fw-bold" for="quantity">Produced Qty <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           step="any" 
                                           min="0.01" 
                                           class="form-control form-control-sm text-end @error('quantity') is-invalid @enderror" 
                                           id="quantity" 
                                           name="quantity" 
                                           value="{{ old('quantity', $production->quantity) }}" 
                                           required />
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Narration / Remarks -->
                                <div class="col-12">
                                    <label class="form-label fw-bold" for="narration">Narration / Remarks</label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('narration') is-invalid @enderror" 
                                           id="narration" 
                                           name="narration" 
                                           value="{{ old('narration', $production->narration) }}" 
                                           placeholder="Production remarks" />
                                    @error('narration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Multiple Items Section -->
                        <div class="card mb-3 border">
                            <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="bx bx-layer me-1"></i> Consumed Raw Materials (Issued to Production)
                                </h6>
                                <span class="badge bg-label-info">Max quantity allowed is Floor Issued Quantity</span>
                            </div>

                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-0" id="itemTable">
                                        <thead class="table-light">
                                            <tr class="text-center align-middle">
                                                <th style="min-width: 260px;" width="38%">Raw Material Item <span class="text-danger">*</span></th>
                                                <th style="min-width: 150px;" width="18%">Consumed Qty <span class="text-danger">*</span></th>
                                                <th style="min-width: 110px;" width="12%">Rate</th>
                                                <th style="min-width: 120px;" width="14%">Value</th>
                                                <th style="min-width: 160px;" width="12%">Note</th>
                                                <th style="min-width: 70px;" width="6%">Action</th>
                                            </tr>
                                            <!-- Top Input Row for Adding Items -->
                                            <tr class="bg-light">
                                                <td class="p-1">
                                                    <select class="form-control form-control-sm js-example-basic-single" id="itemSelect">
                                                        <option value="">-- Select Material Item --</option>
                                                        @foreach($rawMaterials as $mat)
                                                            <option value="{{ $mat->id }}" 
                                                                    data-barcode="{{ $mat->barcode }}" 
                                                                    data-name="{{ $mat->name }}" 
                                                                    data-unit="{{ $mat->unitDetail->name ?? '' }}"
                                                                    data-floor-stock="{{ (float)$mat->floor_stock }}"
                                                                    data-rate="{{ (float)$mat->latest_rate }}">
                                                                {{ $mat->barcode ? $mat->barcode . ' - ' : '' }}{{ $mat->name }} [Floor Issued: {{ number_format($mat->floor_stock, 2) }}{{ !empty($mat->unitDetail->name) ? ' '.$mat->unitDetail->name : '' }}]
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="p-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-1" id="floorInfoWrap" style="display:none !important;">
                                                        <span class="badge bg-label-success py-0 px-1" id="floorBadge">Floor Issued: <strong id="floorDisplay">0.00</strong></span>
                                                    </div>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="any" min="0.01" class="form-control form-control-sm text-end" id="qtyInput" placeholder="0.00" />
                                                        <span class="input-group-text p-1 text-muted small" id="unitBadge">-</span>
                                                    </div>
                                                </td>
                                                <td class="p-1">
                                                    <input type="number" step="any" min="0" class="form-control form-control-sm text-end" id="rateInput" placeholder="0.00" />
                                                </td>
                                                <td class="p-1">
                                                    <input type="number" step="any" min="0" class="form-control form-control-sm text-end" id="valueInput" placeholder="0.00" readonly />
                                                </td>
                                                <td class="p-1">
                                                    <input type="text" class="form-control form-control-sm" id="noteInput" placeholder="Remarks" />
                                                </td>
                                                <td class="p-1 text-center">
                                                    <button type="button" class="btn btn-primary btn-sm w-100" id="addItemBtn" title="Add Item">
                                                        <i class="bx bx-plus"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="itemTableBody">
                                            @foreach($prodItems as $index => $itemRow)
                                                @php
                                                    $mat = $itemRow->materialItem;
                                                    $bCode = $mat ? $mat->barcode : '';
                                                    $iName = $mat ? $mat->name : 'Item #'.$itemRow->item_id;
                                                    $iUnit = ($mat && $mat->unitDetail) ? $mat->unitDetail->name : '-';
                                                    $dispText = $bCode ? ($bCode . ' - ' . $iName) : $iName;
                                                    $rowKey = $index + 1;
                                                    $fStock = $mat ? (float)($mat->floor_stock ?? 0) : 0;
                                                    $iRate = (float)($itemRow->rate ?? 0);
                                                    $iVal = (float)($itemRow->value ?? round($itemRow->production_qty * $iRate, 2));
                                                @endphp
                                                <tr id="row_{{ $rowKey }}">
                                                    <td>
                                                        <span class="fw-bold">{{ $dispText }}</span>
                                                        <small class="text-muted d-block">Floor Issued: {{ number_format($fStock, 2) }} {{ $iUnit }}</small>
                                                        <input type="hidden" name="item[{{ $rowKey }}][item_id]" value="{{ $itemRow->item_id }}" class="rowItemId" />
                                                        <input type="hidden" class="rowItemBarcode" value="{{ $bCode }}" />
                                                        <input type="hidden" class="rowItemName" value="{{ $iName }}" />
                                                        <input type="hidden" class="rowItemUnit" value="{{ $iUnit }}" />
                                                        <input type="hidden" class="rowItemFloorStock" value="{{ $fStock }}" />
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="rowQtyText">{{ number_format($itemRow->production_qty, 2) }}</span>
                                                        <span class="badge bg-label-secondary ms-1">{{ $iUnit }}</span>
                                                        <input type="hidden" name="item[{{ $rowKey }}][production_qty]" value="{{ $itemRow->production_qty }}" class="rowQtyInput" />
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="rowRateText">{{ number_format($iRate, 2) }}</span>
                                                        <input type="hidden" name="item[{{ $rowKey }}][rate]" value="{{ $iRate }}" class="rowRateInput" />
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="rowValText">{{ number_format($iVal, 2) }}</span>
                                                        <input type="hidden" name="item[{{ $rowKey }}][value]" value="{{ $iVal }}" class="rowValInput" />
                                                    </td>
                                                    <td>
                                                        <span class="rowNoteText">{{ $itemRow->note ?? '' }}</span>
                                                        <input type="hidden" name="item[{{ $rowKey }}][note]" value="{{ $itemRow->note ?? '' }}" class="rowNoteInput" />
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-icon btn-outline-primary editRowBtn me-1" title="Edit Item">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger removeRowBtn" title="Remove Item">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td class="text-end">Total Consumed:</td>
                                                <td class="text-end" id="totalQtyDisplay">0.00</td>
                                                <td class="text-end">Total Cost:</td>
                                                <td class="text-end" id="totalValueDisplay">0.00</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Accounting Voucher Notice -->
                        <div class="alert alert-info py-2 px-3 small d-flex align-items-center mb-3">
                            <i class="bx bx-info-circle me-2 fs-5"></i>
                            <div>
                                <strong>Automatic Accounting Voucher Entry:</strong> Updating this record will automatically adjust the accounting voucher debiting <strong>Cost of Goods Sold (Consumption)</strong> and crediting <strong>Raw Materials (Purchase)</strong>.
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('inventory-production-list') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this production record and accounting voucher?')">
                                <i class="bx bx-check me-1"></i> Update Production
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let rowIndex = {{ count($prodItems) + 1 }};

    recalcTotals();

    $('#itemSelect').on('select2:select change', function () {
        let selectedOption = $(this).find('option:selected');
        let unit = selectedOption.data('unit') || '-';
        let floorStock = parseFloat(selectedOption.data('floor-stock'));
        if (isNaN(floorStock)) floorStock = 0;

        let rate = parseFloat(selectedOption.data('rate'));
        if (isNaN(rate)) rate = 0;

        $('#unitBadge').text(unit);
        if ($(this).val()) {
            $('#floorDisplay').text(floorStock.toFixed(2) + (unit !== '-' ? ' ' + unit : ''));
            $('#floorInfoWrap').attr('style', 'display: flex !important;');
            $('#qtyInput').attr('max', floorStock);
            $('#rateInput').val(rate > 0 ? rate.toFixed(2) : '');
            calculateRowValue();
        } else {
            $('#floorInfoWrap').attr('style', 'display: none !important;');
            $('#qtyInput').removeAttr('max');
            $('#rateInput').val('');
            $('#valueInput').val('');
        }
        $('#qtyInput').focus();
    });

    $('#qtyInput, #rateInput').on('input keyup change', function () {
        calculateRowValue();
    });

    function calculateRowValue() {
        let q = parseFloat($('#qtyInput').val()) || 0;
        let r = parseFloat($('#rateInput').val()) || 0;
        let val = q * r;
        $('#valueInput').val(val > 0 ? val.toFixed(2) : '');
    }

    $('#qtyInput, #rateInput, #noteInput').on('keydown', function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $('#addItemBtn').click();
        }
    });

    $('#addItemBtn').on('click', function () {
        let itemId = $('#itemSelect').val();
        if (!itemId) {
            alert('Please select a raw material item.');
            $('#itemSelect').select2('open');
            return;
        }

        let selectedOption = $('#itemSelect').find('option:selected');
        let itemName = selectedOption.data('name');
        let itemBarcode = selectedOption.data('barcode') || '';
        let itemUnit = selectedOption.data('unit') || '-';
        let floorStock = parseFloat(selectedOption.data('floor-stock'));
        if (isNaN(floorStock)) floorStock = 0;
        let displayText = itemBarcode ? `${itemBarcode} - ${itemName}` : itemName;

        let qty = parseFloat($('#qtyInput').val());
        if (!qty || qty <= 0) {
            alert('Please enter a valid consumed quantity greater than 0.');
            $('#qtyInput').focus();
            return;
        }

        // Max Validation: check against Floor Stock (Issue to Production quantity)
        let alreadyAddedQty = 0;
        $('.rowItemId').each(function () {
            if ($(this).val() == itemId) {
                alreadyAddedQty += parseFloat($(this).closest('tr').find('.rowQtyInput').val()) || 0;
            }
        });

        let totalRequested = alreadyAddedQty + qty;
        if (totalRequested > floorStock) {
            alert(`Cannot consume ${totalRequested.toFixed(2)} ${itemUnit} for "${itemName}". Available issued quantity on production floor is only ${floorStock.toFixed(2)} ${itemUnit}. Please issue materials to production first.`);
            $('#qtyInput').focus();
            return;
        }

        let rate = parseFloat($('#rateInput').val()) || 0;
        let val = parseFloat($('#valueInput').val()) || (qty * rate);
        let note = $('#noteInput').val() || '';

        let rowHtml = `
            <tr id="row_${rowIndex}">
                <td>
                    <span class="fw-bold">${displayText}</span>
                    <small class="text-muted d-block">Floor Issued: ${floorStock.toFixed(2)} ${itemUnit}</small>
                    <input type="hidden" name="item[${rowIndex}][item_id]" value="${itemId}" class="rowItemId" />
                    <input type="hidden" class="rowItemBarcode" value="${itemBarcode}" />
                    <input type="hidden" class="rowItemName" value="${itemName}" />
                    <input type="hidden" class="rowItemUnit" value="${itemUnit}" />
                    <input type="hidden" class="rowItemFloorStock" value="${floorStock}" />
                </td>
                <td class="text-end">
                    <span class="rowQtyText">${qty.toFixed(2)}</span>
                    <span class="badge bg-label-secondary ms-1">${itemUnit}</span>
                    <input type="hidden" name="item[${rowIndex}][production_qty]" value="${qty}" class="rowQtyInput" />
                </td>
                <td class="text-end">
                    <span class="rowRateText">${rate.toFixed(2)}</span>
                    <input type="hidden" name="item[${rowIndex}][rate]" value="${rate}" class="rowRateInput" />
                </td>
                <td class="text-end">
                    <span class="rowValText">${val.toFixed(2)}</span>
                    <input type="hidden" name="item[${rowIndex}][value]" value="${val}" class="rowValInput" />
                </td>
                <td>
                    <span class="rowNoteText">${note}</span>
                    <input type="hidden" name="item[${rowIndex}][note]" value="${note}" class="rowNoteInput" />
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary editRowBtn me-1" title="Edit Item">
                        <i class="bx bx-edit-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger removeRowBtn" title="Remove Item">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#itemTableBody').append(rowHtml);
        rowIndex++;

        // Reset Inputs
        $('#itemSelect').val('').trigger('change');
        $('#unitBadge').text('-');
        $('#floorInfoWrap').attr('style', 'display: none !important;');
        $('#qtyInput').val('');
        $('#rateInput').val('');
        $('#valueInput').val('');
        $('#noteInput').val('');

        recalcTotals();
        $('#itemSelect').select2('open');
    });

    $(document).on('click', '.removeRowBtn', function () {
        $(this).closest('tr').remove();
        recalcTotals();
    });

    $(document).on('click', '.editRowBtn', function () {
        let row = $(this).closest('tr');

        let itemId = row.find('.rowItemId').val();
        let qty = row.find('.rowQtyInput').val();
        let rate = row.find('.rowRateInput').val();
        let note = row.find('.rowNoteInput').val();

        $('#itemSelect').val(itemId).trigger('change');
        $('#qtyInput').val(qty);
        $('#rateInput').val(rate);
        $('#noteInput').val(note);
        calculateRowValue();

        row.remove();
        recalcTotals();
        $('#qtyInput').focus();
    });

    function recalcTotals() {
        let totalQty = 0;
        let totalValue = 0;
        $('.rowQtyInput').each(function () {
            totalQty += parseFloat($(this).val()) || 0;
        });
        $('.rowValInput').each(function () {
            totalValue += parseFloat($(this).val()) || 0;
        });
        $('#totalQtyDisplay').text(totalQty.toFixed(2));
        $('#totalValueDisplay').text(totalValue.toFixed(2));
    }

    $('#productionEditForm').on('submit', function (e) {
        if ($('#itemTableBody tr').length === 0) {
            e.preventDefault();
            alert('Please add at least one material item to the table before saving.');
            $('#itemSelect').select2('open');
            return false;
        }

        // Validate floor stock totals for all items in table
        let itemTotals = {};
        let itemStocks = {};
        let itemNames = {};
        let itemUnits = {};
        let hasError = false;

        $('#itemTableBody tr').each(function () {
            let id = $(this).find('.rowItemId').val();
            let q = parseFloat($(this).find('.rowQtyInput').val()) || 0;
            let s = parseFloat($(this).find('.rowItemFloorStock').val()) || 0;
            let n = $(this).find('.rowItemName').val();
            let u = $(this).find('.rowItemUnit').val();

            itemTotals[id] = (itemTotals[id] || 0) + q;
            itemStocks[id] = s;
            itemNames[id] = n;
            itemUnits[id] = u;
        });

        for (let id in itemTotals) {
            if (itemTotals[id] > itemStocks[id]) {
                alert(`Validation Error: Total consumed quantity for "${itemNames[id]}" (${itemTotals[id].toFixed(2)} ${itemUnits[id]}) exceeds available floor issued quantity (${itemStocks[id].toFixed(2)} ${itemUnits[id]}).`);
                hasError = true;
                break;
            }
        }

        if (hasError) {
            e.preventDefault();
            return false;
        }

        return true;
    });
});
</script>
@endsection
