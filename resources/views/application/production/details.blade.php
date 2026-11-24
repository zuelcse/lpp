@extends('layouts/contentNavbarLayout')

@section('title', 'Production Details - ' . $production->voucher_no)

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
                <a href="{{ route('inventory-production-list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to Production List
                </a>
                <div>
                    @if(isset($voucher) && $voucher)
                        <a href="{{ url('voucher/details/' . $voucher->id) }}" class="btn btn-outline-info btn-sm me-1" target="_blank">
                            <i class="bx bx-book-bookmark me-1"></i> View Accounting Voucher
                        </a>
                    @endif
                    <a href="{{ route('inventory-production-edit', $production->id) }}" class="btn btn-primary btn-sm me-1">
                        <i class="bx bx-edit-alt me-1"></i> Edit Production
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                        <i class="bx bx-printer me-1"></i> Print Production
                    </button>
                </div>
            </div>

            <!-- Printable Voucher Card -->
            <div class="card shadow-sm border p-4 mb-4">
                <!-- Header -->
                <div class="text-center pb-3 border-bottom mb-3">
                    <h3 class="fw-bold mb-1">{{ config('variables.templateName', 'Ali Software Inventory') }}</h3>
                    <h5 class="text-uppercase text-primary fw-bold tracking-wide mb-1">Production Voucher</h5>
                    <p class="text-muted small mb-0">Finished Goods Manufacturing &amp; Materials Consumption</p>
                </div>

                <!-- Info Grid -->
                <div class="row mb-3">
                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 140px;">Voucher No:</th>
                                <td class="fw-bold text-primary">{{ $production->voucher_no }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Production Date:</th>
                                <td>{{ date('d-m-Y', strtotime($production->date)) }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Finished Item:</th>
                                <td class="fw-bold">
                                    {{ $production->stockItem ? $production->stockItem->name : 'Item #'.$production->production_item }}
                                    @if($production->stockItem && $production->stockItem->barcode)
                                        <span class="text-muted small">({{ $production->stockItem->barcode }})</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-sm-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 140px;">Produced Qty:</th>
                                <td class="fw-bold text-success fs-5">
                                    {{ number_format((float)$production->quantity, 2) }}
                                    @if($production->stockItem && $production->stockItem->unitDetail)
                                        <span class="fs-6 text-muted">{{ $production->stockItem->unitDetail->name }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Created At:</th>
                                <td>{{ date('d-m-Y h:i A', strtotime($production->created_at)) }}</td>
                            </tr>
                            @if(!empty($production->narration))
                            <tr>
                                <th class="text-muted">Narration:</th>
                                <td class="small">{{ $production->narration }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <h6 class="fw-bold text-secondary mb-2">
                    <i class="bx bx-layer me-1"></i> Raw Materials Consumed in Production:
                </h6>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th class="text-start">Material Item Description</th>
                                <th style="width: 120px;">Barcode</th>
                                <th style="width: 80px;">Unit</th>
                                <th class="text-end" style="width: 130px;">Consumed Qty</th>
                                <th class="text-end" style="width: 110px;">Rate</th>
                                <th class="text-end" style="width: 130px;">Value</th>
                                <th class="text-start" style="width: 160px;">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandQty = 0;
                                $grandValue = 0;
                            @endphp
                            @forelse($prodItems as $index => $item)
                                @php
                                    $qty = (float)$item->production_qty;
                                    $rate = (float)($item->rate ?? 0);
                                    $val = (float)($item->value ?? round($qty * $rate, 2));
                                    $grandQty += $qty;
                                    $grandValue += $val;
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
                                    <td class="text-end fw-bold">{{ number_format($qty, 2) }}</td>
                                    <td class="text-end">{{ number_format($rate, 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($val, 2) }}</td>
                                    <td class="small">{{ $item->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">No material items recorded for this production voucher.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Total Consumed:</td>
                                <td class="text-end text-primary">{{ number_format($grandQty, 2) }}</td>
                                <td class="text-end">Total Cost:</td>
                                <td class="text-end text-success fs-6">{{ number_format($grandValue, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Signatures -->
                <div class="row pt-5 mt-4 text-center">
                    <div class="col-4">
                        <div class="border-top pt-2 fw-semibold">Prepared By</div>
                    </div>
                    <div class="col-4">
                        <div class="border-top pt-2 fw-semibold">Checked By</div>
                    </div>
                    <div class="col-4">
                        <div class="border-top pt-2 fw-semibold">Authorized By</div>
                    </div>
                </div>
            </div>

            <!-- Linked Accounting Voucher Information (Non-print) -->
            @if(isset($voucher) && $voucher)
            <div class="card shadow-sm border mb-4 no-print">
                <div class="card-header bg-label-info py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="bx bx-receipt me-1"></i> Linked Accounting Voucher Entry (#{{ $voucher->voucher_no }})
                    </h6>
                    <a href="{{ url('voucher/details/' . $voucher->id) }}" class="btn btn-sm btn-info" target="_blank">
                        <i class="bx bx-show me-1"></i> View in Accounting
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <span class="text-muted small">Voucher Type:</span>
                            <span class="badge bg-label-primary d-block">Production (PRD)</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small">Voucher Date:</span>
                            <div class="fw-semibold">{{ date('d-M-Y', strtotime($voucher->date)) }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small">Total Cost / Amount:</span>
                            <div class="fw-bold text-success fs-6">{{ number_format($voucher->total_amount, 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small">Narration:</span>
                            <div class="small">{{ $voucher->narration_remarks ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Debit Head</th>
                                    <th>Credit Head</th>
                                    <th class="text-end" style="width: 140px;">Amount</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($voucher->MasterVoucher as $mv)
                                    @php
                                        $dr = \App\Models\Ledger::find($mv->debit_head);
                                        $cr = \App\Models\Ledger::find($mv->credit_head);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-label-danger me-1">Dr</span>
                                            <strong>{{ $dr ? $dr->name : 'Ledger #'.$mv->debit_head }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-success me-1">Cr</span>
                                            <strong>{{ $cr ? $cr->name : 'Ledger #'.$mv->credit_head }}</strong>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($mv->amount, 2) }}</td>
                                        <td class="small">{{ $mv->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
