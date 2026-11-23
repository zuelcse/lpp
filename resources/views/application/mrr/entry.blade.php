@extends('layouts/contentNavbarLayout')

@section('title', 'New MRR Entry')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Material Receive Report (MRR) Entry') }}</h5>
                    <a href="{{ route('inventory-mrr-list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-list-ul me-1"></i> MRR List
                    </a>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <form method="POST" action="{{ route('inventory-mrr-store') }}" id="mrrForm">
                        @csrf

                        <!-- MRR Master Header Information -->
                        <div class="p-3 mb-3 border rounded bg-light">
                            <div class="row g-3">
                                <!-- Voucher No -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold" for="voucher_no">Voucher No <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('voucher_no') is-invalid @enderror" 
                                           id="voucher_no" 
                                           name="voucher_no" 
                                           value="{{ old('voucher_no', $voucher_no) }}" 
                                           placeholder="MRR Voucher No" 
                                           required />
                                    @error('voucher_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold" for="date">Receive Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control form-control-sm @error('date') is-invalid @enderror" 
                                           id="date" 
                                           name="date" 
                                           value="{{ old('date', date('Y-m-d')) }}" 
                                           required />
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Supplier Name (Dropdown) as supplier_id foreign key -->
                                <div class="col-12 col-sm-12 col-md-6">
                                    <label class="form-label fw-bold" for="supplier_id">Supplier Name <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm js-example-basic-single @error('supplier_id') is-invalid @enderror" 
                                            id="supplier_id" 
                                            name="supplier_id" 
                                            required>
                                        <option value="">-- Select Supplier / Ledger --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}{{ $supplier->alias ? ' ('.$supplier->alias.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Challan No -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label" for="challan_no">Challan No</label>
                                    <input type="text" 
                                           class="form-control form-control-sm @error('challan_no') is-invalid @enderror" 
                                           id="challan_no" 
                                           name="challan_no" 
                                           value="{{ old('challan_no') }}" 
                                           placeholder="Enter Challan No" />
                                    @error('challan_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Challan Date -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label" for="challan_date">Challan Date</label>
                                    <input type="date" 
                                           class="form-control form-control-sm @error('challan_date') is-invalid @enderror" 
                                           id="challan_date" 
                                           name="challan_date" 
                                           value="{{ old('challan_date') }}" />
                                    @error('challan_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Multiple Items Section (like sales/invoice-pp) -->
                        <div class="card mb-3 border">
                            <div class="card-header bg-white py-2 border-bottom">
                                <h6 class="mb-0 fw-bold text-primary">
                                    <i class="bx bx-package me-1"></i> Receive Materials Items
                                </h6>
                            </div>

                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-0" id="itemTable">
                                        <thead class="table-light">
                                            <tr class="text-center align-middle">
                                                <th style="min-width: 250px;" width="35%">Item (Barcode - Name) <span class="text-danger">*</span></th>
                                                <th style="min-width: 120px;" width="15%">Received Qty <span class="text-danger">*</span></th>
                                                <th style="min-width: 120px;" width="15%">Rate (TK)</th>
                                                <th style="min-width: 130px;" width="15%">Value (TK)</th>
                                                <th style="min-width: 150px;" width="15%">Note</th>
                                                <th style="min-width: 90px;" width="5%">Action</th>
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
                                                                    data-stock="{{ $mat->quantity }}">
                                                                {{ $mat->barcode ? $mat->barcode . ' - ' : '' }}{{ $mat->name }}{{ !empty($mat->unitDetail->name) ? ' ('.$mat->unitDetail->name.')' : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="p-1">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="any" min="0.01" class="form-control form-control-sm text-end" id="qtyInput" placeholder="0.00" />
                                                        <span class="input-group-text p-1 text-muted small" id="unitBadge">-</span>
                                                    </div>
                                                </td>
                                                <td class="p-1">
                                                    <input type="number" step="any" min="0" class="form-control form-control-sm text-end" id="rateInput" placeholder="0.00" />
                                                </td>
                                                <td class="p-1">
                                                    <input type="number" step="any" min="0" class="form-control form-control-sm text-end" id="valueInput" placeholder="0.00" />
                                                </td>
                                                <td class="p-1">
                                                    <input type="text" class="form-control form-control-sm" id="noteInput" placeholder="Item note / remarks" />
                                                </td>
                                                <td class="p-1 text-center">
                                                    <button type="button" class="btn btn-primary btn-sm w-100" id="addItemBtn" title="Add Item">
                                                        <i class="bx bx-plus"></i> Add
                                                    </button>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="itemTableBody">
                                            <!-- Dynamic Rows Added via JS -->
                                        </tbody>
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td class="text-end">Total Summary:</td>
                                                <td class="text-end" id="totalQtyDisplay">0.00</td>
                                                <td></td>
                                                <td class="text-end" id="totalValueDisplay">0.00</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Summary and Voucher Details Section (like sales/invoice-pp) -->
                        <div class="row justify-content-end mb-3">
                            <div class="col-md-6 col-sm-12">
                                <div class="card border p-3 bg-light">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-6 col-sm-6 col-form-label text-end fw-bold" for="total_amount">Total Amount (TK)</label>
                                                <div class="col-6 col-sm-6">
                                                    <input type="text" class="form-control form-control-sm text-end fw-bold bg-white" id="total_amount" name="total_amount" readonly value="0.00"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-6 col-sm-6 col-form-label text-end" for="paid_amount">Paid Amount (TK)</label>
                                                <div class="col-6 col-sm-6">
                                                    <input type="number" step="any" min="0" onkeyup="calcMrrBalance()" class="form-control form-control-sm text-end" id="paid_amount" name="paid_amount" placeholder="0.00" autocomplete="off" value="{{ old('paid_amount') }}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-6 col-sm-6 col-form-label text-end" for="due_balance">Due / Balance (TK)</label>
                                                <div class="col-6 col-sm-6">
                                                    <input type="text" class="form-control form-control-sm text-end text-danger fw-bold bg-white" id="due_balance" name="due_balance" readonly value="0.00"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="row">
                                                <label class="col-12 col-sm-4 col-form-label text-sm-end" for="narration_remarks">Narration / Remarks</label>
                                                <div class="col-12 col-sm-8">
                                                    <textarea name="narration_remarks" id="narration_remarks" rows="2" class="form-control form-control-sm" placeholder="Narration / Remarks for Voucher">{{ old('narration_remarks') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('inventory-mrr-list') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-secondary me-2">
                                    <i class="bx bx-plus-circle me-1"></i> Save & Add Another
                                </button>
                                <button type="submit" name="action" value="save" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this MRR?')">
                                    <i class="bx bx-check me-1"></i> Save MRR
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let rowIndex = 1;

    // When item is selected, update unit badge
    $('#itemSelect').on('select2:select change', function (e) {
        let selectedOption = $(this).find('option:selected');
        let unit = selectedOption.data('unit') || '-';
        $('#unitBadge').text(unit);
        $('#qtyInput').focus();
    });

    // Auto calculate value when quantity or rate changes
    function autoCalcValue() {
        let qty = parseFloat($('#qtyInput').val()) || 0;
        let rate = parseFloat($('#rateInput').val()) || 0;
        if (qty > 0 && rate >= 0) {
            let val = (qty * rate).toFixed(2);
            $('#valueInput').val(val);
        }
    }

    $('#qtyInput, #rateInput').on('input keyup change', function () {
        autoCalcValue();
    });

    // Enter key triggers Add
    $('#qtyInput, #rateInput, #valueInput, #noteInput').on('keydown', function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            $('#addItemBtn').click();
        }
    });

    // Add Item to Table
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
        let displayText = itemBarcode ? `${itemBarcode} - ${itemName}` : itemName;

        let qty = parseFloat($('#qtyInput').val());
        if (!qty || qty <= 0) {
            alert('Please enter a valid received quantity greater than 0.');
            $('#qtyInput').focus();
            return;
        }

        let rate = parseFloat($('#rateInput').val()) || 0;
        let value = parseFloat($('#valueInput').val());
        if (isNaN(value)) {
            value = qty * rate;
        }

        let note = $('#noteInput').val() || '';

        let rowHtml = `
            <tr id="row_${rowIndex}">
                <td>
                    <span class="fw-bold">${displayText}</span>
                    <input type="hidden" name="item[${rowIndex}][item_id]" value="${itemId}" class="rowItemId" />
                    <input type="hidden" class="rowItemBarcode" value="${itemBarcode}" />
                    <input type="hidden" class="rowItemName" value="${itemName}" />
                    <input type="hidden" class="rowItemUnit" value="${itemUnit}" />
                </td>
                <td class="text-end">
                    <span class="rowQtyText">${qty.toFixed(2)}</span>
                    <span class="badge bg-label-secondary ms-1">${itemUnit}</span>
                    <input type="hidden" name="item[${rowIndex}][received_qty]" value="${qty}" class="rowQtyInput" />
                </td>
                <td class="text-end">
                    <span class="rowRateText">${rate.toFixed(2)}</span>
                    <input type="hidden" name="item[${rowIndex}][rate]" value="${rate}" class="rowRateInput" />
                </td>
                <td class="text-end">
                    <span class="rowValueText">${value.toFixed(2)}</span>
                    <input type="hidden" name="item[${rowIndex}][value]" value="${value}" class="rowValueInput" />
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
        $('#qtyInput').val('');
        $('#rateInput').val('');
        $('#valueInput').val('');
        $('#noteInput').val('');

        recalcTotals();
        $('#itemSelect').select2('open');
    });

    // Remove Row
    $(document).on('click', '.removeRowBtn', function () {
        $(this).closest('tr').remove();
        recalcTotals();
    });

    // Edit Row: load back into input fields
    $(document).on('click', '.editRowBtn', function () {
        let row = $(this).closest('tr');

        let itemId = row.find('.rowItemId').val();
        let qty = row.find('.rowQtyInput').val();
        let rate = row.find('.rowRateInput').val();
        let value = row.find('.rowValueInput').val();
        let note = row.find('.rowNoteInput').val();

        $('#itemSelect').val(itemId).trigger('change');
        $('#qtyInput').val(qty);
        $('#rateInput').val(rate);
        $('#valueInput').val(value);
        $('#noteInput').val(note);

        row.remove();
        recalcTotals();
        $('#qtyInput').focus();
    });

    // Recalculate Grand Totals
    function recalcTotals() {
        let totalQty = 0;
        let totalValue = 0;

        $('.rowQtyInput').each(function () {
            totalQty += parseFloat($(this).val()) || 0;
        });

        $('.rowValueInput').each(function () {
            totalValue += parseFloat($(this).val()) || 0;
        });

        $('#totalQtyDisplay').text(totalQty.toFixed(2));
        $('#totalValueDisplay').text(totalValue.toFixed(2));
        $('#total_amount').val(totalValue.toFixed(2));
        calcMrrBalance();
    }

    window.calcMrrBalance = function() {
        let total = parseFloat($('#total_amount').val()) || 0;
        let paid = parseFloat($('#paid_amount').val()) || 0;
        let due = total - paid;
        $('#due_balance').val(due.toFixed(2));
    };

    // Form submit validation: ensure at least one item
    $('#mrrForm').on('submit', function (e) {
        if ($('#itemTableBody tr').length === 0) {
            e.preventDefault();
            alert('Please add at least one material item to the table before saving.');
            $('#itemSelect').select2('open');
            return false;
        }
        return true;
    });
});
</script>
@endsection
