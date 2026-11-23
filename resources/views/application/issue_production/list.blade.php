@extends('layouts/contentNavbarLayout')

@section('title', 'Issue to Production List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Filter Section -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('inventory-issue-production-list') }}" class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-sm" 
                                   placeholder="Search Voucher No..." 
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
                                <a href="{{ route('inventory-issue-production-list') }}" class="btn btn-outline-secondary btn-sm">
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
                    <h5 class="mb-0">{{ __('Materials Issue to Production List') }}</h5>
                    <a href="{{ route('inventory-issue-production-entry') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus-circle me-1"></i> New Issue Entry
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
                                    <th class="text-center">Total Items</th>
                                    <th class="text-end">Total Issue Qty</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($issues as $key => $issue)
                                    @php
                                        $agg = $aggregates[$issue->voucher_no] ?? null;
                                        $totalIssueQty = $agg ? $agg->total_issue_qty : 0;
                                        $itemCount = $agg ? $agg->total_items : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ method_exists($issues, 'firstItem') && $issues->firstItem() ? ($issues->firstItem() + $key) : ($key + 1) }}</td>
                                        <td>
                                            <a href="{{ route('inventory-issue-production-details', $issue->id) }}" class="fw-bold text-primary">
                                                {{ $issue->voucher_no }}
                                            </a>
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($issue->date)) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-label-info">{{ $itemCount }} items</span>
                                        </td>
                                        <td class="text-end fw-bold text-primary">{{ number_format((float)$totalIssueQty, 2) }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-icon btn-outline-info me-1" 
                                               href="{{ route('inventory-issue-production-details', $issue->id) }}" 
                                               title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-primary me-1" 
                                               href="{{ route('inventory-issue-production-edit', $issue->id) }}" 
                                               title="Edit Issue">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-danger" 
                                               href="{{ route('inventory-issue-production-delete', $issue->id) }}" 
                                               title="Delete Issue" 
                                               onclick="return confirm('Are you sure you want to delete this issue record? This will revert raw material stock deductions.')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bx bx-info-circle me-1"></i> No Issue to Production Records Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($issues->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $issues->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
