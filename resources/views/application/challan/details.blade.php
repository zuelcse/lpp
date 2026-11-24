@extends('layouts/contentNavbarLayout')

@section('title', 'Delivery Challan - ' . $challan->challan_no)

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

@php
    $statusBadges = [
        'Pending' => 'bg-label-warning',
        'Dispatched' => 'bg-label-info',
        'Delivered' => 'bg-label-success',
        'Cancelled' => 'bg-label-danger',
    ];
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Action Bar -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 no-print">
                <a href="{{ route('challan-list') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to Challan List
                </a>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <form method="POST" action="{{ route('challan-status', $challan->id) }}" class="d-flex align-items-center gap-1">
                        @csrf
                        <select name="status" class="form-select form-select-sm" style="width: 140px;">
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ $challan->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Update Status</button>
                    </form>

                    @if($challan->sales)
                        <a href="{{ url('/sales/details-pp/' . $challan->sales->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bx bx-receipt me-1"></i> View Invoice
                        </a>
                    @endif

                    <a href="{{ route('challan-edit', $challan->id) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit-alt me-1"></i> Edit
                    </a>

                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                        <i class="bx bx-printer me-1"></i> Print Challan
                    </button>
                </div>
            </div>

            @include('alerts.alert')

            <!-- Printable Delivery Challan -->
            <div class="card shadow-sm border p-4">
                <div class="text-center pb-3 border-bottom mb-3">
                    <h3 class="fw-bold mb-1">{{ $company->site_name ?? config('variables.templateName', 'Trading Solutions') }}</h3>
                    @if(!empty($company->company_address))
                        <p class="text-muted small mb-1">{!! nl2br(e($company->company_address)) !!}</p>
                    @endif
                    @if(!empty($company->company_phone) || !empty($company->company_email))
                        <p class="text-muted small mb-2">
                            @if(!empty($company->company_phone)) Phone: {{ $company->company_phone }} @endif
                            @if(!empty($company->company_phone) && !empty($company->company_email)) | @endif
                            @if(!empty($company->company_email)) Email: {{ $company->company_email }} @endif
                        </p>
                    @endif
                    <h5 class="text-uppercase text-primary fw-bold mb-0">Delivery Challan</h5>
                </div>

                <!-- Header Details -->
                <div class="row mb-4">
                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 140px;">Customer / Party:</th>
                                <td class="fw-bold">{{ $challan->ledger ? $challan->ledger->name : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Delivery Address:</th>
                                <td>{{ $challan->delivery_address ?: ($challan->ledger->address ?? '-') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Contact Person:</th>
                                <td>{{ $challan->contact_person ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Contact Phone:</th>
                                <td>{{ $challan->contact_phone ?: ($challan->ledger->mobile ?? '-') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-sm-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-muted" style="width: 140px;">Challan No:</th>
                                <td class="fw-bold text-primary">{{ $challan->challan_no }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Challan Date:</th>
                                <td>{{ date('d-m-Y', strtotime($challan->challan_date)) }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Invoice / Ref No:</th>
                                <td>{{ $challan->sales ? $challan->sales->voucher_no : ($challan->reference_no ?: '-') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Vehicle / Driver:</th>
                                <td>
                                    {{ $challan->vehicle_no ?: '-' }}
                                    @if($challan->driver_name) ({{ $challan->driver_name }}) @endif
                                </td>
                            </tr>
                            @if($challan->transport_name)
                                <tr>
                                    <th class="text-muted">Transport:</th>
                                    <td>{{ $challan->transport_name }}</td>
                                </tr>
                            @endif
                            <tr class="no-print">
                                <th class="text-muted">Status:</th>
                                <td>
                                    <span class="badge {{ $statusBadges[$challan->status] ?? 'bg-label-secondary' }}">
                                        {{ $challan->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Line Items -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 55px;">SL</th>
                                <th class="text-start">Item Description</th>
                                <th style="width: 110px;">Unit</th>
                                <th class="text-end" style="width: 140px;">Quantity</th>
                                <th class="text-start" style="width: 200px;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalQty = 0; @endphp
                            @forelse($challan->items as $index => $item)
                                @php $totalQty += (float) $item->quantity; @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->item_name }}</strong>
                                        @if($item->stockItem && $item->stockItem->barcode)
                                            <div class="small text-muted">Code: {{ $item->stockItem->barcode }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->unit_name ?: ($item->stockItem->unitDetail->name ?? '-') }}</td>
                                    <td class="text-end fw-bold">{{ number_format((float) $item->quantity, 2) }}</td>
                                    <td>{{ $item->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total Quantity</th>
                                <th class="text-end fw-bold">{{ number_format($totalQty, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($challan->narration)
                    <div class="mb-4">
                        <strong>Narration / Remarks:</strong>
                        <p class="mb-0 text-muted">{{ $challan->narration }}</p>
                    </div>
                @endif

                <div class="row mt-5 pt-4 text-center">
                    <div class="col-4">
                        <div class="border-top border-secondary pt-2 mx-3">
                            <strong>Prepared By</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-top border-secondary pt-2 mx-3">
                            <strong>Driver / Carrier Signature</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-top border-secondary pt-2 mx-3">
                            <strong>Receiver's Signature &amp; Seal</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
