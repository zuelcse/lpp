@extends('layouts/contentNavbarLayout')

@section('title', 'MRR Details - ' . $mrr->voucher_no)

@section('content')
<style>
@media print {
    .no-print, .layout-menu, .layout-navbar, .content-footer {
        display: none !important;
    }
    .content-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Action Bar -->
            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <a href="{{ route('inventory-mrr-list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to MRR List
                </a>
                <div>
                    @if(!empty($voucher))
                        <a href="{{ url('voucher/details/' . $voucher->id) }}" target="_blank" class="btn btn-outline-info btn-sm me-1" title="View Accounting Voucher">
                            <i class="bx bx-receipt me-1"></i> View Voucher
                        </a>
                    @endif
                    <a href="{{ route('inventory-mrr-edit', $mrr->id) }}" class="btn btn-primary btn-sm me-1">
                        <i class="bx bx-edit-alt me-1"></i> Edit MRR
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                        <i class="bx bx-printer me-1"></i> Print MRR
                    </button>
                </div>
            </div>

            <!-- Printable Voucher Card -->
            <div class="card shadow-sm border p-4">
                <!-- Header -->
                <div class="text-center pb-3 border-bottom mb-3">
                    <h3 class="fw-bold mb-1">{{ config('variables.templateName', 'Ali Software Inventory') }}</h3>
                    <h5 class="text-uppercase text-primary fw-bold tracking-wide mb-1">Material Receive Report (MRR)</h5>
                    <p class="text-muted small mb-0">Raw Materials Inventory Management System</p>
                </div>

                <!-- Info Grid -->
                <div class="row mb-4">
                    <div class="col-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 130px;">Voucher No:</th>
                                <td class="fw-bold text-primary">{{ $mrr->voucher_no }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Receive Date:</th>
                                <td>{{ date('d-m-Y', strtotime($mrr->date)) }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Supplier:</th>
                                <td class="fw-bold">{{ $mrr->supplier ? $mrr->supplier->name : '-' }}{{ ($mrr->supplier && $mrr->supplier->alias) ? ' ('.$mrr->supplier->alias.')' : '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 130px;">Challan No:</th>
                                <td>{{ $mrr->challan_no ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Challan Date:</th>
                                <td>{{ $mrr->challan_date ? date('d-m-Y', strtotime($mrr->challan_date)) : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Created At:</th>
                                <td>{{ date('d-m-Y h:i A', strtotime($mrr->created_at)) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="text-start">Item Description</th>
                                <th style="width: 140px;">Barcode</th>
                                <th style="width: 100px;">Unit</th>
                                <th class="text-end" style="width: 120px;">Received Qty</th>
                                <th class="text-end" style="width: 120px;">Rate (TK)</th>
                                <th class="text-end" style="width: 130px;">Value (TK)</th>
                                <th class="text-start" style="width: 160px;">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandQty = 0;
                                $grandValue = 0;
                            @endphp
                            @forelse($mrrItems as $index => $item)
                                @php
                                    $grandQty += (float)$item->received_qty;
                                    $grandValue += (float)$item->value;
                                    $mat = $item->materialItem;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $mat ? $mat->name : 'Item #'.$item->item_id }}</strong>
                                        @if($mat && $mat->name_bn)
                                            <div class="text-muted small">{{ $mat->name_bn }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $mat && $mat->barcode ? $mat->barcode : '-' }}</td>
                                    <td class="text-center">{{ $mat && $mat->unitDetail ? $mat->unitDetail->name : '-' }}</td>
                                    <td class="text-end fw-bold">{{ number_format((float)$item->received_qty, 2) }}</td>
                                    <td class="text-end">{{ number_format((float)$item->rate, 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format((float)$item->value, 2) }}</td>
                                    <td class="small">{{ $item->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">No material items recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Grand Total:</td>
                                <td class="text-end">{{ number_format($grandQty, 2) }}</td>
                                <td></td>
                                <td class="text-end text-success">{{ number_format($grandValue, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Financial & Voucher Summary -->
                <div class="row mb-4">
                    <div class="col-6">
                        @if($mrr->narration)
                            <div class="p-2 border rounded bg-light mb-2">
                                <span class="fw-bold text-muted small d-block">Narration / Remarks:</span>
                                <span>{{ $mrr->narration }}</span>
                            </div>
                        @endif

                        @if(!empty($voucher))
                            <div class="p-2 border rounded bg-light">
                                <span class="fw-bold text-primary small d-block mb-1">
                                    <i class="bx bx-receipt me-1"></i> Linked Accounting Voucher:
                                    <a href="{{ url('voucher/details/' . $voucher->id) }}" target="_blank" class="fw-bold">
                                        #{{ $voucher->voucher_no }}
                                    </a>
                                </span>
                                @if($voucher->MasterVoucher && $voucher->MasterVoucher->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered bg-white mb-0 small">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Debit Head</th>
                                                    <th>Credit Head</th>
                                                    <th class="text-end">Amount (TK)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($voucher->MasterVoucher as $mv)
                                                    <tr>
                                                        <td>{{ $mv->DebitLedger ? $mv->DebitLedger->name : 'Ledger #' . $mv->debit_head }}</td>
                                                        <td>{{ $mv->CreditLedger ? $mv->CreditLedger->name : 'Ledger #' . $mv->credit_head }}</td>
                                                        <td class="text-end fw-bold">{{ number_format((float)$mv->amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="col-6">
                        <table class="table table-sm table-bordered mb-0">
                            <tr>
                                <th class="text-end bg-light" style="width: 55%;">Total Amount (TK):</th>
                                <td class="text-end fw-bold fs-6">{{ number_format($grandValue, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end bg-light">Paid Amount (TK):</th>
                                <td class="text-end text-success fw-bold">{{ number_format((float)($mrr->paid_amount ?? 0), 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end bg-light">Due / Balance (TK):</th>
                                <td class="text-end text-danger fw-bold">{{ number_format($grandValue - (float)($mrr->paid_amount ?? 0), 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="row pt-5 mt-4 text-center">
                    <div class="col-3">
                        <div class="border-top pt-2 small text-muted">Received By</div>
                    </div>
                    <div class="col-3">
                        <div class="border-top pt-2 small text-muted">Store In-Charge</div>
                    </div>
                    <div class="col-3">
                        <div class="border-top pt-2 small text-muted">Checked By</div>
                    </div>
                    <div class="col-3">
                        <div class="border-top pt-2 small text-muted">Authorized Signature</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
