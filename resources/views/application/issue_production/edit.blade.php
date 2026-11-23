@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Issue to Production')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Edit Issue to Production') }}</h5>
                    <a href="{{ route('inventory-issue-production-list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-list-ul me-1"></i> Issue to Production List
                    </a>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <form method="POST" action="{{ route('inventory-issue-production-update', $issue->id) }}" id="issueEditForm">
                        @csrf

                        <!-- Master Header Information -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row g-3">
                                <!-- Voucher No -->
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label fw-bold" for="voucher_no">Voucher No <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('voucher_no') is-invalid @enderror" 
                                           id="voucher_no" 
                                           name="voucher_no" 
                                           value="{{ old('voucher_no', $issue->voucher_no) }}" 
                                           required />
                                    @error('voucher_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label fw-bold" for="date">Issue Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control form-control-sm @error('date') is-invalid @enderror" 
                                           id="date" 
                                           name="date" 
                                           value="{{ old('date', date('Y-m-d', strtotime($issue->date))) }}" 
                                           required />
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Multiple Items Section (like sales/invoice-pp) -->
                        <div class="card mb-3 border">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="bx bx-layer me-1"></i> Materials Issued to Production
                                </h6>
                            </div>

                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-0" id="itemTable">
                                        <thead class="table-light">
                                            <tr class="text-center align-middle">
                                                <th style="min-width: 280px;" width="50%">Material Item (Barcode - Name) <span class="text-danger">*</span></th>
                                                <th style="min-width: 150px;" width="20%">Issue Qty <span class="text-danger">*</span></th>
                                                <th style="min-width: 200px;" width="20%">Note</th>
                                                <th style="min-width: 90px;" width="10%">Action</th>
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
                                                                    data-stock="{{ (float)($mat->effective_stock ?? $mat->quantity) }}">
                                                                {{ $mat->barcode ? $mat->barcode . ' - ' : '' }}{{ $mat->name }} [Stock: {{ number_format($mat->effective_stock ?? $mat->quantity, 2) }}{{ !empty($mat->unitDetail->name) ? ' '.$mat->unitDetail->name : '' }}]
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="p-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-1" id="stockInfoWrap" style="display:none !important;">
                                                        <span class="badge bg-label-primary py-0 px-1" id="stockBadge">Store Stock: <strong id="stockDisplay">0.00</strong></span>
                                                    </div>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="any" min="0.01" class="form-control form-control-sm text-end" id="qtyInput" placeholder="0.00" />
                                                        <span class="input-group-text p-1 text-muted small" id="unitBadge">-</span>
                                                    </div>
                                                </td>
                                                <td class="p-1">
                                                    <input type="text" class="form-control form-control-sm" id="noteInput" placeholder="Issue note / remarks" />
                                                </td>
                                                <td class="p-1 text-center">
                                                    <button type="button" class="btn btn-primary btn-sm w-100" id="addItemBtn" title="Add Item">
                                                        <i class="bx bx-plus"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="itemTableBody">
                                            @foreach($issueItems as $index => $itemRow)
                                                @php
                                                    $mat = $itemRow->materialItem;
                                                    $bCode = $mat ? $mat->barcode : '';
                                                    $iName = $mat ? $mat->name : 'Item #'.$itemRow->item_id;
                                                    $iUnit = ($mat && $mat->unitDetail) ? $mat->unitDetail->name : '-';
                                                    $dispText = $bCode ? ($bCode . ' - ' . $iName) : $iName;
                                                    $rowKey = $index + 1;
                                                    $itemStock = $mat ? (float)($mat->effective_stock ?? ($mat->quantity + $itemRow->issue2production_qty)) : 0;
                                                @endphp
                                                <tr id="row_{{ $rowKey }}">
                                                    <td>
                                                        <span class="fw-bold">{{ $dispText }}</span>
                                                        <small class="text-muted d-block">Store Stock: {{ number_format($itemStock, 2) }} {{ $iUnit }}</small>
                                                        <input type="hidden" name="item[{{ $rowKey }}][item_id]" value="{{ $itemRow->item_id }}" class="rowItemId" />
                                                        <input type="hidden" class="rowItemBarcode" value="{{ $bCode }}" />
                                                        <input type="hidden" class="rowItemName" value="{{ $iName }}" />
                                                        <input type="hidden" class="rowItemUnit" value="{{ $iUnit }}" />
                                                        <input type="hidden" class="rowItemStock" value="{{ $itemStock }}" />
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="rowQtyText">{{ number_format($itemRow->issue2production_qty, 2) }}</span>
                                                        <span class="badge bg-label-secondary ms-1">{{ $iUnit }}</span>
                                                        <input type="hidden" name="item[{{ $rowKey }}][issue2production_qty]" value="{{ $itemRow->issue2production_qty }}" class="rowQtyInput" />
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
                                                <td class="text-end">Total Issue Qty:</td>
                                                <td class="text-end" id="totalQtyDisplay">0.00</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('inventory-issue-production-list') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this issue record?')">
                                <i class="bx bx-check me-1"></i> Update Issue
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
    let rowIndex = {{ count($issueItems) + 1 }};

    recalcTotals();

    $('#itemSelect').on('select2:select change', function () {
        let selectedOption = $(this).find('option:selected');
        let unit = selectedOption.data('unit') || '-';
        let stock = parseFloat(selectedOption.data('stock'));
        if (isNaN(stock)) stock = 0;

        $('#unitBadge').text(unit);
        if ($(this).val()) {
            $('#stockDisplay').text(stock.toFixed(2) + (unit !== '-' ? ' ' + unit : ''));
            $('#stockInfoWrap').attr('style', 'display: flex !important;');
            $('#qtyInput').attr('max', stock);
        } else {
            $('#stockInfoWrap').attr('style', 'display: none !important;');
            $('#qtyInput').removeAttr('max');
        }
        $('#qtyInput').focus();
    });

    $('#qtyInput, #noteInput').on('keydown', function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $('#addItemBtn').click();
        }
    });

    $('#addItemBtn').on('click', function () {
        let itemId = $('#itemSelect').val();
        if (!itemId) {
            alert('Please select a material item.');
            $('#itemSelect').select2('open');
            return;
        }

        let selectedOption = $('#itemSelect').find('option:selected');
        let itemName = selectedOption.data('name');
        let itemBarcode = selectedOption.data('barcode') || '';
        let itemUnit = selectedOption.data('unit') || '-';
        let stock = parseFloat(selectedOption.data('stock'));
        if (isNaN(stock)) stock = 0;
        let displayText = itemBarcode ? `${itemBarcode} - ${itemName}` : itemName;

        let qty = parseFloat($('#qtyInput').val());
        if (!qty || qty <= 0) {
            alert('Please enter a valid issue quantity greater than 0.');
            $('#qtyInput').focus();
            return;
        }

        // Check if entered quantity exceeds available stock (including already added rows)
        let alreadyAddedQty = 0;
        $('.rowItemId').each(function () {
            if ($(this).val() == itemId) {
                alreadyAddedQty += parseFloat($(this).closest('tr').find('.rowQtyInput').val()) || 0;
            }
        });

        let totalRequested = alreadyAddedQty + qty;
        if (totalRequested > stock) {
            alert(`Cannot issue ${totalRequested.toFixed(2)} ${itemUnit} for "${itemName}". Available store stock is only ${stock.toFixed(2)} ${itemUnit}.`);
            $('#qtyInput').focus();
            return;
        }

        let note = $('#noteInput').val() || '';

        let rowHtml = `
            <tr id="row_${rowIndex}">
                <td>
                    <span class="fw-bold">${displayText}</span>
                    <small class="text-muted d-block">Store Stock: ${stock.toFixed(2)} ${itemUnit}</small>
                    <input type="hidden" name="item[${rowIndex}][item_id]" value="${itemId}" class="rowItemId" />
                    <input type="hidden" class="rowItemBarcode" value="${itemBarcode}" />
                    <input type="hidden" class="rowItemName" value="${itemName}" />
                    <input type="hidden" class="rowItemUnit" value="${itemUnit}" />
                    <input type="hidden" class="rowItemStock" value="${stock}" />
                </td>
                <td class="text-end">
                    <span class="rowQtyText">${qty.toFixed(2)}</span>
                    <span class="badge bg-label-secondary ms-1">${itemUnit}</span>
                    <input type="hidden" name="item[${rowIndex}][issue2production_qty]" value="${qty}" class="rowQtyInput" />
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
        $('#stockInfoWrap').attr('style', 'display: none !important;');
        $('#qtyInput').val('');
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
        let note = row.find('.rowNoteInput').val();

        $('#itemSelect').val(itemId).trigger('change');
        $('#qtyInput').val(qty);
        $('#noteInput').val(note);

        row.remove();
        recalcTotals();
        $('#qtyInput').focus();
    });

    function recalcTotals() {
        let totalQty = 0;
        $('.rowQtyInput').each(function () {
            totalQty += parseFloat($(this).val()) || 0;
        });
        $('#totalQtyDisplay').text(totalQty.toFixed(2));
    }

    $('#issueEditForm').on('submit', function (e) {
        if ($('#itemTableBody tr').length === 0) {
            e.preventDefault();
            alert('Please add at least one material item to the table before saving.');
            $('#itemSelect').select2('open');
            return false;
        }

        // Validate stock totals for all items in table
        let itemTotals = {};
        let itemStocks = {};
        let itemNames = {};
        let itemUnits = {};
        let hasError = false;

        $('#itemTableBody tr').each(function () {
            let id = $(this).find('.rowItemId').val();
            let q = parseFloat($(this).find('.rowQtyInput').val()) || 0;
            let s = parseFloat($(this).find('.rowItemStock').val()) || 0;
            let n = $(this).find('.rowItemName').val();
            let u = $(this).find('.rowItemUnit').val();

            itemTotals[id] = (itemTotals[id] || 0) + q;
            itemStocks[id] = s;
            itemNames[id] = n;
            itemUnits[id] = u;
        });

        for (let id in itemTotals) {
            if (itemTotals[id] > itemStocks[id]) {
                alert(`Validation Error: Total issue quantity for "${itemNames[id]}" (${itemTotals[id].toFixed(2)} ${itemUnits[id]}) exceeds available store stock (${itemStocks[id].toFixed(2)} ${itemUnits[id]}).`);
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
