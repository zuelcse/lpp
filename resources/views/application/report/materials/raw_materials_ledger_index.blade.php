@extends($pdf ? 'layouts/blankLayout' : 'layouts/contentNavbarLayout')

@section('title', 'Raw Materials Ledger')

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
                        <i class="bx bx-layer me-2"></i>{{ __('Raw Materials Ledger Report') }}
                    </h5>
                    <span class="badge bg-label-info">Report Panel</span>
                </div>
            </div>

            <form method="POST" 
                  target="_blank" 
                  action="{{ route('report-materials-raw-materials-ledger-report') }}" 
                  id="reportFilterForm" 
                  onsubmit="return reportRestriction(event)">
                @csrf
                <div class="card-body py-4">
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

                                    <!-- Material Group Filter (Optional) -->
                                    <div class="col-md-3 col-sm-12">
                                        <label class="form-label" for="group_id">Material Group</label>
                                        <select class="form-control form-control-sm js-example-basic-single" 
                                                id="group_id" 
                                                name="group_id" 
                                                onchange="filterItemsByGroup()">
                                            <option value="">-- All Groups --</option>
                                            @foreach ($materialGroups as $grp)
                                                <option value="{{ $grp->id }}" {{ $selected_group == $grp->id ? 'selected' : '' }}>
                                                    {{ $grp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Raw Material Item (Required) -->
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="material_item_id">Raw Material Item <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm js-example-basic-single" 
                                                id="material_item_id" 
                                                name="material_item_id" 
                                                required>
                                            <option value="">-- Select Raw Material Item --</option>
                                            @foreach ($rawMaterials as $item)
                                                <option value="{{ $item->id }}" 
                                                        data-group="{{ $item->group }}"
                                                        {{ $selected_item == $item->id ? 'selected' : '' }}>
                                                    {{ $item->barcode ? $item->barcode . ' - ' : '' }}{{ $item->name }}{{ $item->unitDetail ? ' ('.$item->unitDetail->name.')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Report Action Buttons -->
                            <fieldset class="border p-3 rounded bg-white">
                                <legend class="float-none w-auto px-2 text-dark fw-bold">
                                    <i class="bx bx-file me-1"></i> Reports
                                </legend>

                                <div class="row">
                                    <div class="col-12 col-md-4 mb-1">
                                        <button type="submit" 
                                                name="report_name" 
                                                value="raw_materials_ledger" 
                                                class="btn btn-primary btn-sm w-100 shadow-sm">
                                            <i class="bx bx-receipt me-1"></i> Raw Materials Ledger
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-2 mb-1">
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm w-100" 
                                                onclick="resetReportForm()">
                                            <i class="bx bx-reset me-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Keep a cache of all material items for client-side filtering
    const allMaterials = [
        @foreach ($rawMaterials as $item)
        {
            id: "{{ $item->id }}",
            group: "{{ $item->group }}",
            text: "{{ ($item->barcode ? $item->barcode . ' - ' : '') . addslashes($item->name) . ($item->unitDetail ? ' ('.$item->unitDetail->name.')' : '') }}"
        },
        @endforeach
    ];

    function filterItemsByGroup() {
        const selectedGroup = $('#group_id').val();
        const itemSelect = $('#material_item_id');
        const currentSelected = itemSelect.val();

        itemSelect.empty().append('<option value="">-- Select Raw Material Item --</option>');

        const filtered = selectedGroup 
            ? allMaterials.filter(m => String(m.group) === String(selectedGroup))
            : allMaterials;

        filtered.forEach(m => {
            const isSelected = (String(m.id) === String(currentSelected)) ? 'selected' : '';
            itemSelect.append(`<option value="${m.id}" data-group="${m.group}" ${isSelected}>${m.text}</option>`);
        });

        itemSelect.trigger('change.select2');
    }

    function reportRestriction(event) {
        const item = $('#material_item_id').val();
        const s_date = $('#s_date').val();
        const e_date = $('#e_date').val();

        if (!s_date) {
            alert('⚠️ Please select the start date (From Date)!');
            $('#s_date').focus();
            return false;
        }

        if (!e_date) {
            alert('⚠️ Please select the end date (To Date)!');
            $('#e_date').focus();
            return false;
        }

        if (s_date > e_date) {
            alert('⚠️ From Date cannot be greater than To Date!');
            $('#s_date').focus();
            return false;
        }

        if (!item) {
            alert('⚠️ Please select a Raw Material Item!');
            $('#material_item_id').select2('open');
            return false;
        }

        return true;
    }

    function resetReportForm() {
        $('#reportFilterForm')[0].reset();
        $('#group_id').val('').trigger('change.select2');
        filterItemsByGroup();
        $('#material_item_id').val('').trigger('change.select2');
    }

    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            width: '100%'
        });
    });
</script>
@endsection
