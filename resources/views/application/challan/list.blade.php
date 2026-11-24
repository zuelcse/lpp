@extends('layouts/contentNavbarLayout')

@section('title', 'Challan List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Filter Card -->
            <div class="card mb-3 shadow-sm">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('challan-list') }}" class="row g-2 align-items-center">
                        <div class="col-12 col-md-3">
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-sm"
                                   placeholder="Search Challan, Customer, Vehicle, Inv..."
                                   value="{{ request('search') }}" />
                        </div>
                        <div class="col-12 col-md-2">
                            <select name="ledger_id" class="form-select form-select-sm js-example-basic-single">
                                <option value="">-- All Customers --</option>
                                @foreach($ledgers as $ledger)
                                    <option value="{{ $ledger->id }}" {{ (string) request('ledger_id') === (string) $ledger->id ? 'selected' : '' }}>
                                        {{ $ledger->alias ? $ledger->alias . ' - ' : '' }}{{ $ledger->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">-- All Status --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-1">
                            <input type="date"
                                   name="date_from"
                                   class="form-control form-control-sm"
                                   title="Date From"
                                   value="{{ request('date_from') }}" />
                        </div>
                        <div class="col-6 col-md-1">
                            <input type="date"
                                   name="date_to"
                                   class="form-control form-control-sm"
                                   title="Date To"
                                   value="{{ request('date_to') }}" />
                        </div>
                        <div class="col-12 col-md-3 d-flex justify-content-end gap-1">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-search me-1"></i> Filter
                            </button>
                            @if(request()->hasAny(['search', 'ledger_id', 'status', 'date_from', 'date_to']))
                                <a href="{{ route('challan-list') }}" class="btn btn-outline-secondary btn-sm">
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
                    <h5 class="mb-0">{{ __('Delivery Challan List') }}</h5>
                    <a href="{{ route('challan-create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus-circle me-1"></i> New Challan
                    </a>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Challan No</th>
                                    <th>Date</th>
                                    <th>Customer / Party</th>
                                    <th>Invoice Ref</th>
                                    <th>Vehicle / Driver</th>
                                    <th class="text-center">Items</th>
                                    <th class="text-end">Total Qty</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @php
                                    $statusBadges = [
                                        'Pending' => 'bg-label-warning',
                                        'Dispatched' => 'bg-label-info',
                                        'Delivered' => 'bg-label-success',
                                        'Cancelled' => 'bg-label-danger',
                                    ];
                                @endphp
                                @forelse($challans as $key => $challan)
                                    <tr>
                                        <td>{{ $challans->firstItem() + $key }}</td>
                                        <td>
                                            <a href="{{ route('challan-details', $challan->id) }}" class="fw-bold text-primary">
                                                {{ $challan->challan_no }}
                                            </a>
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($challan->challan_date)) }}</td>
                                        <td>
                                            <strong>{{ $challan->ledger ? $challan->ledger->name : '-' }}</strong>
                                            @if($challan->contact_phone)
                                                <div class="small text-muted">{{ $challan->contact_phone }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($challan->sales)
                                                <a href="{{ url('/sales/details-pp/' . $challan->sales->id) }}" target="_blank" class="badge bg-label-primary">
                                                    {{ $challan->sales->voucher_no }}
                                                </a>
                                            @elseif($challan->reference_no)
                                                <span class="text-muted">{{ $challan->reference_no }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($challan->vehicle_no || $challan->driver_name)
                                                <div>{{ $challan->vehicle_no ?: '-' }}</div>
                                                @if($challan->driver_name)
                                                    <div class="small text-muted">{{ $challan->driver_name }}</div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-info">{{ $challan->items_count }} items</span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format((float) $challan->total_qty, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $statusBadges[$challan->status] ?? 'bg-label-secondary' }}">
                                                {{ $challan->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-icon btn-outline-info me-1"
                                               href="{{ route('challan-details', $challan->id) }}"
                                               title="View / Print Challan">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-primary me-1"
                                               href="{{ route('challan-edit', $challan->id) }}"
                                               title="Edit Challan">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                            <a class="btn btn-sm btn-icon btn-outline-danger"
                                               href="{{ route('challan-delete', $challan->id) }}"
                                               title="Delete Challan"
                                               onclick="return confirm('Are you sure you want to delete this challan?')">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">No Delivery Challan found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $challans->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
