@extends('layouts/contentNavbarLayout')

@section('title', 'Production List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Filter Section -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('inventory-production-list') }}" class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-sm" 
                                   placeholder="Search Voucher No, Production Item, Barcode..." 
                                   value="{{ request('search') }}" />
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="date" 
                                   name="date_from" 
                                   class="form-control form-control-sm" 
                                   title="Date From" 
                                   value="{{ request('date_from') }}" />
                        </div>
                        <div class="col-6 col-md-2">
                            <input type="date" 
                                   name="date_to" 
                                   class="form-control form-control-sm" 
                                   title="Date To" 
                                   value="{{ request('date_to') }}" />
                        </div>
                        <div class="col-12 col-md-4 d-flex justify-content-end gap-1">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-search me-1"></i> Filter
                            </button>
                            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                                <a href="{{ route('inventory-production-list') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Card -->
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Finished Goods Production List') }}</h5>
                    <a href="{{ route('inventory-production-entry') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus-circle me-1"></i> New Production Entry
                    </a>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Voucher No</th>
                                    <th>Date</th>
                                    <th>Production Item (Finished Good)</th>
                                    <th class="text-end">Produced Qty</th>
                                    <th class="text-center">Materials Consumed</th>
                                    <th class="text-end">Total Material Qty</th>
                                    <th class="text-end">Total Cost</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($productions as $key => $prod)
                                    @php
                                        $agg = $aggregates[$prod->voucher_no] ?? null;
                                        $totalProdQty = $agg ? $agg->total_prod_qty : 0;
                                        $totalProdVal = $agg ? $agg->total_prod_value : 0;
                                        $itemCount = $agg ? $agg->total_items : 0;
                                        $linkedVoucher = $vouchers[$prod->voucher_no] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ method_exists($productions, 'firstItem') && $productions->firstItem() ? ($productions->firstItem() + $key) : ($key + 1) }}</td>
                                        <td>
                                            <a href="{{ route('inventory-production-details', $prod->id) }}" class="fw-bold text-primary">
                                                {{ $prod->voucher_no }}
                                            </a>
                                            @if($linkedVoucher)
                                                <a href="{{ url('voucher/details/' . $linkedVoucher->id) }}" 
                                                   class="badge bg-label-info ms-1" 
                                                   title="View Accounting Voucher" 
                                                   target="_blank">
                                                    <i class="bx bx-receipt"></i> Voucher
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($prod->date)) }}</td>
                                        <td>
                                            <strong>{{ $prod->stockItem ? $prod->stockItem->name : 'Item #'.$prod->production_item }}</strong>
                                            @if($prod->stockItem && $prod->stockItem->barcode)
                                                <span class="text-muted small ms-1">({{ $prod->stockItem->barcode }})</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ number_format((float)$prod->quantity, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-info">{{ $itemCount }} items</span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format((float)$totalProdQty, 2) }}</td>
                                        <td class="text-end fw-bold text-primary">{{ number_format((float)$totalProdVal, 2) }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-icon btn-outline-info me-1" 
                                               href="{{ route('inventory-production-details', $prod->id) }}" 
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-primary me-1" 
                                               href="{{ route('inventory-production-edit', $prod->id) }}" 
                                               title="Edit Production">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-danger" 
                                               href="{{ route('inventory-production-delete', $prod->id) }}" 
                                               title="Delete Production" 
                                               onclick="return confirm('Are you sure you want to delete this Production record? This will revert finished goods stock and delete the accounting voucher.')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bx bx-info-circle me-1"></i> No Production Records Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($productions->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $productions->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
