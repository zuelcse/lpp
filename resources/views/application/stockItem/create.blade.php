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
                            <form method="POST" action="{{url('/stockitem/create-action')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-12 col-sm-4 col-form-label" for="basic-default-name">Name</label>
                                            <div class="col-12 col-sm-8">
                                                <input type="text" class="form-control" id="basic-default-name" name="name" placeholder="Enter EN Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-12 col-sm-4 col-form-label" for="basic-default-name_bn">Name BN</label>
                                            <div class="col-12 col-sm-8">
                                                <input type="text" class="form-control" id="basic-default-name_bn" name="name_bn" placeholder="Enter BN Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="basic-default-manufacturer">Manufacturing</label>
                                            <div class="col-sm-8">
                                                <select type="text" id="manufacturer" class="form-control  " name="manufacturer" placeholder="Enter Base units" aria-label="Enter Group Name" aria-describedby="manufacturer">
                                                    <option>Select Manufacturer</option>
                                                    @foreach($allManufacturer as $key => $item)
                                                    <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="basic-default-category">Group</label>
                                            <div class="col-sm-8">
                                                <select type="text" id="category" class="form-control  " name="category" placeholder="Enter Group Name" aria-label="Enter Group Name" aria-describedby="category">
                                                    <option>Select Group</option>
                                                    @foreach($allGroup as $key => $item)
                                                    <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="barcode">Barcode No</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="barcode" class="form-control  " name="barcode" placeholder="Enter Barcode No" aria-label="Enter Barcode No" aria-describedby="taxClassificationName" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="part_no">Part No</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="part_no" class="form-control  " name="part_no" placeholder="Enter Part No" aria-label="Enter Tax Part No" aria-describedby="part_no" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="unit">Unit</label>
                                            <div class="col-sm-8">
                                                <select type="text" id="unit" class="form-control  " name="unit" placeholder="Enter unit" aria-label="Enter unit" aria-describedby="unit">
                                                    <option>Select Unit</option>
                                                    @foreach($allUnits as $key => $item)
                                                    <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label" for="salesRate">Sales Rate</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="salesRate" class="form-control  " name="salesRate" placeholder="Enter Sales Rate" aria-label="Enter Sales Rate" aria-describedby="salesRate" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
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
