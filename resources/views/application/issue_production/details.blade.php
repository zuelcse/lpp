@extends('layouts/contentNavbarLayout')

@section('title', 'Issue to Production Details - ' . $issue->voucher_no)

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
                <a href="{{ route('inventory-issue-production-list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to Issue List
                </a>
                <div>
                    <a href="{{ route('inventory-issue-production-edit', $issue->id) }}" class="btn btn-primary btn-sm me-1">
                        <i class="bx bx-edit-alt me-1"></i> Edit Issue
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                        <i class="bx bx-printer me-1"></i> Print Issue
                    </button>
                </div>
            </div>

            <!-- Printable Voucher Card -->
            <div class="card shadow-sm border p-4">
                <!-- Header -->
                <div class="text-center pb-3 border-bottom mb-3">
                    <h3 class="fw-bold mb-1">{{ config('variables.templateName', 'Ali Software Inventory') }}</h3>
                    <h5 class="text-uppercase text-primary fw-bold tracking-wide mb-1">Issue to Production Voucher</h5>
                    <p class="text-muted small mb-0">Raw Materials Requisition / Issue to Floor</p>
                </div>

                <!-- Info Grid -->
                <div class="row mb-4">
                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 140px;">Voucher No:</th>
                                <td class="fw-bold text-primary">{{ $issue->voucher_no }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Issue Date:</th>
                                <td>{{ date('d-m-Y', strtotime($issue->date)) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-sm-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 140px;">Created At:</th>
                                <td>{{ date('d-m-Y h:i A', strtotime($issue->created_at)) }}</td>
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
                                <th class="text-start">Material Item Description</th>
                                <th style="width: 140px;">Barcode</th>
                                <th style="width: 100px;">Unit</th>
                                <th class="text-end" style="width: 150px;">Issue Qty</th>
                                <th class="text-start" style="width: 200px;">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandQty = 0;
                            @endphp
                            @forelse($issueItems as $index => $item)
                                @php
                                    $grandQty += (float)$item->issue2production_qty;
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
                                    <td class="text-end fw-bold">{{ number_format((float)$item->issue2production_qty, 2) }}</td>
                                    <td class="small">{{ $item->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">No material items recorded for this issue voucher.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Total Issue Quantity:</td>
                                <td class="text-end text-primary">{{ number_format($grandQty, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Signatures -->
                <div class="row pt-5 mt-4 text-center">
                    <div class="col-4">
                        <div class="border-top pt-2 fw-semibold">Issued By</div>
                    </div>
                    <div class="col-4">
                        <div class="border-top pt-2 fw-semibold">Received By</div>
                    </div>
                    <div class="col-4">
                        <div class="border-top pt-2 fw-semibold">Authorized By</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
