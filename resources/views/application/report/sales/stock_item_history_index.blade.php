@extends($pdf ? 'layouts/blankLayout' : 'layouts/contentNavbarLayout')

@section('title', 'Stock Item History Report')

@section('content')
<style>
    .form-label, .col-form-label {
        font-size: 0.70rem;
        text-transform: uppercase;
        letter-spacing: inherit;
        font-weight: 600;
    }
    fieldset.border {
        border: 1px solid #d9dee3 !important;
    }
    legend {
        font-size: 0.85rem;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-primary">
                        <i class="bx bx-history me-2"></i>{{ __('Stock Item History Report') }}
                    </h5>
                    <span class="badge bg-label-info">Item Movement Ledger</span>
                </div>
            </div>

            <form method="POST" 
                  target="_blank" 
                  action="{{ route('report-sales-stock-item-history-report') }}" 
                  id="stockItemHistoryForm"
                  onsubmit="return validateHistoryForm(event)">
                @csrf
                <div class="card-body py-4">
                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <!-- Report Filters -->
                            <fieldset class="border p-3 rounded mb-3 bg-light">
                                <legend class="float-none w-auto px-2 text-dark fw-bold">
                                    <i class="bx bx-filter-alt me-1"></i> Report Filters
                                </legend>

                                <div class="row g-3">
                                    <!-- Date Range -->
                                    <div class="col-md-5 col-sm-12">
                                        <label class="form-label d-block">Date Range <span class="text-danger">*</span></label>
                                        <div class="row g-2 align-items-center">
                                            <div class="col-5">
                                                <input type="date" 
                                                       class="form-control form-control-sm" 
                                                       id="s_date" 
                                                       name="s_date" 
                                                       value="{{ $s_date }}" 
                                                       required />
                                            </div>
                                            <div class="col-2 text-center col-form-label">To</div>
                                            <div class="col-5">
                                                <input type="date" 
                                                       class="form-control form-control-sm" 
                                                       id="e_date" 
                                                       name="e_date" 
                                                       value="{{ $e_date }}" 
                                                       required />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Category Filter (Optional) -->
                                    <div class="col-md-3 col-sm-12">
                                        <label class="form-label" for="category_id">Category</label>
                                        <select class="form-control form-control-sm js-example-basic-single" 
                                                id="category_id" 
                                                name="category_id" 
                                                onchange="filterItemsByCategory()">
                                            <option value="">-- All Categories --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $selected_category == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Stock Item (Required) -->
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="stock_item_id">Stock Item <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm js-example-basic-single" 
                                                id="stock_item_id" 
                                                name="stock_item_id" 
                                                required>
                                            <option value="">-- Select Stock Item --</option>
                                            @foreach ($stockItems as $item)
                                                <option value="{{ $item->id }}" 
                                                        data-category="{{ $item->category }}"
                                                        {{ $selected_item == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }} {{ $item->unitDetail ? '('.$item->unitDetail->name.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Actions -->
                            <div class="d-flex justify-content-end gap-2 pt-2">
                                <button type="reset" class="btn btn-sm btn-outline-secondary" onclick="resetForm()">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bx bx-file me-1"></i> Generate Stock Item History Report
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateHistoryForm(e) {
        var item = document.getElementById('stock_item_id').value;
        if (!item) {
            alert('Please select a Stock Item to generate history report.');
            e.preventDefault();
            return false;
        }
        return true;
    }

    function filterItemsByCategory() {
        var catId = document.getElementById('category_id').value;
        var itemSelect = document.getElementById('stock_item_id');
        var options = itemSelect.querySelectorAll('option');

        options.forEach(function(opt) {
            if (opt.value === '') {
                opt.style.display = 'block';
                return;
            }
            var itemCat = opt.getAttribute('data-category');
            if (!catId || itemCat == catId) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        });

        var currentSelected = itemSelect.options[itemSelect.selectedIndex];
        if (currentSelected && currentSelected.style.display === 'none') {
            itemSelect.value = '';
            if (window.jQuery && $(itemSelect).data('select2')) {
                $(itemSelect).val('').trigger('change');
            }
        }
    }

    function resetForm() {
        setTimeout(function() {
            filterItemsByCategory();
            if (window.jQuery && $('.js-example-basic-single').length) {
                $('.js-example-basic-single').val('').trigger('change');
            }
        }, 50);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && $('.js-example-basic-single').length) {
            $('.js-example-basic-single').select2({ width: '100%' });
            $('#category_id').on('change', function() {
                filterItemsByCategory();
            });
        }
    });
</script>
@endsection
