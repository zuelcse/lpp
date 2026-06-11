@extends('layouts/contentNavbarLayout')

@section('title', 'New Stock Item')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Update Stock Item') }}</span>
                    <a href="{{url('/stockitem')}}" class="btn btn-secondary float-left">
                        Go Back
                    </a>  
                </div>
                
                <div class="card-body">
                    <div class="row">
                    <div class="col-xxl">
                        <div class="card mb-12">
                        <div class="card-header d-flex align-items-center justify-content-between">
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{url('/stockitem/edit-action/'.$stockItem->id)}}">
                                @csrf
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" id="basic-default-name" name="name" placeholder="Enter Name" value="{{ old('name', $stockItem->name) }}"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="basic-default-parent">Parent</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" id="basic-default-parent" name="parent" placeholder="Enter Parent" value="{{ old('parent', $stockItem->parent) }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="basic-default-category">Category</label>
                                    <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="basic-default-category" class="form-control" name="category" placeholder="Category" aria-label="Category" aria-describedby="basic-default-category" value="{{ old('category', $stockItem->category) }}" />
                                    </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="taxClassificationName">Tax Classification</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="taxClassificationName" class="form-control  " name="taxClassificationName" placeholder="Enter Tax Classification Name" aria-label="Enter Tax Classification Name" aria-describedby="taxClassificationName" value="{{ old('taxClassificationName', $stockItem->taxClassificationName) }}" />
                                    </div>
                                </div>
                            
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="costingMethod">Costing Method</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="costingMethod" class="form-control  " name="costingMethod" placeholder="Enter Tax Costing Method" aria-label="Enter Costing Method" aria-describedby="taxClassificationName" value="{{ old('costingMethod', $stockItem->costingMethod) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="valuationMethod">Valuation Method</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="valuationMethod" class="form-control  " name="valuationMethod" placeholder="Enter Valuation Method" aria-label="Enter Tax Valuation Method" aria-describedby="valuationMethod" value="{{ old('valuationMethod', $stockItem->valuationMethod) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="baseUnits">Base Units</label>
                                    <div class="col-sm-10">
                                    <select type="text" id="baseUnits" class="form-control  " name="baseUnits" placeholder="Enter Base units" aria-label="Enter Base units" aria-describedby="baseUnits">
                                        <option>Select Base Units</option>
                                        @foreach($allUnits as $key => $item)
                                        <option value="{{$key}}"  value="{{ old('baseUnits', $stockItem->baseUnits) }}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="additionalUnits">Additional Units</label>
                                    <div class="col-sm-10">
                                    <select type="text" id="additionalUnits" class="form-control  " name="additionalUnits" placeholder="Enter Additional units" aria-label="Enter Additional units" aria-describedby="additionalUnits">
                                        <option>Select Additional Units</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="conversion">Conversion</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="conversion" class="form-control  " name="conversion" placeholder="Enter Conversionn" aria-label="Enter Conversionn" aria-describedby="conversion" value="{{ old('conversion', $stockItem->conversion) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="openingBalance">Opening Balance</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="openingBalance" class="form-control  " name="openingBalance" placeholder="Enter Opening Balance" aria-label="Enter Opening Balance" aria-describedby="openingBalance" value="{{ old('openingBalance', $stockItem->openingBalance) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="conversion">Opening Value</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="openingValue" class="form-control  " name="openingValue" placeholder="Enter openingValue" aria-label="Enter openingValue" aria-describedby="openingValue" value="{{ old('openingValue', $stockItem->openingValue) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="openingRate">Opening Rate</label>
                                    <div class="col-sm-10">
                                    <input type="text" id="openingRate" class="form-control  " name="openingRate" placeholder="Enter Opening Rate" aria-label="Enter Opening Rate" aria-describedby="openingRate" value="{{ old('openingRate', $stockItem->openingRate) }}" />
                                    </div>
                                </div>
                                
                                <div class="row justify-content-end">
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
