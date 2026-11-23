@extends('layouts/contentNavbarLayout')

@section('title', 'MRR List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Filter Section -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('inventory-mrr-list') }}" class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-sm" 
                                   placeholder="Search Voucher No, Supplier, Challan..." 
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
                                <a href="{{ route('inventory-mrr-list') }}" class="btn btn-outline-secondary btn-sm">
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
                    <h5 class="mb-0">{{ __('Material Receive Report (MRR) List') }}</h5>
                    <a href="{{ route('inventory-mrr-entry') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus-circle me-1"></i> New MRR Entry
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
                                    <th>Supplier Name</th>
                                    <th>Challan No</th>
                                    <th>Challan Date</th>
                                    <th class="text-center">Items</th>
                                    <th class="text-end">Total Qty</th>
                                    <th class="text-end">Total Value (TK)</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($mrrs as $key => $mrr)
                                    @php
                                        $agg = $aggregates[$mrr->voucher_no] ?? null;
                                        $totalQty = $agg ? $agg->total_qty : 0;
                                        $totalValue = $agg ? $agg->total_value : 0;
                                        $itemCount = $agg ? $agg->total_items : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ method_exists($mrrs, 'firstItem') && $mrrs->firstItem() ? ($mrrs->firstItem() + $key) : ($key + 1) }}</td>
                                        <td>
                                            <a href="{{ route('inventory-mrr-details', $mrr->id) }}" class="fw-bold text-primary">
                                                {{ $mrr->voucher_no }}
                                            </a>
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($mrr->date)) }}</td>
                                        <td><strong>{{ $mrr->supplier ? $mrr->supplier->name : '-' }}</strong></td>
                                        <td>{{ $mrr->challan_no ?: '-' }}</td>
                                        <td>{{ $mrr->challan_date ? date('d-m-Y', strtotime($mrr->challan_date)) : '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-label-info">{{ $itemCount }} items</span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format((float)$totalQty, 2) }}</td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">{{ number_format((float)$totalValue, 2) }}</span>
                                            @if(!empty($mrr->paid_amount) && $mrr->paid_amount > 0)
                                                <div class="small text-muted">Paid: {{ number_format((float)$mrr->paid_amount, 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-icon btn-outline-info me-1" 
                                               href="{{ route('inventory-mrr-details', $mrr->id) }}" 
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-primary me-1" 
                                               href="{{ route('inventory-mrr-edit', $mrr->id) }}" 
                                               title="Edit MRR">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-danger" 
                                               href="{{ route('inventory-mrr-delete', $mrr->id) }}" 
                                               title="Delete MRR" 
                                               onclick="return confirm('Are you sure you want to delete this MRR? This will revert received stock.')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            <i class="bx bx-info-circle me-1"></i> No Material Receive Reports Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($mrrs->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $mrrs->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
