@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Challan - ' . $challan->challan_no)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Edit Delivery Challan') }} - <span class="text-primary">{{ $challan->challan_no }}</span></h5>
                    <div>
                        <a href="{{ route('challan-details', $challan->id) }}" class="btn btn-outline-info btn-sm me-1">
                            <i class="bx bx-show me-1"></i> View / Print
                        </a>
                        <a href="{{ route('challan-list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-list-ul me-1"></i> Challan List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @include('alerts.alert')
                    @include('application.challan._form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
    @include('application.challan._form_script')
@endsection
