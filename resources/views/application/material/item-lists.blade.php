@extends('layouts/contentNavbarLayout')

@section('title', 'Raw Material Items')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Raw Material Items') }}</h5>
                    <a href="{{ route('inventory-material-item-create') }}" class="btn btn-primary">
                        <i class="bx bx-plus-circle me-1"></i> New Item
                    </a>  
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Alias</th>
                                    <th>Name</th>
                                    <th>Name (BN)</th>
                                    <th>Group</th>
                                    <th>Unit</th>
                                    <th>Barcode</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Opening Stock</th>
                                    <th class="text-end">Opening Value</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($material_items as $key => $item)
                                <tr>
                                    <td>{{ method_exists($material_items, 'firstItem') && $material_items->firstItem() ? ($material_items->firstItem() + $key) : ($key + 1) }}</td>
                                    <td>{{ $item->alias ?: '-' }}</td>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>{{ $item->name_bn ?: '-' }}</td>
                                    <td>
                                        @if(!empty($item->group_name))
                                            <span class="badge bg-label-info">{{ $item->group_name }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($item->unit) && isset($allUnits[$item->unit]))
                                            <span class="badge bg-label-secondary">{{ $allUnits[$item->unit] }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->barcode ?: '-' }}</td>
                                    <td class="text-end">{{ number_format((float)$item->quantity, 2) }}</td>
                                    <td class="text-end">{{ number_format((float)$item->opening_stock, 2) }}</td>
                                    <td class="text-end">{{ number_format((float)$item->opening_value, 2) }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-primary" href="{{ route('inventory-material-item-edit', $item->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="bx bx-info-circle me-1"></i> No Raw Material Items Found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($material_items->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $material_items->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
