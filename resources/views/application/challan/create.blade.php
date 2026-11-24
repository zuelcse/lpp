@extends('layouts/contentNavbarLayout')

@section('title', 'New Delivery Challan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('New Delivery Challan') }}</h5>
                    <a href="{{ route('challan-list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-list-ul me-1"></i> Challan List
                    </a>
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
