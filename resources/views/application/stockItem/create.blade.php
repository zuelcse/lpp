@extends('layouts/contentNavbarLayout')

@section('title', 'New Item')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Create Item') }}</span>
                    <button class="btn btn-secondary float-left" onclick="history.back()">Go Back</button>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-xxl">
                        <div class="card mb-12">
                        <div class="card-header d-flex align-items-center justify-content-between">
                        </div>
                        <div class="card-body">
                            @include('alerts.alert')
                            <form method="POST" action="{{url('/stockitem/create-action')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-12 col-sm-4 col-form-label" for="basic-default-name">Name <span class="text-danger">*</span></label>
                                            <div class="col-12 col-sm-8">
                                                <input type="text" class="form-control" id="basic-default-name" name="name" placeholder="Enter EN Name" value="{{ old('name') }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-12 col-sm-4 col-form-label" for="basic-default-name_bn">Name BN</label>
                                            <div class="col-12 col-sm-8">
                                                <input type="text" class="form-control" id="basic-default-name_bn" name="name_bn" placeholder="Enter BN Name" value="{{ old('name_bn') }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="manufacturer">Manufacturing</label>
                                            <div class="col-sm-8">
                                                <select id="manufacturer" class="form-select js-example-basic-single" name="manufacturer" aria-label="Select Manufacturer">
                                                    <option value="">-- Select Manufacturer --</option>
                                                    @foreach($allManufacturer as $key => $item)
                                                    <option value="{{$key}}" {{ (string)old('manufacturer') === (string)$key ? 'selected' : '' }}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="category">Item Category</label>
                                            <div class="col-sm-8">
                                                <select id="category" class="form-select js-example-basic-single" name="category" aria-label="Select Item Category">
                                                    <option value="">-- Select Item Category --</option>
                                                    @foreach($allGroup as $key => $item)
                                                    <option value="{{$key}}" {{ (string)old('category') === (string)$key ? 'selected' : '' }}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="sub_category">Item Sub Category</label>
                                            <div class="col-sm-8">
                                                <select id="sub_category" class="form-select js-example-basic-single" name="sub_category" aria-label="Select Item Sub Category">
                                                    <option value="">-- Select Item Sub Category --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="barcode">Barcode No <span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" id="barcode" class="form-control" name="barcode" placeholder="Enter Barcode No" value="{{ old('barcode') }}" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="part_no">Part No</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="part_no" class="form-control" name="part_no" placeholder="Enter Part No" value="{{ old('part_no') }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="unit">Unit <span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <select id="unit" class="form-select js-example-basic-single" name="unit" aria-label="Select Unit" required>
                                                    <option value="">-- Select Unit --</option>
                                                    @foreach($allUnits as $key => $item)
                                                    <option value="{{$key}}" {{ (string)old('unit') === (string)$key ? 'selected' : '' }}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="salesRate">Sales Rate</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="salesRate" class="form-control" name="salesRate" placeholder="Enter Sales Rate" value="{{ old('salesRate') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you want to proceed?')">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function () {
        const $category = $('#category');
        const $subCategory = $('#sub_category');
        const oldSubCategory = @json(old('sub_category'));

        function loadSubCategories(categoryId, selectedSubCategory = null) {
            $subCategory.empty().append('<option value="">-- Select Item Sub Category --</option>');

            if (!categoryId) {
                $subCategory.trigger('change');
                return;
            }

            $.getJSON("{{ url('/sub-categories') }}/" + categoryId, function (data) {
                $.each(data, function (_, item) {
                    const isSelected = selectedSubCategory !== null && String(selectedSubCategory) === String(item.id);
                    $subCategory.append(new Option(item.name, item.id, isSelected, isSelected));
                });
                $subCategory.trigger('change');
            });
        }

        $category.on('change', function () {
            loadSubCategories($(this).val(), null);
        });

        if ($category.val()) {
            loadSubCategories($category.val(), oldSubCategory);
        }
    });
</script>
@endsection
