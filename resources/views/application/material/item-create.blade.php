@extends('layouts/contentNavbarLayout')

@section('title', 'Raw Material Item Entry')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Create Raw Material Item') }}</h5>
                    <a href="{{ route('inventory-material-item-lists') }}" class="btn btn-secondary">
                        <i class="bx bx-list-ul me-1"></i> Item List
                    </a>
                </div>

                <div class="card-body">
                    @include('alerts.alert')

                    <form method="POST" action="{{ route('inventory-material-item-create') }}" id="materialItemForm">
                        @csrf

                        <div class="row">
                            <!-- Alias -->
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="alias">Alias / Code</label>
                                <input type="text" 
                                       class="form-control @error('alias') is-invalid @enderror" 
                                       id="alias" 
                                       name="alias" 
                                       placeholder="Enter Alias / Code" 
                                       value="{{ old('alias') }}" />
                                @error('alias')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="barcode">Barcode</label>
                                <input type="text" 
                                       class="form-control @error('barcode') is-invalid @enderror" 
                                       id="barcode" 
                                       name="barcode" 
                                       placeholder="Enter Barcode" 
                                       value="{{ old('barcode') }}" />
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name (EN) -->
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       placeholder="Enter Item Name" 
                                       value="{{ old('name') }}" 
                                       required />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name (BN) -->
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="name_bn">Name (Bangla)</label>
                                <input type="text" 
                                       class="form-control @error('name_bn') is-invalid @enderror" 
                                       id="name_bn" 
                                       name="name_bn" 
                                       placeholder="Enter Bangla Name" 
                                       value="{{ old('name_bn') }}" />
                                @error('name_bn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Group Dropdown -->
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="group">Group</label>
                                <select class="form-select @error('group') is-invalid @enderror" id="group" name="group">
                                    <option value="">-- Select Group --</option>
                                    @foreach($allGroups as $groupId => $groupName)
                                        <option value="{{ $groupId }}" {{ old('group') == $groupId ? 'selected' : '' }}>
                                            {{ $groupName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit Dropdown -->
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="unit">Unit</label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit">
                                    <option value="">-- Select Unit --</option>
                                    @foreach($allUnits as $unitId => $unitName)
                                        <option value="{{ $unitId }}" {{ old('unit') == $unitId ? 'selected' : '' }}>
                                            {{ $unitName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Opening Stock -->
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label" for="opening_stock">Opening Stock</label>
                                <input type="number" 
                                       step="any" 
                                       min="0" 
                                       class="form-control @error('opening_stock') is-invalid @enderror" 
                                       id="opening_stock" 
                                       name="opening_stock" 
                                       placeholder="0.00" 
                                       value="{{ old('opening_stock', '0') }}" />
                                @error('opening_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Opening Value -->
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label" for="opening_value">Opening Value</label>
                                <input type="number" 
                                       step="any" 
                                       min="0" 
                                       class="form-control @error('opening_value') is-invalid @enderror" 
                                       id="opening_value" 
                                       name="opening_value" 
                                       placeholder="0.00" 
                                       value="{{ old('opening_value', '0') }}" />
                                @error('opening_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label" for="quantity">Quantity</label>
                                <input type="number" 
                                       step="any" 
                                       min="0" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       placeholder="0.00" 
                                       value="{{ old('quantity', '0') }}" />
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4" />

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('inventory-material-item-lists') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <div>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-secondary me-2">
                                    <i class="bx bx-plus-circle me-1"></i> Save & Add Another
                                </button>
                                <button type="submit" name="action" value="save" class="btn btn-primary" onclick="return confirm('Are you sure you want to save this item?')">
                                    <i class="bx bx-check me-1"></i> Save Item
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
document.addEventListener('DOMContentLoaded', function () {
    const openingStockInput = document.getElementById('opening_stock');
    const quantityInput = document.getElementById('quantity');
    let quantityManuallyEdited = false;

    quantityInput.addEventListener('input', function () {
        quantityManuallyEdited = true;
    });

    openingStockInput.addEventListener('input', function () {
        if (!quantityManuallyEdited || quantityInput.value === '' || quantityInput.value === '0') {
            quantityInput.value = openingStockInput.value;
        }
    });
});
</script>
@endsection
