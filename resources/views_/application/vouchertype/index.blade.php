@extends('layouts/contentNavbarLayout')

@section('title', 'Voucher')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Voucher') }}</span>
                </div>


                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Number</th>
                                        <th>Reference Number</th>
                                        <th>Reference Date</th>
                                        <th>Narration</th>
                                        <th>Party Name</th>
                                        <th>Place of Supply</th>
                                        <th>Invoice</th>
                                        <th>Accounting Voucher</th>
                                        <th>Inventory Voucher</th>
                                        <th>Order Voucher</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    
                                    @forelse($vouchers as $key => $item)
                                        <tr>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $getVoucherType[$item->voucher_type] }}</td>
                                            <td>{{ $item->voucher_number }}</td>
                                            <td>{{ $item->reference_number }}</td>
                                            <td>{{ $item->reference_date }}</td>
                                            <td>{{ $item->narration }}</td>
                                            <td>{{ $item->party_name }}</td>
                                            <td>{{ $item->place_of_supply }}</td>
                                            <td>{{ $item->is_invoice }}</td>
                                            <td>{{ $item->is_accounting_voucher }}</td>
                                            <td>{{ $item->is_inventory_voucher }}</td>
                                            <td>{{ $item->is_order_voucher }}</td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            No Item Found.
                                        </tr>
                                    @endforelse


                                </tbody>
                            </table>
                            <div>
                                {{ $vouchers->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
